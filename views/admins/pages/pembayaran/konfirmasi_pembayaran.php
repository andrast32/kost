<?php
// Diasumsikan koneksi dan sesi sudah ada dari file router Anda
$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);

// Mengambil semua PEMESANAN yang statusnya 'Pending' untuk dikonfirmasi
$query_pending = $mysqli->query("
    SELECT 
        p.id_pemesanan, p.total, u.nama_user, p.tanggal_pesan,
        k.kode_kamar
    FROM pemesanan p
    JOIN user u ON p.id_user = u.id_user
    LEFT JOIN detail_pemesanan dp ON p.id_pemesanan = dp.id_pemesanan AND dp.tipe_item = 'kamar'
    LEFT JOIN kamar k ON dp.id_item = k.id_kamar
    WHERE p.status_kontrak = 'Menunggu'
    ORDER BY p.tanggal_pesan ASC
");
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-calendar-check me-2"></i>Konfirmasi Pemesanan Tertunda</h2>
        </div>
        <div class="card-body">
            
            <?php if ($query_pending && $query_pending->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-center">
                                <th>ID Pesanan</th>
                                <th>Nama Penyewa</th>
                                <th>Kamar</th>
                                <th>Tanggal Pesan</th>
                                <th>Total Tagihan</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($pesanan = $query_pending->fetch_assoc()): ?>
                                <tr class="text-center">
                                    <td>#<?= $pesanan['id_pemesanan'] ?></td>
                                    <td><?= htmlspecialchars($pesanan['nama_user']) ?></td>
                                    <td><?= htmlspecialchars($pesanan['kode_kamar'] ?? 'N/A') ?></td>
                                    <td><?= date('d M Y', strtotime($pesanan['tanggal_pesan'])) ?></td>
                                    <td class="fw-bold">Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#bayarModal" data-id="<?= $pesanan['id_pemesanan'] ?>" data-total="<?= $pesanan['total'] ?>">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                        <form action="settings/functions/proses_konfirmasi.php" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menolak dan membatalkan pesanan ini?');">
                                            <input type="hidden" name="id_pemesanan" value="<?= $pesanan['id_pemesanan'] ?>">
                                            <button type="submit" name="action" value="tolak" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center p-5">
                    <h5 class="mt-3">Semua Pemesanan Sudah Terkonfirmasi!</h5>
                    <p class="text-muted">Tidak ada pemesanan yang menunggu untuk dikonfirmasi saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="bayarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bayarModalLabel">Konfirmasi & Catat Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="settings/functions/add/add_pembayaran" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_pemesanan" id="modal_id_pemesanan">
                    <input type="hidden" name="action" value="terima">
                    <div class="mb-3">
                        <label for="modal_jumlah_bayar" class="form-label">Jumlah Bayar (Otomatis)</label>
                        <input type="number" class="form-control" id="modal_jumlah_bayar" name="jumlah_bayar" readonly>
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
                    <button type="submit" class="btn btn-primary">Konfirmasi & Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bayarModal = document.getElementById('bayarModal');
        if (bayarModal) {
            bayarModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id_pemesanan = button.getAttribute('data-id');
                const total = button.getAttribute('data-total');
                bayarModal.querySelector('#modal_id_pemesanan').value = id_pemesanan;
                bayarModal.querySelector('#modal_jumlah_bayar').value = total;
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