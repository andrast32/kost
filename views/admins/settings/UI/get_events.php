<?php
// Sesuaikan path ke file koneksi Anda
require '../../../controller/connect.php';

// Query untuk mengambil data kontrak yang aktif
$query_events = $mysqli->query("
    SELECT 
        u.nama_user, 
        p.tanggal_mulai_kontrak, 
        p.tanggal_akhir_kontrak
    FROM pemesanan p
    JOIN user u ON p.id_user = u.id_user
    WHERE p.status_kontrak = 'Aktif'
");

$events = [];
while ($row = $query_events->fetch_assoc()) {
    // Event untuk tanggal mulai
    $events[] = [
        'title' => 'Masuk: ' . $row['nama_user'],
        'start' => $row['tanggal_mulai_kontrak'],
        'allDay' => true,
        'type' => 'mulai' // Properti kustom untuk styling
    ];
    // Event untuk tanggal akhir
    $events[] = [
        'title' => 'Selesai: ' . $row['nama_user'],
        'start' => $row['tanggal_akhir_kontrak'],
        'allDay' => true,
        'type' => 'selesai'
    ];
}

// Kembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($events);
?>