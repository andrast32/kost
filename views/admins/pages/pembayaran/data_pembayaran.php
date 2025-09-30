<?php
// Diasumsikan koneksi dan sesi sudah ada dari router utama
$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);

// 1. Query Utama: Ambil data pembayaran beserta info penyewa
$query_pembayaran = $mysqli->query("
    SELECT
        py.id_pembayaran, py.tanggal_bayar, py.jumlah_bayar,
        p.id_pemesanan, p.status_kontrak,
        u.nama_user
    FROM pembayaran py
    JOIN pemesanan p ON py.id_pemesanan = p.id_pemesanan
    JOIN user u ON p.id_user = u.id_user
    ORDER BY py.tanggal_bayar DESC
");
$semua_pembayaran = $query_pembayaran->fetch_all(MYSQLI_ASSOC);

// 2. Siapkan data detail item untuk setiap pemesanan di awal
$detail_items = [];
if (!empty($semua_pembayaran)) {
    $id_pemesanan_list = array_column($semua_pembayaran, 'id_pemesanan');
    if (!empty($id_pemesanan_list)) {
        $placeholders = implode(',', array_fill(0, count($id_pemesanan_list), '?'));
        $types = str_repeat('i', count($id_pemesanan_list));

        $stmt_items = $mysqli->prepare("
            SELECT 
                dp.id_pemesanan, dp.tipe_item, dp.harga_saat_pesan,
                k.kode_kamar, k.foto AS foto_kamar,
                f.nama_fasilitas, f.foto AS foto_fasilitas
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

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Riwayat Pembayaran</h2>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-center">
                        <th>ID Bayar</th>
                        <th>Nama Penyewa</th>
                        <th>Tanggal Bayar</th>
                        <th>Jumlah Bayar</th>
                        <th>Status Kontrak</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($semua_pembayaran) > 0): ?>
                        <?php foreach($semua_pembayaran as $pembayaran): ?>
                            <tr class="text-center">
                                <td>#<?= $pembayaran['id_pembayaran'] ?></td>
                                <td><?= htmlspecialchars($pembayaran['nama_user']) ?></td>
                                <td><?= date('d M Y', strtotime($pembayaran['tanggal_bayar'])) ?></td>
                                <td class="fw-bold">Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></td>
                                <td>
                                    <?php 
                                        $status = htmlspecialchars($pembayaran['status_kontrak']);
                                        $badge_class = 'bg-secondary';
                                        if ($status == 'Aktif') $badge_class = 'bg-success';
                                        if ($status == 'Pending') $badge_class = 'bg-warning';
                                        if ($status == 'Selesai' || $status == 'Dibatalkan') $badge_class = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge_class ?>"><?= $status ?></span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $pembayaran['id_pemesanan'] ?>">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                    </button>
                                    <a href="cetak_kwitansi.php?id=<?= $pembayaran['id_pembayaran'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center p-4">Belum ada data pembayaran.</td></tr>
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
                                    $indent_class = $is_kamar ? '' : 'ms-4'; // Tambah indentasi jika fasilitas
                            ?>
                                    <?php if ($is_kamar && $has_fasilitas && $index > 0): ?>
                                        <li class="list-group-item py-1"><hr class="my-1"></li>
                                    <?php elseif (!$is_kamar && $index > 0 && $items_in_order[$index-1]['tipe_item'] === 'kamar'): ?>
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

<style>
    .item-detail-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 0.25rem;
    }
</style>