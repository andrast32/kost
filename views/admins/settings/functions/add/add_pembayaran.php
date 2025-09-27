<?php
include("../../../../controller/connect.php"); // Sesuaikan path koneksi

session_name('kost');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['id_pemesanan'], $_POST['jumlah_bayar'], $_POST['tanggal_bayar'])) {

        $id_pemesanan = intval($_POST['id_pemesanan']);
        $jumlah_bayar = $_POST['jumlah_bayar'];
        $tanggal_bayar = $_POST['tanggal_bayar'];
        $nama_bukti = null;

        // Proses upload file bukti
        if (isset($_FILES['bukti_transaksi']) && $_FILES['bukti_transaksi']['error'] === 0) {
            $targetDir = "../../../../../assets/uploads/bukti_bayar/"; // Sesuaikan path
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $ext = pathinfo($_FILES['bukti_transaksi']['name'], PATHINFO_EXTENSION);
            $nama_bukti = "bukti_" . $id_pemesanan . "_" . time() . "." . $ext;
            move_uploaded_file($_FILES['bukti_transaksi']['tmp_name'], $targetDir . $nama_bukti);
        }

        // Gunakan transaksi karena kita mengubah 2 tabel
        $mysqli->begin_transaction();
        try {
            // 1. Masukkan data ke tabel pembayaran
            $stmt_bayar = $mysqli->prepare(
                "INSERT INTO pembayaran (id_pemesanan, tanggal_bayar, jumlah_bayar, bukti_transaksi, status) VALUES (?, ?, ?, ?, 'Lunas')"
            );
            $stmt_bayar->bind_param("isds", $id_pemesanan, $tanggal_bayar, $jumlah_bayar, $nama_bukti);
            $stmt_bayar->execute();
            $stmt_bayar->close();

            // 2. Update status di tabel pemesanan menjadi 'Diterima'
            $stmt_pesanan = $mysqli->prepare("UPDATE pemesanan SET status = 'Diterima' WHERE id_pemesanan = ? AND status = 'Pending'");
            $stmt_pesanan->bind_param("i", $id_pemesanan);
            $stmt_pesanan->execute();
            $stmt_pesanan->close();

            // Jika semua berhasil, commit
            $mysqli->commit();
            $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Berhasil!', 'text' => 'Pembayaran telah berhasil diproses.'];

        } catch (Exception $e) {
            $mysqli->rollback();
            $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }

    } else {
        $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Data tidak lengkap.'];
    }
} else {
    $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Akses Ditolak', 'text' => 'Akses tidak diizinkan.'];
}

header("Location: ../../../index?pembayaran=data_pembayaran");
exit;