<?php

    $sl_user = $_GET['sl_user'] ?? '';
    if (empty($sl_user)) {
        die("Akses tidak valid. User tidak diizinkan!");
    }

    $stmt = $mysqli->prepare("
        SELECT
            u.id_user, u.nama_user, u.sl_user,
            b.id_biodata, b.jk, b.alamat, b.no_hp, 
            b.foto, b.scan_ktp, b.scan_kk, b.bukti_nikah
        FROM user u
        LEFT JOIN biodata b ON u.id_user = b.id_user
        WHERE u.sl_user = ?
        LIMIT 1
    ");
    $stmt->bind_param("s", $sl_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if (!$data) {
        die("User dengan kode tersebut tidak ditemukan.");
    }

    $has_biodata = !is_null($data['id_biodata']);

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex align-items-center">

                        <h4 class="card-title">
                            <?= htmlspecialchars($h1 ?? 'Data Biodata'); ?>
                        </h4>

                        <a href="?penyewa=data_penyewa" class="btn btn-round btn-primary btn-border ms-auto" style="margin: 0 0.5rem;">
                            <i class="fas fa-angle-double-left"></i> Kembali
                        </a>

                        <?php if (!$has_biodata): ?>

                                <button class="btn btn-border btn-round btn-secondary " data-bs-toggle="modal" data-bs-target="#add">
                                    <i class="fas fa-plus"></i> Tambah <?= htmlentities($p); ?>
                                </button>

                        <?php endif?>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display table table-hover">

                            <thead>
                                <tr align="center">
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th style="width: 25%;">Alamat</th>
                                    <th>Foto</th>
                                    <th style="width: 15%;">Document</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if ($has_biodata) :?>

                                    <tr align="center">
                                        <td><?= htmlspecialchars($data['nama_user'])?></td>
                                        <td><?= htmlspecialchars($data['jk'])?></td>
                                        <td><?= htmlspecialchars($data['alamat'])?></td>
                                        <td align="center">
                                            <div class="avatar avatar-xxl">
                                                <img src="/kost/assets/uploads/biodata/foto/<?= $data['foto'] ?>" alt="foto <?= $data['nama_user']?>" class="avatar-img rounded">
                                            </div>
                                        </td>

                                        <td align="center">
                                            <button type="button" class="btn btn-secondary btn-link btn-lg" data-bs-toggle="modal" data-bs-target="#doc">
                                                <i class="fas fa-folder"></i>
                                            </button>
                                        </td>

                                        <td align="center">

                                            <button type="button" class="btn btn-primary btn-link btn-lg" data-bs-toggle="modal" data-bs-target="#edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button class="btn btn-link btn-danger btn-lg" onclick="deleteBio(<?= $data['id_biodata']; ?>, <?= $data['id_user'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </td>
                                    </tr>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            Biodata untuk <strong><?= htmlspecialchars($data['nama_user']); ?></strong> belum ditambahkan.
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- modal add -->

<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Tambah biodata untuk 
                    <strong><?= htmlspecialchars($data['nama_user']); ?></strong>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="settings/functions/add/add_bio" method="post" enctype="multipart/form-data">
                    <div class="row">

                        <input type="hidden" readonly class="form-control" name="id_user" id="id_user" value="<?= htmlspecialchars($data['id_user']) ?>">

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="nama">
                                    Nama 
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="far fa-user"></i>
                                    </span>

                                    <input type="text" readonly class="form-control" value="<?= htmlspecialchars($data['nama_user']) ?>">

                                </div>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="alamat">
                                    Alamat 
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>

                                    <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukan alamat lengkap" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6 pe-0">
                            <div class="form-group">

                                <label for="jk">
                                    Jenis Kelamin 
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-venus-mars"></i>
                                    </span>

                                    <select name="jk" id="jk" class="form-control">
                                        <option value="" disabled selected>Pilih jenis kelamin</option>
                                        <option value="Laki-Laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label for="no_telp">
                                    Nomor Telpon 
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>

                                    <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Masukan Nomor hp" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6 pe-0">
                            <div class="form-group">

                                <label for="foto">
                                    Foto 
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-camera"></i>
                                    </span>

                                    <input type="file" name="foto" id="foto" class="form-control" required accept=".jpg, .png, .jpeg">

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label for="kk">
                                    Scan KK
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="far fa-address-card"></i>
                                    </span>

                                    <input type="file" name="scan_kk" id="scan_kk" class="form-control" accept=".pdf">

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6 pe-0">
                            <div class="form-group">

                                <label for="ktp">
                                    Scan KTP 
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="far fa-address-card"></i>
                                    </span>

                                    <input type="file" name="scan_ktp" id="scan_ktp" class="form-control" accept=".pdf">

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label for="buku nikah">
                                    Bukti Nikah
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-book"></i>
                                    </span>

                                    <input type="file" name="bukti_nikah" id="bukti_nikah" class="form-control" accept=".pdf">

                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <input type="reset" value="Reset" class="btn btn-border btn-round btn-primary float-right">
                            <input type="submit" value="Submit" class="btn btn-border btn-round btn-success float-right">
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php if ($has_biodata) : ?>

    <!-- modal edit -->
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">Rubah</span>
                        <span class="fw-light">
                            <?= $p?>
                            <?= htmlspecialchars($data['nama_user'])?>
                        </span>
                    </h5>
                </div>
                
                <div class="modal-body border-0">
                    <form action="settings/functions/edit/edit_bio" method="post" enctype="multipart/form-data">
                        <div class="row">

                            <input type="hidden" name="id_biodata" id="id_biodata" readonly class="form-control" value="<?= htmlspecialchars($data['id_biodata']) ?>">
                            <input type="hidden" name="id_user" id="id_user" readonly class="form-control" value="<?= htmlspecialchars($data['id_user']) ?>">

                            <div class="col-sm-12">
                                <div class="form-group">

                                    <label for="nama">Nama</label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="far fa-user"></i>
                                        </span>

                                        <input type="text" readonly class="form-control" value="<?= $data['nama_user'] ?>">

                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">

                                    <label for="alamat">
                                        Alamat
                                        <span class="text-dange">*</span>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>

                                        <input type="text" name="alamat" id="alamat" class="form-control" value="<?= $data['alamat'] ?>" placeholder="Masukan alamat lengkap" required>

                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6 pe-0">
                                <div class="form-group">

                                    <label for="jk">
                                        Jenis Kelamin 
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="fas fa-venus-mars"></i>
                                        </span>

                                        <select name="jk" id="jk" class="form-control">
                                            <option value="" disabled selected>Pilih jenis kelamin</option>
                                            <option 
                                            value="Laki-Laki" 
                                            <?= $data['jk'] == 'Laki-Laki' ? 'selected' : '' ?>>
                                                Laki-Laki
                                            </option>
                                            <option 
                                            value="Perempuan" 
                                            <?= $data['jk'] == 'Perempuan' ? 'selected' : '' ?>>
                                                Perempuan
                                            </option>
                                        </select>

                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="no_telp">
                                        Nomor Telpon 
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>

                                        <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Masukan Nomor hp" value="<?= $data['no_hp']?>" required>

                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6 pe-0">
                                <div class="form-group">

                                    <label for="foto">
                                        Foto 
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="fas fa-camera"></i>
                                        </span>

                                        <input type="file" name="foto" id="foto" class="form-control" accept=".jpg, .png, .jpeg">

                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="kk">
                                        Scan KK
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="far fa-address-card"></i>
                                        </span>

                                        <input type="file" name="scan_kk" id="scan_kk" class="form-control" accept=".pdf">

                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6 pe-0">
                                <div class="form-group">

                                    <label for="ktp">
                                        Scan KTP 
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="far fa-address-card"></i>
                                        </span>

                                        <input type="file" name="scan_ktp" id="scan_ktp" class="form-control" accept=".pdf">

                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="buku nikah">
                                        Bukti Nikah
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="fas fa-book"></i>
                                        </span>

                                        <input type="file" name="bukti_nikah" id="bukti_nikah" class="form-control" accept=".pdf">

                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">
                                <input type="reset" value="Reset" class="btn btn-border btn-round btn-primary float-right">
                                <input type="submit" value="Submit" class="btn btn-border btn-round btn-success float-right">
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- modal doc -->
    <div class="modal fade" id="doc" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">Document</span>
                        <span class="fw-light"><?= htmlspecialchars($data['nama_user']) ?></span>
                    </h5>
                </div>

                <div class="modal-body border-0">
                    <div class="row">

                        <div class="col-sm-12">

                            <label for="No hp">No telpon</label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>

                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['no_hp']) ?>" readonly>

                            </div>

                        </div>

                        <?php if (!empty($data['scan_ktp'])) : ?>
                            <div class="col-md-6 pe-0">
                                <div class="form-group">

                                    <label for="ktp">
                                        KTP <?= htmlspecialchars($data['nama_user']) ?>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="far fa-address-card"></i>
                                        </span>

                                        <form action="/kost/assets/uploads/biodata/ktp/<?= htmlspecialchars($data['scan_ktp']) ?>" target="_blank" method="get"
                                        >
                                            <button type="submit" class="btn">
                                                Scan KTP
                                            </button>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($data['scan_kk'])) : ?>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="kk">
                                        KK <?= htmlspecialchars($data['nama_user']) ?>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="far fa-address-card"></i>
                                        </span>

                                        <form action="/kost/assets/uploads/biodata/kk/<?= htmlspecialchars($data['scan_kk']) ?>" target="_blank" method="get"
                                        >
                                            <button type="submit" class="btn">
                                                Scan KK
                                            </button>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($data['bukti_nikah'])) : ?>
                            <div class="col-sm-12">
                                <div class="form-group">

                                    <label for="ktp">
                                        Bukti Menikah <?= $data['nama_user']?>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="fas fa-book"></i>
                                        </span>

                                        <form action="/kost/assets/uploads/biodata/bukti_nikah/<?= htmlspecialchars($data['bukti_nikah']); ?>" target="_blank" method="get"
                                        >
                                            <button type="submit" class="btn">
                                                Document bukti pernikahan
                                            </button>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>

<?php endif; ?>

<script>
    function deleteBio(id_biodata, id_user) {
        Swal.fire({
            title: 'Hapus permanen?',
            text: "Anda yakin akan menghapus biodata <?= htmlspecialchars($data['nama_user']) ?>?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e74c3c'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "settings/functions/delete/permanent/del_bio?id_biodata=" + id_biodata + "&id_user=" + id_user;
            }
        });
    }
</script>
