<?php
include("../../../../controller/connect.php"); 

session_name('kost');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id_user'])) {
    if (isset($_POST['id_user'], $_POST['id_kamar'], $_POST['tanggal_mulai_kontrak'], $_POST['tanggal_akhir_kontrak'])) {

        $id_user = intval($_POST['id_user']);
        $id_kamar = intval($_POST['id_kamar']);
        $tanggal_mulai = $_POST['tanggal_mulai_kontrak'];
        $tanggal_akhir = $_POST['tanggal_akhir_kontrak'];
        $selected_fasilitas = $_POST['fasilitas'] ?? [];
        $tanggal_pesan_hari_ini = date('Y-m-d');

        if (new DateTime($tanggal_akhir) <= new DateTime($tanggal_mulai)) {
            $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Tanggal akhir kontrak harus setelah tanggal mulai.'];
            header("Location: ../../../index?pemesanan=data_pemesanan");
            exit;
        }

        $mysqli->begin_transaction();
        try {
            $stmt_kamar = $mysqli->prepare("SELECT harga FROM kamar WHERE id_kamar = ? AND status = 'Kosong' FOR UPDATE");
            $stmt_kamar->bind_param("i", $id_kamar);
            $stmt_kamar->execute();
            $kamar_data = $stmt_kamar->get_result()->fetch_assoc();
            if (!$kamar_data) throw new Exception("Kamar tidak tersedia.");
            $harga_kamar = $kamar_data['harga'];
            $stmt_kamar->close();

            $biaya_bulanan_kamar = $harga_kamar;
            $biaya_tambahan_fasilitas = 0;
            $fasilitas_to_insert = [];

            if (!empty($selected_fasilitas)) {
                $placeholders = implode(',', array_fill(0, count($selected_fasilitas), '?'));
                $types = str_repeat('i', count($selected_fasilitas));
                $stmt_fasilitas = $mysqli->prepare("SELECT id_fasilitas, nama_fasilitas, harga, stok FROM fasilitas WHERE id_fasilitas IN ($placeholders)");
                $stmt_fasilitas->bind_param($types, ...$selected_fasilitas);
                $stmt_fasilitas->execute();
                $result_fasilitas = $stmt_fasilitas->get_result();
                while ($fasilitas = $result_fasilitas->fetch_assoc()) {
                    if ($fasilitas['stok'] < 1) throw new Exception("Stok untuk " . htmlspecialchars($fasilitas['nama_fasilitas']) . " habis.");
                    $biaya_tambahan_fasilitas += $fasilitas['harga'];
                    $fasilitas_to_insert[] = ['id' => $fasilitas['id_fasilitas'], 'harga' => $fasilitas['harga']];
                }
                $stmt_fasilitas->close();
            }

            $biaya_bulanan_total = $biaya_bulanan_kamar + $biaya_tambahan_fasilitas;
            $date1 = new DateTime($tanggal_mulai);
            $date2 = new DateTime($tanggal_akhir);
            $bulan_sewa = ceil($date1->diff($date2)->days / 30);
            $total_biaya_keseluruhan = $biaya_bulanan_total * $bulan_sewa;

            $stmt_pemesanan = $mysqli->prepare("INSERT INTO pemesanan (id_user, tanggal_pesan, tanggal_mulai_kontrak, tanggal_akhir_kontrak, total, biaya_bulanan, status_kontrak) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
            $stmt_pemesanan->bind_param("isssds", $id_user, $tanggal_pesan_hari_ini, $tanggal_mulai, $tanggal_akhir, $total_biaya_keseluruhan, $biaya_bulanan_total);
            $stmt_pemesanan->execute();
            $id_pemesanan_baru = $mysqli->insert_id;
            $stmt_pemesanan->close();

            $stmt_detail = $mysqli->prepare("INSERT INTO detail_pemesanan (id_pemesanan, tipe_item, id_item, harga_saat_pesan) VALUES (?, ?, ?, ?)");
            
            $tipe_kamar = 'kamar';
            $stmt_detail->bind_param("isid", $id_pemesanan_baru, $tipe_kamar, $id_kamar, $harga_kamar);
            $stmt_detail->execute();

            if (!empty($fasilitas_to_insert)) {
                $tipe_fasilitas = 'fasilitas';
                $stmt_stok = $mysqli->prepare("UPDATE fasilitas SET stok = stok - 1 WHERE id_fasilitas = ?");
                foreach ($fasilitas_to_insert as $item) {
                    $stmt_detail->bind_param("isid", $id_pemesanan_baru, $tipe_fasilitas, $item['id'], $item['harga']);
                    $stmt_detail->execute();
                    $stmt_stok->bind_param("i", $item['id']);
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
            $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Berhasil!', 'text' => 'Kontrak baru berhasil dibuat!'];
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