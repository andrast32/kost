<?php
// Ganti dengan path file koneksi Anda yang benar
include("../../../../controller/connect.php"); 

session_name('kost');
session_start();

// Hanya proses jika request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Pastikan semua field yang dibutuhkan terisi
    if (isset($_POST['id_user'], $_POST['id_kamar'], $_POST['tanggal_masuk'], $_POST['tanggal_keluar'])) {

        $id_user = intval($_POST['id_user']);
        $id_kamar = intval($_POST['id_kamar']);
        $tanggal_masuk = $_POST['tanggal_masuk'];
        $tanggal_keluar = $_POST['tanggal_keluar'];
        $selected_fasilitas = $_POST['fasilitas'] ?? [];

        // 1. Validasi tanggal
        if (new DateTime($tanggal_keluar) <= new DateTime($tanggal_masuk)) {
            $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Tanggal keluar harus setelah tanggal masuk.'];
            header("Location: ../buat_pemesanan.php"); // Redirect kembali ke form
            exit;
        }

        // 2. Ambil harga kamar & pastikan masih kosong
        $stmt_kamar = $mysqli->prepare("SELECT harga FROM kamar WHERE id_kamar = ? AND status = 'Kosong'");
        $stmt_kamar->bind_param("i", $id_kamar);
        $stmt_kamar->execute();
        $result_kamar = $stmt_kamar->get_result();
        
        if ($result_kamar->num_rows === 0) {
            $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Kamar tidak tersedia atau sudah dipesan orang lain.'];
            header("Location: ../buat_pemesanan.php");
            exit;
        }
        $kamar = $result_kamar->fetch_assoc();
        $harga_kamar = $kamar['harga'];
        $stmt_kamar->close();

        // 3. Hitung biaya kamar
        $date1 = new DateTime($tanggal_masuk);
        $date2 = new DateTime($tanggal_keluar);
        $bulan_sewa = ceil($date1->diff($date2)->days / 30);
        $total_biaya_kamar = $harga_kamar * $bulan_sewa;

        // 4. Hitung biaya fasilitas (jika ada)
        $total_biaya_fasilitas = 0;
        $fasilitas_to_insert = [];
        if (!empty($selected_fasilitas)) {
            $placeholders = implode(',', array_fill(0, count($selected_fasilitas), '?'));
            $types = str_repeat('i', count($selected_fasilitas));
            
            $stmt_fasilitas = $mysqli->prepare("SELECT id_fasilitas, nama_fasilitas, harga, stok FROM fasilitas WHERE id_fasilitas IN ($placeholders)");
            
            // Mengikat parameter dinamis
            $stmt_fasilitas->bind_param($types, ...$selected_fasilitas);
            $stmt_fasilitas->execute();
            $result_fasilitas = $stmt_fasilitas->get_result();

            while ($fasilitas = $result_fasilitas->fetch_assoc()) {
                if ($fasilitas['stok'] < 1) {
                    $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Stok untuk ' . htmlspecialchars($fasilitas['nama_fasilitas']) . ' telah habis.'];
                    header("Location: ../buat_pemesanan.php");
                    exit;
                }
                $total_biaya_fasilitas += $fasilitas['harga'];
                $fasilitas_to_insert[] = ['id' => $fasilitas['id_fasilitas'], 'harga' => $fasilitas['harga']];
            }
            $stmt_fasilitas->close();
        }

        // 5. Hitung total biaya
        $total_biaya = $total_biaya_kamar + $total_biaya_fasilitas;

        // 6. Masukkan data ke tabel `pemesanan`
        $tanggal_pesan_hari_ini = date('Y-m-d'); // <-- INI PERBAIKANNYA

        $stmt_pemesanan = $mysqli->prepare(
            "INSERT INTO pemesanan (id_user, id_kamar, tanggal_masuk, tanggal_keluar, total, status, tanggal_pesan) VALUES (?, ?, ?, ?, ?, 'Diterima', ?)"
        );
        $stmt_pemesanan->bind_param("iissds", $id_user, $id_kamar, $tanggal_masuk, $tanggal_keluar, $total_biaya, $tanggal_pesan_hari_ini);
        
        if ($stmt_pemesanan->execute()) {
            $id_pemesanan_baru = $mysqli->insert_id;
            $stmt_pemesanan->close();

            // 7. Masukkan data ke `detail_pemesanan_fasilitas` (jika ada)
            if (!empty($fasilitas_to_insert)) {
                $stmt_detail = $mysqli->prepare("INSERT INTO pemesanan_fasilitas (id_pemesanan, id_fasilitas, harga_saat_pesan) VALUES (?, ?, ?)");
                $stmt_stok = $mysqli->prepare("UPDATE fasilitas SET stok = stok - 1 WHERE id_fasilitas = ?");
                foreach ($fasilitas_to_insert as $item) {
                    $stmt_detail->bind_param("iid", $id_pemesanan_baru, $item['id'], $item['harga']);
                    $stmt_detail->execute();
                    $stmt_stok->bind_param("i", $item['id']);
                    $stmt_stok->execute();
                }
                $stmt_detail->close();
                $stmt_stok->close();
            }
            
            // 8. Update status kamar
            $stmt_update_kamar = $mysqli->prepare("UPDATE kamar SET status = 'Terisi' WHERE id_kamar = ?");
            $stmt_update_kamar->bind_param("i", $id_kamar);
            $stmt_update_kamar->execute();
            $stmt_update_kamar->close();

            $_SESSION['alert'] = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Pemesanan baru berhasil dibuat! Total Biaya: Rp ' . number_format($total_biaya)
            ];

        } else {
            // Jika gagal menyimpan pemesanan utama
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Gagal menyimpan data pemesanan utama.'
            ];
        }

    } else {
        // Jika field wajib tidak terisi
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Gagal!',
            'text' => 'Data yang dikirim tidak lengkap.'
        ];
    }
} else {
    // Jika file diakses langsung
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Akses Ditolak',
        'text' => 'Anda tidak diizinkan mengakses halaman ini secara langsung.'
    ];
}

// Redirect kembali ke halaman daftar pemesanan atau form
// Ganti 'daftar_pemesanan.php' jika nama file Anda berbeda
header("Location: ../../../index"); 
exit;