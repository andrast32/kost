<?php
// Sesuaikan path ke file koneksi Anda
include '../../../../controller/connect.php';

// 1. Ambil data dari database
$query_pembayaran = $mysqli->query("
    SELECT
        py.id_pembayaran, u.nama_user, py.tanggal_bayar, p.tanggal_akhir_kontrak, 
        py.jumlah_bayar, p.status_kontrak, py.status AS status_pembayaran
    FROM pembayaran py
    JOIN pemesanan p ON py.id_pemesanan = p.id_pemesanan
    JOIN user u ON p.id_user = u.id_user
    WHERE p.status_kontrak != 'Pending'
    ORDER BY 
        CASE p.status_kontrak WHEN 'Aktif' THEN 1 WHEN 'Dibatalkan' THEN 2 WHEN 'Selesai' THEN 3 ELSE 4 END,
        py.tanggal_bayar DESC
");
$data = $query_pembayaran->fetch_all(MYSQLI_ASSOC);

// 2. Atur Header HTTP untuk download file .xls
$filename = 'Laporan_Pembayaran_TheKost_' . date('Y-m-d') . '.xls';
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

// PERUBAHAN: Tambahkan array "kamus" bulan Indonesia
$nama_bulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 
    7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran</title>
    <style>
        .header { font-size: 16px; font-weight: bold; text-align: center; }
        th { background-color: #f2f2f2; font-weight: bold; }
        td, th { border: 1px solid #ccc; padding: 8px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <p class="header" colspan="7">Laporan Riwayat Pembayaran - The Kost</p>
    <p class="header" colspan="7">Dicetak pada: <?= date('d') . ' ' . $nama_bulan[date('n')] . ' ' . date('Y') ?></p>
    <br>

    <table border="1">
        <thead>
            <tr>
                <th>ID Bayar</th>
                <th>Nama Penyewa</th>
                <th>Tanggal Bayar</th>
                <th>Tanggal Selesai</th>
                <th>Jumlah Bayar</th>
                <th>Status Kontrak</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($data) > 0): ?>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td class="text-center">#<?= $row['id_pembayaran'] ?></td>
                        <td><?= htmlspecialchars($row['nama_user']) ?></td>
                        <td class="text-center">
                            <?php 
                                $ts_bayar = strtotime($row['tanggal_bayar']);
                                echo date('d', $ts_bayar) . ' ' . $nama_bulan[date('n', $ts_bayar)] . ' ' . date('Y', $ts_bayar);
                            ?>
                        </td>
                        <td class="text-center">
                            <?php 
                                $ts_akhir = strtotime($row['tanggal_akhir_kontrak']);
                                echo date('d', $ts_akhir) . ' ' . $nama_bulan[date('n', $ts_akhir)] . ' ' . date('Y', $ts_akhir);
                            ?>
                        </td>
                        <td class="text-right"><?= 'Rp ' . number_format($row['jumlah_bayar'], 0, ',', '.') ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['status_kontrak']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['status_pembayaran']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php
exit();
?>