<?php

// Mengambil data untuk mengisi pilihan di form
$users = $mysqli->query("SELECT id_user, nama_user FROM user WHERE role = 'User' AND deleted != 1 ORDER BY nama_user ASC");
$kamar_kosong = $mysqli->query("SELECT id_kamar, kode_kamar, harga FROM kamar WHERE status = 'Kosong' ORDER BY kode_kamar ASC");
$fasilitas_tambahan = $mysqli->query("SELECT id_fasilitas, nama_fasilitas, harga, stok FROM fasilitas WHERE stok > 0 AND deleted != 1 ORDER BY nama_fasilitas ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Pemesanan Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3><i class="fas fa-plus-circle me-2"></i>Buat Pemesanan Baru</h3>
        </div>
        <div class="card-body p-4">

            <form action="settings/functions/add/add_pemesanan.php" method="POST">

                <div class="mb-3">
                    <label for="id_user" class="form-label fw-bold">Pilih Penyewa</label>
                    <select class="form-select" id="id_user" name="id_user" required>
                        <option value="" disabled selected>-- Pilih salah satu --</option>
                        <?php while($user = $users->fetch_assoc()): ?>
                            <option value="<?= $user['id_user'] ?>"><?= htmlspecialchars($user['nama_user']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_kamar" class="form-label fw-bold">Pilih Kamar (Hanya kamar kosong yang tampil)</label>
                    <select class="form-select" id="id_kamar" name="id_kamar" required>
                        <option value="" disabled selected>-- Pilih salah satu --</option>
                        <?php while($kamar = $kamar_kosong->fetch_assoc()): ?>
                            <option value="<?= $kamar['id_kamar'] ?>">
                                <?= htmlspecialchars($kamar['kode_kamar']) ?> - (Rp <?= number_format($kamar['harga']) ?>/bulan)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tanggal_masuk" class="form-label fw-bold">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_keluar" class="form-label fw-bold">Tanggal Keluar</label>
                        <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Pilih Fasilitas Tambahan (Opsional)</label>
                    <div class="border p-3 rounded bg-light">
                        <?php if ($fasilitas_tambahan->num_rows > 0): ?>
                            <?php while($fasilitas = $fasilitas_tambahan->fetch_assoc()): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fasilitas[]" value="<?= $fasilitas['id_fasilitas'] ?>" id="fasilitas_<?= $fasilitas['id_fasilitas'] ?>">
                                <label class="form-check-label" for="fasilitas_<?= $fasilitas['id_fasilitas'] ?>">
                                    <?= htmlspecialchars($fasilitas['nama_fasilitas']) ?> (+ Rp <?= number_format($fasilitas['harga']) ?>) - Stok: <?= $fasilitas['stok'] ?>
                                </label>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">Tidak ada fasilitas tambahan yang tersedia saat ini.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fw-bold"><i class="fas fa-check-circle me-2"></i>Submit Pesanan</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Script untuk menampilkan notifikasi dari session
    <?php if ($alert): ?>
    Swal.fire({
        icon: '<?= $alert['icon'] ?>',
        title: '<?= $alert['title'] ?>',
        text: '<?= $alert['text'] ?>',
    });
    <?php endif; ?>
</script>

</body>
</html>