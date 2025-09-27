<?php
// Sesuaikan path ke file koneksi Anda
include("../../../../controller/connect.php"); 

session_name('kost');
session_start();

// Hanya izinkan akses melalui metode POST dan jika admin sudah login
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['id_user'])) {
    $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Akses Ditolak', 'text' => 'Akses tidak diizinkan.'];
    // Redirect ke halaman utama admin
    header("Location: ../../../index?pembayaran=data_pembayaran"); 
    exit;
}

// 1. Validasi Data Input
// Pengecekan `!empty()` adalah kunci untuk mencegah error "ID Pemesanan kosong"
if (isset($_POST['id_pemesanan'], $_POST['jumlah_bayar'], $_POST['tanggal_bayar']) && !empty($_POST['id_pemesanan'])) {

    $id_pemesanan = intval($_POST['id_pemesanan']);
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $tanggal_bayar = $_POST['tanggal_bayar'];
    $nama_bukti = null; // Default nama file bukti adalah null

    // 2. Proses Upload File Bukti (Jika Ada)
    if (isset($_FILES['bukti_transaksi']) && $_FILES['bukti_transaksi']['error'] === 0) {
        // Tentukan folder tujuan upload
        $targetDir = "../../../../../assets/uploads/bukti_bayar/"; // Sesuaikan path jika perlu
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $ext = strtolower(pathinfo($_FILES['bukti_transaksi']['name'], PATHINFO_EXTENSION));
        $nama_bukti = "bukti_" . $id_pemesanan . "_" . time() . "." . $ext;
        
        if (!move_uploaded_file($_FILES['bukti_transaksi']['tmp_name'], $targetDir . $nama_bukti)) {
            $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Gagal mengupload file bukti transaksi.'];
            header("Location: ../../../index?pembayaran=data_pembayaran"); 
            exit;
        }
    }

    // 3. Proses Database dengan Transaksi (untuk keamanan data)
    $mysqli->begin_transaction();
    try {
        // Langkah A: Masukkan data ke tabel `pembayaran`
        $stmt_bayar = $mysqli->prepare(
            "INSERT INTO pembayaran (id_pemesanan, tanggal_bayar, jumlah_bayar, bukti_transaksi, status) VALUES (?, ?, ?, ?, 'Lunas')"
        );
        $stmt_bayar->bind_param("isds", $id_pemesanan, $tanggal_bayar, $jumlah_bayar, $nama_bukti);
        $stmt_bayar->execute();
        $stmt_bayar->close();

        // Langkah B: Update status di tabel `pemesanan` dari 'Pending' menjadi 'Aktif'
        $stmt_pesanan = $mysqli->prepare("UPDATE pemesanan SET status_kontrak = 'Aktif' WHERE id_pemesanan = ? AND status_kontrak = 'Pending'");
        $stmt_pesanan->bind_param("i", $id_pemesanan);
        $stmt_pesanan->execute();
        $stmt_pesanan->close();

        // Jika semua langkah berhasil, simpan perubahan secara permanen
        $mysqli->commit();
        $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Berhasil!', 'text' => 'Pembayaran telah berhasil diproses.'];

    } catch (Exception $e) {
        // Jika ada error di salah satu langkah, batalkan semua perubahan
        $mysqli->rollback();
        $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Terjadi kesalahan database: ' . $e->getMessage()];
    }

} else {
    // Jika data dari form tidak lengkap atau id_pemesanan kosong
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Gagal!',
        'text' => 'Data tidak lengkap atau ID Pemesanan kosong.'
    ];
}

// 4. Redirect kembali ke halaman daftar pemesanan
header("Location: ../../../index?pembayaran=data_pembayaran"); 
exit;