<?php
// Sesuaikan path ke file koneksi Anda
include '../../../../controller/connect.php';

// Cek apakah ada parameter bulan dan tahun di URL
$bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : 0;
$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : 0;

// Array "kamus" bulan Indonesia
$nama_bulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

if ($bulan > 0 && $tahun > 0) {
    // ---- LOGIKA UNTUK TAMPILAN DETAIL LAPORAN ----
    $stmt = $mysqli->prepare("
        SELECT
            py.id_pembayaran, u.nama_user, py.tanggal_bayar, p.tanggal_akhir_kontrak, py.jumlah_bayar, p.status_kontrak, py.status AS status_pembayaran
        FROM pembayaran py
        JOIN pemesanan p ON py.id_pemesanan = p.id_pemesanan
        JOIN user u ON p.id_user = u.id_user
        WHERE YEAR(py.tanggal_bayar) = ? AND MONTH(py.tanggal_bayar) = ?
        ORDER BY py.tanggal_bayar ASC
    ");
    $stmt->bind_param("ii", $tahun, $bulan);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    // ---- LOGIKA UNTUK TAMPILAN ARSIP ----
    $query_arsip = $mysqli->query("
        SELECT DISTINCT YEAR(tanggal_bayar) AS tahun, MONTH(tanggal_bayar) AS bulan 
        FROM pembayaran
        ORDER BY tahun DESC, bulan DESC
    ");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Laporan Pembayaran 
        <?php if($bulan > 0): echo $nama_bulan[$bulan] . ' ' . $tahun; else: echo 'Arsip'; endif; ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; }
        .archive-card, .print-sheet { background: white; box-shadow: 0 0 15px rgba(0,0,0,0.07); }
        .archive-card { transition: all 0.2s ease-in-out; }
        .archive-card:hover { transform: translateY(-5px); box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important; }
        .print-button-container { position: fixed; bottom: 20px; right: 20px; }
        @media print {
            body { background-color: white; }
            .print-sheet { box-shadow: none; margin: 0; padding: 0; }
            .print-button-container, .back-button, .navbar { display: none !important; }
            @page { size: A4 landscape; margin: 20mm; }
        }
    </style>
</head>
<body>

<div class="container my-5">

    <?php if ($bulan > 0 && $tahun > 0): ?>
        <a href="download_pdf" class="btn btn-secondary mb-3 back-button"><i class="fas fa-arrow-left me-2"></i>Kembali ke Arsip</a>
        <div id="pdf-content" class="print-sheet p-5">
            <div class="text-center mb-4 pb-3 border-bottom">
                <h2>Laporan Riwayat Pembayaran</h2>
                <h4>Periode: <?= $nama_bulan[$bulan] . ' ' . $tahun ?></h4>
                <p class="text-muted">Dicetak oleh Admin pada: <?= date('d') . ' ' . $nama_bulan[date('n')] . ' ' . date('Y') ?></p>
            </div>

            <table class="table table-bordered table-sm">
                <thead class="table-light text-center">
                    <tr>
                        <th>ID Bayar</th>
                        <th>Nama Penyewa</th>
                        <th>Tgl. Bayar</th>
                        <th>Tgl. Selesai Kontrak</th>
                        <th>Jumlah Bayar</th>
                        <th>Status Kontrak</th>
                        <th>Status Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($data) > 0): ?>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td class="text-center">#<?= $row['id_pembayaran'] ?></td>
                                <td><?= htmlspecialchars($row['nama_user']) ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal_bayar'])) ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal_akhir_kontrak'])) ?></td>
                                <td class="text-end">Rp <?= number_format($row['jumlah_bayar'], 0, ',', '.') ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['status_kontrak']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['status_pembayaran']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center p-4">Tidak ada data pembayaran untuk periode ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="print-button-container">
            <button onclick="window.print()" class="btn btn-primary btn-lg shadow">
                <i class="fas fa-print me-2"></i> Cetak atau Simpan sebagai PDF
            </button>
        </div>

    <?php else: ?>
        <div class="text-center mb-4">
            <a href="../../../index?pembayaran=data_pembayaran" class="btn btn-secondary mb-3 back-button"><i class="fas fa-arrow-left me-2"></i>Kembali ke data pembayaran</a>
            <h1 class="display-5 fw-bold">Arsip Laporan Pembayaran</h1>
            <p class="lead text-muted">Pilih periode laporan yang ingin Anda lihat atau cetak.</p>
        </div>
        <div class="row g-3">
            <?php if ($query_arsip->num_rows > 0): ?>
                <?php while($arsip = $query_arsip->fetch_assoc()): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card shadow-sm archive-card">
                            <div class="card-body text-center p-4">
                                <i class="fas fa-file-invoice-dollar fa-3x text-primary mb-3"></i>
                                <h5 class="card-title"><?= $nama_bulan[$arsip['bulan']] . ' ' . $arsip['tahun'] ?></h5>
                                <a href="download_pdf?bulan=<?= $arsip['bulan'] ?>&tahun=<?= $arsip['tahun'] ?>" class="btn btn-outline-primary mt-3">
                                    Lihat Laporan <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-muted">Belum ada data pembayaran untuk ditampilkan.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>