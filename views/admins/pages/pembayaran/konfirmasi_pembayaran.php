<?php

    $query_pending = "
        SELECT 
            p.id_pemesanan, 
            p.total, 
            u.nama_user, 
            k.kode_kamar
        FROM 
            pemesanan p
        JOIN 
            user u ON p.id_user = u.id_user
        LEFT JOIN 
            detail_pemesanan dp ON p.id_pemesanan = dp.id_pemesanan AND dp.tipe_item = 'kamar'
        LEFT JOIN 
            kamar k ON dp.id_item = k.id_kamar
        WHERE 
            p.status_kontrak = 'Pending'
        ORDER BY 
            p.tanggal_pesan ASC
    ";
    $data_pending = $mysqli->query($query_pending);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-warning">
            <h3><i class="fas fa-money-check-alt me-2"></i>Daftar Pembayaran Pending</h3>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-center">
                        <th>No. Pesanan</th>
                        <th>Nama Penyewa</th>
                        <th>Kamar</th>
                        <th>Total Tagihan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data_pending && $data_pending->num_rows > 0): ?>
                        <?php while($pesanan = $data_pending->fetch_assoc()): ?>
                            <tr class="text-center">
                                <td><?= $pesanan['id_pemesanan'] ?></td>
                                <td><?= htmlspecialchars($pesanan['nama_user']) ?></td>
                                <td><?= htmlspecialchars($pesanan['kode_kamar'] ?? 'N/A') ?></td>
                                <td>Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></td>
                                <td>
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#bayarModal" data-id="<?= $pesanan['id_pemesanan'] ?>" data-total="<?= $pesanan['total'] ?>">
                                        <i class="fas fa-check me-1"></i> Proses Bayar
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">Tidak ada pembayaran yang pending saat ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="bayarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bayarModalLabel">Proses Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="settings/functions/add/add_pembayaran" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_pemesanan" id="modal_id_pemesanan">
                    <div class="mb-3">
                        <label for="modal_jumlah_bayar" class="form-label">Jumlah Bayar</label>
                        <input type="number" class="form-control" id="modal_jumlah_bayar" name="jumlah_bayar" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal_tanggal_bayar" class="form-label">Tanggal Bayar</label>
                        <input type="date" class="form-control" id="modal_tanggal_bayar" name="tanggal_bayar" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal_bukti_transaksi" class="form-label">Bukti Transaksi (Opsional)</label>
                        <input type="file" class="form-control" id="modal_bukti_transaksi" name="bukti_transaksi" accept="image/*,.pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bayarModal = document.getElementById('bayarModal');
        if (bayarModal) {
            bayarModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id_pemesanan = button.getAttribute('data-id');
                const total = button.getAttribute('data-total');
                const modalIdInput = bayarModal.querySelector('#modal_id_pemesanan');
                const modalJumlahInput = bayarModal.querySelector('#modal_jumlah_bayar');
                modalIdInput.value = id_pemesanan;
                modalJumlahInput.value = total;
            });
        }

        <?php if ($alert): ?>
        Swal.fire({
            icon: '<?= $alert['icon'] ?>',
            title: '<?= $alert['title'] ?>',
            text: '<?= $alert['text'] ?>',
        });
        <?php endif; ?>
    });
</script>

</body>
</html>