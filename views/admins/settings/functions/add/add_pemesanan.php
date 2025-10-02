<?php
// Ganti dengan path file koneksi Anda yang benar
include("../../../../controller/connect.php"); 

session_name('kost');
session_start();

// Hanya proses jika request adalah POST dan admin sudah login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id_user'])) {

    // Pastikan semua field yang dibutuhkan terisi
    if (isset($_POST['id_user'], $_POST['id_kamar'], $_POST['tanggal_mulai_kontrak'], $_POST['tanggal_akhir_kontrak'])) {

        $id_user = intval($_POST['id_user']);
        $id_kamar = intval($_POST['id_kamar']);
        $tanggal_mulai = $_POST['tanggal_mulai_kontrak'];
        $tanggal_akhir = $_POST['tanggal_akhir_kontrak'];
        $jumlah_fasilitas_map = $_POST['jumlah_fasilitas'] ?? [];
        $tanggal_pesan_hari_ini = date('Y-m-d');

        // Validasi tanggal
        if (new DateTime($tanggal_akhir) <= new DateTime($tanggal_mulai)) {
            $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Tanggal akhir kontrak harus setelah tanggal mulai.'];
            header("Location: ../../../index?page=data_pemesanan&action=tambah");
            exit;
        }

        $mysqli->begin_transaction();
        try {
            // Cek apakah user sudah punya pesanan aktif/pending
            $stmt_cek_pending = $mysqli->prepare("SELECT id_pemesanan FROM pemesanan WHERE id_user = ? AND status_kontrak IN ('Menunggu', 'Aktif')");
            $stmt_cek_pending->bind_param("i", $id_user);
            $stmt_cek_pending->execute();
            if ($stmt_cek_pending->get_result()->num_rows > 0) {
                throw new Exception("Penyewa ini sudah memiliki kontrak aktif atau menunggu pembayaran.");
            }
            $stmt_cek_pending->close();

            // Ambil harga kamar & pastikan masih kosong
            $stmt_kamar = $mysqli->prepare("SELECT harga FROM kamar WHERE id_kamar = ? AND status = 'Kosong' FOR UPDATE");
            $stmt_kamar->bind_param("i", $id_kamar);
            $stmt_kamar->execute();
            $kamar_data = $stmt_kamar->get_result()->fetch_assoc();
            if (!$kamar_data) throw new Exception("Kamar tidak tersedia atau sudah dipesan.");
            $harga_kamar = $kamar_data['harga'];
            $stmt_kamar->close();

            $biaya_bulanan_kamar = $harga_kamar;
            $biaya_tambahan_fasilitas = 0;
            $fasilitas_to_insert = [];

            if (!empty($jumlah_fasilitas_map)) {
                $selected_fasilitas_ids = [];
                foreach ($jumlah_fasilitas_map as $id => $jumlah) {
                    if (intval($jumlah) > 0) {
                        $selected_fasilitas_ids[] = intval($id);
                    }
                }

                if (!empty($selected_fasilitas_ids)) {
                    $placeholders = implode(',', array_fill(0, count($selected_fasilitas_ids), '?'));
                    $types = str_repeat('i', count($selected_fasilitas_ids));
                    $stmt_fasilitas = $mysqli->prepare("SELECT id_fasilitas, nama_fasilitas, harga, stok FROM fasilitas WHERE id_fasilitas IN ($placeholders)");
                    $stmt_fasilitas->bind_param($types, ...$selected_fasilitas_ids);
                    $stmt_fasilitas->execute();
                    $result_fasilitas = $stmt_fasilitas->get_result();

                    while ($fasilitas = $result_fasilitas->fetch_assoc()) {
                        $id_fas = $fasilitas['id_fasilitas'];
                        $jumlah_dipesan = intval($jumlah_fasilitas_map[$id_fas]);
                        if ($fasilitas['stok'] < $jumlah_dipesan) throw new Exception("Stok untuk " . htmlspecialchars($fasilitas['nama_fasilitas']) . " tidak mencukupi.");
                        
                        $biaya_tambahan_fasilitas += $fasilitas['harga'] * $jumlah_dipesan;
                        $fasilitas_to_insert[] = ['id' => $id_fas, 'harga' => $fasilitas['harga'], 'jumlah' => $jumlah_dipesan];
                    }
                    $stmt_fasilitas->close();
                }
            }
            
            $biaya_bulanan_total = $biaya_bulanan_kamar + $biaya_tambahan_fasilitas;
            $date1 = new DateTime($tanggal_mulai);
            $date2 = new DateTime($tanggal_akhir);
            $bulan_sewa = ceil($date1->diff($date2)->days / 30);
            if ($bulan_sewa < 1) $bulan_sewa = 1; // Minimal sewa 1 bulan
            $total_biaya_keseluruhan = $biaya_bulanan_total * $bulan_sewa;

            // PERBAIKAN: Status awal diubah menjadi 'Pending'
            $stmt_pemesanan = $mysqli->prepare("INSERT INTO pemesanan (id_user, tanggal_pesan, tanggal_mulai_kontrak, tanggal_akhir_kontrak, total, biaya_bulanan, status_kontrak) VALUES (?, ?, ?, ?, ?, ?, 'Menunggu')");
            $stmt_pemesanan->bind_param("isssds", $id_user, $tanggal_pesan_hari_ini, $tanggal_mulai, $tanggal_akhir, $total_biaya_keseluruhan, $biaya_bulanan_total);
            $stmt_pemesanan->execute();
            $id_pemesanan_baru = $mysqli->insert_id;
            $stmt_pemesanan->close();

            $stmt_detail = $mysqli->prepare("INSERT INTO detail_pemesanan (id_pemesanan, tipe_item, id_item, jumlah, harga_saat_pesan) VALUES (?, ?, ?, ?, ?)");
            
            $tipe_kamar = 'kamar';
            $jumlah_kamar = 1;
            $stmt_detail->bind_param("isids", $id_pemesanan_baru, $tipe_kamar, $id_kamar, $jumlah_kamar, $harga_kamar);
            $stmt_detail->execute();

            if (!empty($fasilitas_to_insert)) {
                $tipe_fasilitas = 'fasilitas';
                $stmt_stok = $mysqli->prepare("UPDATE fasilitas SET stok = stok - ? WHERE id_fasilitas = ?");
                foreach ($fasilitas_to_insert as $item) {
                    $stmt_detail->bind_param("isids", $id_pemesanan_baru, $tipe_fasilitas, $item['id'], $item['jumlah'], $item['harga']);
                    $stmt_detail->execute();
                    $stmt_stok->bind_param("ii", $item['jumlah'], $item['id']);
                    $stmt_stok->execute();
                }
                $stmt_stok->close();
            }
            $stmt_detail->close();
            
            $stmt_update_kamar = $mysqli->prepare("UPDATE kamar SET status = 'Terisi' WHERE id_kamar = ?");
            $stmt_update_kamar->bind_param("i", $id_kamar);
            $stmt_update_kamar->execute();
            $stmt_update_kamar->close();

            $mysqli->commit();
            $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Berhasil!', 'text' => 'Kontrak baru berhasil dibuat dan menunggu pembayaran.'];
        } catch (Exception $e) {
            $mysqli->rollback();
            $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    } else {
        $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Data yang dikirim tidak lengkap.'];
    }
} else {
    $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Akses Ditolak', 'text' => 'Akses tidak diizinkan.'];
}

header("Location: ../../../index?pemesanan=data_pemesanan"); 
exit;
?>