<?php
// Sesuaikan path ke file koneksi Anda
include("../../../../controller/connect.php"); 

session_name('kost');
session_start();

// Validasi akses
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['id_user'])) {
    $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Akses Ditolak', 'text' => 'Akses tidak diizinkan.'];
    header("Location: ../../index.php?page=konfirmasi_pembayaran"); 
    exit;
}

if (isset($_POST['id_pemesanan'], $_POST['action'])) {
    $id_pemesanan = intval($_POST['id_pemesanan']);
    $action = $_POST['action'];

    $mysqli->begin_transaction();
    try {
        if ($action === 'terima') {
            // --- LOGIKA MENERIMA PESANAN ---
            $id_admin_konfirmasi = $_SESSION['id_user']; 
            $jumlah_bayar = $_POST['jumlah_bayar'] ?? '0'; // Ambil langsung, tidak perlu membersihkan format
            $tanggal_bayar = $_POST['tanggal_bayar'] ?? date('Y-m-d');
            $nama_bukti = null;

            // Logika upload file
            if (isset($_FILES['bukti_transaksi']) && $_FILES['bukti_transaksi']['error'] === 0) {
                $targetDir = "../../../../assets/uploads/bukti_bayar/"; // Sesuaikan path jika perlu
                if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                $ext = strtolower(pathinfo($_FILES['bukti_transaksi']['name'], PATHINFO_EXTENSION));
                $nama_bukti = "bukti_" . $id_pemesanan . "_" . time() . "." . $ext;
                if (!move_uploaded_file($_FILES['bukti_transaksi']['tmp_name'], $targetDir . $nama_bukti)) {
                   throw new Exception("Gagal mengupload bukti transaksi.");
                }
            }

            // 1. Catat pembayaran ke tabel `pembayaran`
            $stmt_bayar = $mysqli->prepare("INSERT INTO pembayaran (id_pemesanan, tanggal_bayar, jumlah_bayar, bukti_transaksi, status, dikonfirmasi_oleh) VALUES (?, ?, ?, ?, 'Lunas', ?)");
            $stmt_bayar->bind_param("isdsi", $id_pemesanan, $tanggal_bayar, $jumlah_bayar, $nama_bukti, $id_admin_konfirmasi);
            $stmt_bayar->execute();
            $stmt_bayar->close();

            // 2. Update status kontrak menjadi 'Aktif'
            $stmt_pesanan = $mysqli->prepare("UPDATE pemesanan SET status_kontrak = 'Aktif' WHERE id_pemesanan = ?");
            $stmt_pesanan->bind_param("i", $id_pemesanan);
            $stmt_pesanan->execute();
            $stmt_pesanan->close();

            $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Berhasil!', 'text' => 'Pemesanan telah dikonfirmasi dan diaktifkan.'];

        } elseif ($action === 'tolak') {
            // --- LOGIKA MENOLAK PESANAN ---
            // 1. Update status kontrak menjadi 'Dibatalkan'
            $stmt_pesanan = $mysqli->prepare("UPDATE pemesanan SET status_kontrak = 'Dibatalkan' WHERE id_pemesanan = ?");
            $stmt_pesanan->bind_param("i", $id_pemesanan);
            $stmt_pesanan->execute();
            $stmt_pesanan->close();

            // 2. Kembalikan stok kamar dan fasilitas
            $stmt_items = $mysqli->prepare("SELECT tipe_item, id_item, jumlah FROM detail_pemesanan WHERE id_pemesanan = ?");
            $stmt_items->bind_param("i", $id_pemesanan);
            $stmt_items->execute();
            $result_items = $stmt_items->get_result();
            if ($result_items->num_rows > 0) {
                $stmt_kamar = $mysqli->prepare("UPDATE kamar SET status = 'Kosong' WHERE id_kamar = ?");
                $stmt_stok = $mysqli->prepare("UPDATE fasilitas SET stok = stok + ? WHERE id_fasilitas = ?");
                while ($item = $result_items->fetch_assoc()) {
                    if ($item['tipe_item'] === 'kamar') {
                        $stmt_kamar->bind_param("i", $item['id_item']);
                        $stmt_kamar->execute();
                    } elseif ($item['tipe_item'] === 'fasilitas') {
                        $stmt_stok->bind_param("ii", $item['jumlah'], $item['id_item']);
                        $stmt_stok->execute();
                    }
                }
                $stmt_kamar->close();
                $stmt_stok->close();
            }
            $stmt_items->close();

            $_SESSION['alert'] = ['icon' => 'info', 'title' => 'Berhasil!', 'text' => 'Pemesanan telah ditolak dan dibatalkan. Stok telah dikembalikan.'];
        }

        $mysqli->commit();

    } catch (Exception $e) {
        $mysqli->rollback();
        $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Terjadi kesalahan: ' . $e->getMessage()];
    }
} else {
    $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Data tidak lengkap.'];
}

header("Location: ../../../index?pembayaran=konfirmasi_pembayaran"); 
exit;