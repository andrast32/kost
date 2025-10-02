<?php
// Diasumsikan Anda memiliki file koneksi di lokasi ini
// Sesuaikan path jika perlu
require '../../../controller/connect.php'; 

// 1. Validasi dan ambil ID Pembayaran dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID Pembayaran tidak valid atau tidak ditemukan.");
}
$id_pembayaran = intval($_GET['id']);

// 2. Query untuk mengambil semua data yang dibutuhkan untuk kwitansi
// Menggunakan alias untuk tabel user (penyewa dan admin)
$stmt = $mysqli->prepare("
    SELECT
        py.id_pembayaran, py.tanggal_bayar, py.jumlah_bayar,
        p.id_pemesanan, p.tanggal_mulai_kontrak, p.tanggal_akhir_kontrak,
        penyewa.nama_user AS nama_penyewa,
        admin.nama_user AS nama_petugas
    FROM pembayaran py
    JOIN pemesanan p ON py.id_pemesanan = p.id_pemesanan
    JOIN user penyewa ON p.id_user = penyewa.id_user
    LEFT JOIN user admin ON py.dikonfirmasi_oleh = admin.id_user
    WHERE py.id_pembayaran = ?
");
$stmt->bind_param("i", $id_pembayaran);
$stmt->execute();
$result = $stmt->get_result();
$data_pembayaran = $result->fetch_assoc();
$stmt->close();

if (!$data_pembayaran) {
    die("Data pembayaran tidak ditemukan.");
}

// 3. Ambil detail item yang dipesan untuk kwitansi ini
$id_pemesanan = $data_pembayaran['id_pemesanan'];
$stmt_items = $mysqli->prepare("
    SELECT 
        dp.tipe_item, dp.jumlah, dp.harga_saat_pesan,
        k.kode_kamar,
        f.nama_fasilitas
    FROM detail_pemesanan dp
    LEFT JOIN kamar k ON dp.id_item = k.id_kamar AND dp.tipe_item = 'kamar'
    LEFT JOIN fasilitas f ON dp.id_item = f.id_fasilitas AND dp.tipe_item = 'fasilitas'
    WHERE dp.id_pemesanan = ?
    ORDER BY FIELD(dp.tipe_item, 'kamar', 'fasilitas')
");
$stmt_items->bind_param("i", $id_pemesanan);
$stmt_items->execute();
$result_items = $stmt_items->get_result();
$items_pesanan = $result_items->fetch_all(MYSQLI_ASSOC);
$stmt_items->close();

// Ambil nama petugas dari hasil query, dengan fallback jika kosong
$nama_petugas = $data_pembayaran['nama_petugas'] ?? 'Admin Sistem';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kwitansi #<?= $data_pembayaran['id_pembayaran'] ?></title>
    <link rel="icon" href="/kost/assets/UI/Dashboards/assets/images/info-icon-03.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .receipt-container { max-width: 800px; margin: 2rem auto; background: #fff; padding: 2.5rem; border: 1px solid #dee2e6; }
        .receipt-header h1 { color: #343a40; font-weight: 700; }
        .signature-area { margin-top: 70px; }
        .print-button-container { position: fixed; bottom: 20px; right: 20px; z-index: 100; }
        
        @media print {
            body { background-color: #fff; }
            .receipt-container { margin: 0; padding: 0; max-width: 100%; border: none; box-shadow: none; }
            .print-button-container { display: none !important; }
        }
    </style>
</head>
<body>

<div id="receipt" class="receipt-container">
    <div class="receipt-header row align-items-center mb-4 pb-3 border-bottom">
        <div class="col-sm-6">
            <img src="/kost/assets/UI/Dashboards/assets/images/info-icon-03.png" alt="Logo Perusahaan" style="max-height: 60px;">
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <h1 class="mb-1">KWITANSI</h1>
            <p class="mb-0"><strong>No:</strong> #<?= $data_pembayaran['id_pembayaran'] ?></p>
        </div>
    </div>

    <div class="mb-4">
        <table class="table table-borderless table-sm">
            <tbody>
                <tr>
                    <td style="width: 200px;"><strong>Telah Diterima dari</strong></td>
                    <td>: <?= htmlspecialchars($data_pembayaran['nama_penyewa']) ?></td>
                </tr>
                <tr>
                    <td><strong>Jumlah Pembayaran</strong></td>
                    <td>: <strong>Rp <?= number_format($data_pembayaran['jumlah_bayar'], 0, ',', '.') ?></strong></td>
                </tr>
                <tr>
                    <td><strong>Untuk Pembayaran</strong></td>
                    <td>: Sewa Kost & Fasilitas Tambahan</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Bayar</strong></td>
                    <td>: <?= date('d F Y', strtotime($data_pembayaran['tanggal_bayar'])) ?></td>
                </tr>
                <tr>
                    <td><strong>Periode Sewa</strong></td>
                    <td>: <?= date('d M Y', strtotime($data_pembayaran['tanggal_mulai_kontrak'])) ?> s/d <?= date('d M Y', strtotime($data_pembayaran['tanggal_akhir_kontrak'])) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <h6>Rincian Pesanan:</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 5%;">#</th>
                    <th>Deskripsi</th>
                    <th class="text-center" style="width: 15%;">Jumlah</th>
                    <th class="text-end" style="width: 25%;">Harga Satuan</th>
                    <th class="text-end" style="width: 25%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $subtotal = 0;
                $has_printed_addons_header = false;

                foreach($items_pesanan as $item): 
                    $total_item = $item['harga_saat_pesan'] * $item['jumlah'];
                    $subtotal += $total_item;
                    
                    if ($item['tipe_item'] === 'kamar'):
                        $nama_item = "Sewa Kamar " . htmlspecialchars($item['kode_kamar']);
                ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><strong><?= $nama_item ?></strong></td>
                            <td class="text-center"><?= $item['jumlah'] ?></td>
                            <td class="text-end">Rp <?= number_format($item['harga_saat_pesan'], 0, ',', '.') ?></td>
                            <td class="text-end">Rp <?= number_format($total_item, 0, ',', '.') ?></td>
                        </tr>
                <?php 
                    else: // Jika item adalah fasilitas
                        if (!$has_printed_addons_header) {
                            echo '<tr><td colspan="5" class="pt-3 pb-1 text-muted"><strong><em>Add-ons:</em></strong></td></tr>';
                            $has_printed_addons_header = true;
                        }
                        $nama_item = htmlspecialchars($item['nama_fasilitas']);
                ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="ps-4"><?= $nama_item ?></td>
                            <td class="text-center"><?= $item['jumlah'] ?></td>
                            <td class="text-end">Rp <?= number_format($item['harga_saat_pesan'], 0, ',', '.') ?></td>
                            <td class="text-end">Rp <?= number_format($total_item, 0, ',', '.') ?></td>
                        </tr>
                <?php 
                    endif;
                endforeach; 
                ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold fs-5">
                    <td colspan="4" class="text-end">Total Pembayaran</td>
                    <td class="text-end">Rp <?= number_format($data_pembayaran['jumlah_bayar'], 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="row mt-4">
        <div class="col-6">
            </div>
        <div class="col-6 text-center">
            <p>Bandung, <?= date('d F Y', strtotime($data_pembayaran['tanggal_bayar'])) ?></p>
            <p>Diterima oleh,</p>
            <div class="signature-area"></div>
            <p class="mb-0"><strong>( <?= htmlspecialchars($nama_petugas) ?> )</strong></p>
            <p class="text-muted">Petugas</p>
        </div>
    </div>
</div>

<div class="print-button-container">
    <button onclick="window.print()" class="btn btn-primary btn-lg shadow">
        <i class="fas fa-print me-2"></i> Cetak Kwitansi
    </button>
</div>

</body>
</html>