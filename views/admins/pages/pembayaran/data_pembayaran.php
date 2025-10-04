<?php

    $query_cek_konfirmasi = $mysqli->query(
        "SELECT 
            id_pemesanan 
        FROM 
            pemesanan 
        WHERE 
            status_kontrak = 'Menunggu' LIMIT 1"
    );

    $perlu_konfirmasi = $query_cek_konfirmasi->num_rows > 0;

    $query_pembayaran = $mysqli->query(
        "SELECT
            py.id_pembayaran, py.tanggal_bayar, py.status, py.jumlah_bayar, py.status 
        AS 
            status_pembayaran, p.id_pemesanan, p.status_kontrak, p.tanggal_akhir_kontrak, u.nama_user
        FROM 
            pembayaran py
        JOIN 
            pemesanan p ON py.id_pemesanan = p.id_pemesanan
        JOIN 
            user u ON p.id_user = u.id_user
        WHERE 
            p.status_kontrak != 'Menunggu'
        ORDER BY 
            CASE p.status_kontrak
                WHEN 'Aktif' THEN 1
                WHEN 'Dibatalkan' THEN 2
                WHEN 'Selesai' THEN 3
                ELSE 4
            END,   
            py.tanggal_bayar DESC
    ");

    $semua_pembayaran = $query_pembayaran->fetch_all(MYSQLI_ASSOC);

    $detail_items = [];

    if (!empty($semua_pembayaran)) {

        $id_pemesanan_list = array_column($semua_pembayaran, 'id_pemesanan');

        if (!empty($id_pemesanan_list)) {

            $placeholders = implode(',', array_fill(0, count($id_pemesanan_list), '?'));

            $types = str_repeat('i', count($id_pemesanan_list));

            $stmt_items = $mysqli->prepare(
                "SELECT 
                    dp.id_pemesanan, dp.tipe_item, dp.harga_saat_pesan, k.kode_kamar, k.foto 
                AS 
                    foto_kamar, f.nama_fasilitas, f.foto 
                AS 
                    foto_fasilitas
                FROM 
                    detail_pemesanan dp
                LEFT JOIN 
                    kamar k ON dp.id_item = k.id_kamar AND dp.tipe_item = 'kamar'
                LEFT JOIN 
                    fasilitas f ON dp.id_item = f.id_fasilitas AND dp.tipe_item = 'fasilitas'
                WHERE 
                    dp.id_pemesanan IN ($placeholders)
                ORDER BY 
                    dp.id_pemesanan, FIELD(dp.tipe_item, 'kamar', 'fasilitas')
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex align-items-center">

                        <h2 class="card-title">
                            <?= htmlspecialchars($h1) ?>
                        </h2>

                        <div class="ms-auto">
                            <a href="pages/pembayaran/download/download_pdf" class="btn btn-round btn-primary btn-border">
                                <i class="fas fa-file-pdf"></i> download PDF
                            </a>

                            <a href="pages/pembayaran/download/download_csv" class="btn btn-round btn-primary btn-border ms-2">
                                <i class="fas fa-file-excel"></i> download CSV
                            </a>

                            <?php if ($perlu_konfirmasi): ?>
                                <a href="?pembayaran=konfirmasi_pembayaran" class="btn btn-round btn-primary btn-border ms-2">
                                    <i class="fas fa-calendar-check"></i> Pembayaran
                                    <span class="badge bg-danger">Baru!</span> 
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <table id="tabelPembayaran" class="table table-hover table-striped display">

                        <thead>
                            <tr align="center">
                                <th style="width: 5%;">No</th>
                                <th>Nama Penyewa</th>
                                <th>Tanggal Bayar</th>
                                <th>Tanggal Selesai</th>
                                <th>Jumlah Bayar</th>
                                <th>Status</th>
                                <th>Ket</th>
                                <th data-orderable="false" data-searchable="false" style="width: 15%;">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (count($semua_pembayaran) > 0): ?>
                                <?php 
                                    $no = 0;
                                    foreach($semua_pembayaran as $pembayaran): 
                                    $no++;
                                    ?>
                                    <tr class="text-center">
                                        <td><?= $no ?></td>
                                        <td>
                                            <?= htmlspecialchars($pembayaran['nama_user']) ?>
                                        </td>
                                        <td>
                                            <?= date('d M Y', strtotime($pembayaran['tanggal_bayar'])) ?>
                                        </td>
                                        <td>
                                            <?= date('d M Y', strtotime($pembayaran['tanggal_akhir_kontrak'])) ?>
                                        </td>
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
                                            <?php 
                                                $status = htmlspecialchars($pembayaran['status']);
                                                $badge_class = 'bg-secondary';
                                                if ($status == 'Lunas') $badge_class = 'bg-success';
                                                if ($status == 'Belum Lunas') $badge_class = 'bg-danger';
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
                                <tr><td colspan="6" class="text-center p-4">Belum ada data pembayaran (selain yang berstatus Menunggu).</td></tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>

            </div>
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
                                        <img src="<?= $path_foto . htmlspecialchars($foto) ?>" class="me-3" alt="<?= htmlspecialchars($nama) ?>"  style="width: 125px; height: auto; object-fit: cover; border-radius: 0.25rem;">
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

<script>
    // PERUBAHAN: Tambahkan skrip ini di bagian paling bawah
    document.getElementById('download-pdf').addEventListener('click', function () {
        // Ambil elemen tersembunyi yang akan kita ubah jadi PDF
        const element = document.getElementById('pdf-content-hidden');
        
        // Opsi untuk file PDF
        const opt = {
            margin:       1,
            filename:     'Laporan_Pembayaran_TheKost_<?= date('Y-m-d') ?>.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'cm', format: 'a4', orientation: 'landscape' }
        };

        // Gunakan html2pdf untuk membuat dan langsung men-download PDF
        html2pdf().from(element).set(opt).save();
    });
</script>