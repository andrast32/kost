<?php
require '../../../../settings/koneksi.php'; // Sesuaikan path koneksi
echo "Memulai Proses Check-Out Otomatis...<br>";

$tanggal_hari_ini = date('Y-m-d');

// 1. Cari semua pemesanan 'Diterima' yang sudah lewat tanggal keluarnya
$query_expired = $mysqli->prepare(
    "SELECT id_pemesanan, id_kamar FROM pemesanan WHERE tanggal_keluar < ? AND status = 'Diterima'"
);
$query_expired->bind_param("s", $tanggal_hari_ini);
$query_expired->execute();
$result_expired = $query_expired->get_result();

if ($result_expired->num_rows === 0) {
    echo "Tidak ada pemesanan yang perlu di-check-out hari ini.<br>";
    exit;
}

while ($pesanan = $result_expired->fetch_assoc()) {
    $id_pemesanan = $pesanan['id_pemesanan'];
    $id_kamar = $pesanan['id_kamar'];

    echo "Memproses check-out untuk pesanan #{$id_pemesanan}...<br>";
    
    $mysqli->begin_transaction();
    try {
        // 2. Update status pemesanan menjadi 'Selesai'
        $stmt_update_pesanan = $mysqli->prepare("UPDATE pemesanan SET status = 'Selesai' WHERE id_pemesanan = ?");
        $stmt_update_pesanan->bind_param("i", $id_pemesanan);
        $stmt_update_pesanan->execute();
        $stmt_update_pesanan->close();
        echo "- Status pesanan diubah menjadi 'Selesai'.<br>";

        // 3. Update status kamar menjadi 'Kosong'
        $stmt_update_kamar = $mysqli->prepare("UPDATE kamar SET status = 'Kosong' WHERE id_kamar = ?");
        $stmt_update_kamar->bind_param("i", $id_kamar);
        $stmt_update_kamar->execute();
        $stmt_update_kamar->close();
        echo "- Status kamar #{$id_kamar} diubah menjadi 'Kosong'.<br>";
        
        // 4. Ambil daftar fasilitas yang dipesan untuk dikembalikan stoknya
        $stmt_get_fasilitas = $mysqli->prepare(
            "SELECT id_fasilitas, jumlah FROM detail_pemesanan_fasilitas WHERE id_pemesanan = ?"
        );
        $stmt_get_fasilitas->bind_param("i", $id_pemesanan);
        $stmt_get_fasilitas->execute();
        $result_fasilitas = $stmt_get_fasilitas->get_result();
        
        if($result_fasilitas->num_rows > 0) {
            $stmt_update_stok = $mysqli->prepare("UPDATE fasilitas SET stok = stok + ? WHERE id_fasilitas = ?");
            while ($item = $result_fasilitas->fetch_assoc()) {
                $stmt_update_stok->bind_param("ii", $item['jumlah'], $item['id_fasilitas']);
                $stmt_update_stok->execute();
                echo "- Stok fasilitas #{$item['id_fasilitas']} dikembalikan sebanyak {$item['jumlah']}.<br>";
            }
            $stmt_update_stok->close();
        }
        $stmt_get_fasilitas->close();

        $mysqli->commit();
        echo "<b>Check-out untuk pesanan #{$id_pemesanan} BERHASIL.</b><br><hr>";

    } catch (Exception $e) {
        $mysqli->rollback();
        echo "<b>Check-out untuk pesanan #{$id_pemesanan} GAGAL: " . $e->getMessage() . "</b><br><hr>";
    }
}

echo "Proses Selesai.";
?>