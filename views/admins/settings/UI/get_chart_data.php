<?php
// Sesuaikan path ke file koneksi Anda
require '../../../controller/connect.php';

// Array "kamus" bulan Indonesia
$nama_bulan_singkat = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];

$labels = [];
$values = [];

// Loop untuk 6 bulan terakhir (termasuk bulan ini)
for ($i = 5; $i >= 0; $i--) {
    $target_date = new DateTime("first day of -$i month");
    $tahun = $target_date->format('Y');
    $bulan = $target_date->format('n');

    // Tambahkan label (misal: "Okt 2025")
    $labels[] = $nama_bulan_singkat[$bulan] . ' ' . $tahun;

    // Query untuk menjumlahkan pendapatan pada bulan dan tahun tersebut
    $stmt = $mysqli->prepare("
        SELECT SUM(jumlah_bayar) as total 
        FROM pembayaran 
        WHERE status = 'Lunas' AND MONTH(tanggal_bayar) = ? AND YEAR(tanggal_bayar) = ?
    ");
    $stmt->bind_param("ii", $bulan, $tahun);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    // Masukkan total pendapatan (atau 0 jika tidak ada)
    $values[] = $result['total'] ?? 0;
}

// Siapkan data dalam format array
$chart_data = [
    'labels' => $labels,
    'values' => $values,
];

// Kembalikan sebagai JSON
header('Content-Type: application/json');
echo json_encode($chart_data);
?>