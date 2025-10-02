<?php
// Diasumsikan koneksi dan sesi sudah ada dari file router utama Anda
// Jika file ini diakses langsung, Anda perlu menambahkan:
// require 'settings/koneksi.php'; 
// session_start();

$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);

// 1. Cek apakah ada pembayaran yang perlu dikonfirmasi
$query_cek_konfirmasi = $mysqli->query("SELECT id_pemesanan FROM pemesanan WHERE status_kontrak = 'Pending' LIMIT 1");
$perlu_konfirmasi = $query_cek_konfirmasi->num_rows > 0;

// 2. Query Utama: Ambil data pembayaran (Urutan utama akan diatur oleh DataTables)
$query_pembayaran = $mysqli->query("
    SELECT
        py.id_pembayaran, py.tanggal_bayar, py.jumlah_bayar, py.status AS status_pembayaran,
        p.id_pemesanan, p.status_kontrak,
        u.nama_user
    FROM pembayaran py
    JOIN pemesanan p ON py.id_pemesanan = p.id_pemesanan
    JOIN user u ON p.id_user = u.id_user
    WHERE p.status_kontrak != 'Pending'
    ORDER BY py.tanggal_bayar DESC
");
$semua_pembayaran = $query_pembayaran->fetch_all(MYSQLI_ASSOC);

// 3. Siapkan data detail item untuk setiap pemesanan (untuk modal)
$detail_items = [];
if (!empty($semua_pembayaran)) {
    $id_pemesanan_list = array_column($semua_pembayaran, 'id_pemesanan');
    if (!empty($id_pemesanan_list)) {
        $placeholders = implode(',', array_fill(0, count($id_pemesanan_list), '?'));
        $types = str_repeat('i', count($id_pemesanan_list));
        $stmt_items = $mysqli->prepare("
            SELECT dp.id_pemesanan, dp.tipe_item, dp.harga_saat_pesan, k.kode_kamar, k.foto AS foto_kamar, f.nama_fasilitas, f.foto AS foto_fasilitas
            FROM detail_pemesanan dp
            LEFT JOIN kamar k ON dp.id_item = k.id_kamar AND dp.tipe_item = 'kamar'
            LEFT JOIN fasilitas f ON dp.id_item = f.id_fasilitas AND dp.tipe_item = 'fasilitas'
            WHERE dp.id_pemesanan IN ($placeholders)
            ORDER BY dp.id_pemesanan, FIELD(dp.tipe_item, 'kamar', 'fasilitas')
        ");
        $stmt_items->bind_param($types, ...$id_pemesanan_list);
        $stmt_items->execute();
        $result_items = $stmt_items->get_result();
        while ($item = $result_items->fetch_assoc()) {
            $detail_items[$item['id_pemesanan']][] = $item;
        }
        $stmt_items->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    
    <style>
        .item-detail-img { width: 50px; height: 50px; object-fit: cover; border-radius: 0.25rem; }
        .dt-buttons .btn { margin-left: 0.5rem; }
    </style>
</head>
<body>

<div class="container-fluid my-4">
    <div class="card"> 
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h4 class="card-title"><?= htmlspecialchars($h1 ?? 'Riwayat Pembayaran') ?></h4>
                <?php if ($perlu_konfirmasi): ?>
                <a href="?pembayaran=konfirmasi_pembayaran" class="btn btn-round btn-info btn-border ms-auto">
                    <i class="fas fa-calendar-check"></i> Konfirmasi Pembayaran
                    <span class="badge bg-danger ms-2">Baru!</span> 
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <table id="tabelPembayaran" class="table table-hover align-middle" style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th>ID Bayar</th>
                        <th>Nama Penyewa</th>
                        <th>Tanggal Bayar</th>
                        <th>Jumlah Bayar</th>
                        <th>Status Kontrak</th>
                        <th data-orderable="false" data-searchable="false" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($semua_pembayaran) > 0): ?>
                        <?php foreach($semua_pembayaran as $pembayaran): ?>
                            <tr class="text-center">
                                <td>#<?= $pembayaran['id_pembayaran'] ?></td>
                                <td><?= htmlspecialchars($pembayaran['nama_user']) ?></td>
                                <td><?= date('d M Y', strtotime($pembayaran['tanggal_bayar'])) ?></td>
                                <td class="fw-bold" data-sort="<?= $pembayaran['jumlah_bayar'] ?>">Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></td>
                                <td>
                                    <?php 
                                        $status = htmlspecialchars($pembayaran['status_kontrak']);
                                        $badge_class = 'bg-secondary';
                                        if ($status == 'Aktif') $badge_class = 'bg-success';
                                        if ($status == 'Selesai' || $status == 'Dibatalkan') $badge_class = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge_class ?>"><?= $status ?></span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $pembayaran['id_pemesanan'] ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="pages/pembayaran/cetak_kwitansi?id=<?= $pembayaran['id_pembayaran'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center p-4">Belum ada data pembayaran (selain yang berstatus Pending).</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (count($semua_pembayaran) > 0): ?>
    <?php foreach($semua_pembayaran as $pembayaran): ?>
        <div class="modal fade" id="detailModal-<?= $pembayaran['id_pemesanan'] ?>" tabindex="-1" aria-labelledby="detailModalLabel-<?= $pembayaran['id_pemesanan'] ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel-<?= $pembayaran['id_pemesanan'] ?>">Detail Item Pesanan #<?= $pembayaran['id_pemesanan'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group list-group-flush">
                            <?php 
                            $items_in_order = $detail_items[$pembayaran['id_pemesanan']] ?? [];
                            if (!empty($items_in_order)):
                                $has_fasilitas = count(array_filter($items_in_order, fn($item) => $item['tipe_item'] === 'fasilitas')) > 0;
                                foreach ($items_in_order as $index => $item):
                                    $is_kamar = $item['tipe_item'] === 'kamar';
                                    $foto = $is_kamar ? $item['foto_kamar'] : $item['foto_fasilitas'];
                                    $nama = $is_kamar ? $item['kode_kamar'] : $item['nama_fasilitas'];
                                    $path_foto = $is_kamar ? '/kost/assets/uploads/kamar/' : '/kost/assets/uploads/fasilitas/';
                                    $indent_class = $is_kamar ? '' : 'ms-4';
                            ?>
                                    <?php if (!$is_kamar && $index > 0 && $items_in_order[$index-1]['tipe_item'] === 'kamar'): ?>
                                        <li class="list-group-item py-1"><hr class="my-1"></li>
                                    <?php endif; ?>
                                    <li class="list-group-item d-flex align-items-center <?= $indent_class ?>">
                                        <img src="<?= $path_foto . htmlspecialchars($foto) ?>" class="item-detail-img me-3" alt="<?= htmlspecialchars($nama) ?>">
                                        <div class="flex-grow-1">
                                            <strong><?= htmlspecialchars($nama) ?></strong>
                                        </div>
                                        <span class="badge bg-light text-dark">Rp <?= number_format($item['harga_saat_pesan'], 0, ',', '.') ?></span>
                                    </li>
                            <?php 
                                endforeach;
                            else:
                                echo '<li class="list-group-item text-muted">Tidak ada detail item untuk pesanan ini.</li>';
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabelPembayaran').DataTable({
        // PERBAIKAN: Menetapkan urutan default berdasarkan kolom Status Kontrak
        "order": [[ 4, "asc" ]], // Kolom ke-4 (Status Kontrak) diurutkan Ascending (A-Z)

        dom:  "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-end'f>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
              "<'row'<'col-sm-12 mt-3'B>>",
        
        buttons: [
            { 
                extend: 'copy', 
                className: 'btn-sm btn-outline-secondary', 
                text: '<i class="fas fa-copy"></i> Copy',
                exportOptions: { columns: ':visible:not(:last-child)' }
            },
            { 
                extend: 'csv', 
                className: 'btn-sm btn-outline-success', 
                text: '<i class="fas fa-file-csv"></i> CSV',
                exportOptions: { columns: ':visible:not(:last-child)' }
            },
            { 
                extend: 'pdf', 
                className: 'btn-sm btn-outline-danger', 
                text: '<i class="fas fa-file-pdf"></i> PDF',
                exportOptions: { columns: ':visible:not(:last-child)' }
            },
            { 
                extend: 'print', 
                className: 'btn-sm btn-outline-dark', 
                text: '<i class="fas fa-print"></i> Print',
                exportOptions: { columns: ':visible:not(:last-child)' }
            }
        ]
    });
});
</script>

</body>
</html>