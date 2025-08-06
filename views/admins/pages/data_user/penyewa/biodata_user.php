<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex align-items-center">

                        <h4 class="card-title"><?= $h1;?></h4>

                        <a href="?penyewa=data_penyewa" class="btn btn-round btn-primary btn-border ms-auto" style="margin: 0 0.5rem;">
                            <i class="fas fa-angle-double-left"></i> Kembali
                        </a>

                        <?php 
                            $id = $_GET['id_user'];

                            $stmt = $mysqli->prepare("SELECT COUNT(*) AS total FROM biodata WHERE id_user = ?");
                            $stmt->bind_param("i", $id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $cek = $result->fetch_assoc();
                            if ($cek['total'] == 0): ?>

                                <button class="btn btn-border btn-round btn-secondary " data-bs-toggle="modal" data-bs-target="#add">
                                    <i class="fas fa-plus"></i> Add <?= $p;?>
                                </button>

                        <?php endif?>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display table table-hover">

                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th style="width: 25%;">Alamat</th>
                                    <th>Foto</th>
                                    <th style="width: 15%;">Document</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $no = 0;
                                    $id = isset($_GET['id_user']) ? intval($_GET['id_user']) : 0;
                                    $bio = $mysqli->query("SELECT * FROM biodata JOIN user ON biodata.id_user = user.id_user WHERE biodata.id_user = $id");
                                    while ($data = $bio->fetch_assoc()) {
                                        $no++;
                                ?>

                                    <tr>
                                        <td align="center"><?= $no?></td>
                                        <td><?= $data['nama_user']?></td>
                                        <td><?= $data['jk']?></td>
                                        <td><?= $data['alamat']?></td>
                                        <td align="center">
                                            <div class="avatar avatar-xxl">
                                                <img src="/kost/assets/uploads/biodata/foto/<?= $data['foto'] ?>" alt="foto <?= $data['nama_user']?>" class="avatar-img rounded">
                                            </div>
                                        </td>

                                        <td align="center">
                                            <button type="button" class="btn btn-secondary btn-link btn-lg" data-bs-toggle="modal" data-bs-target="#doc-<?= $data['id_user']?>">
                                                <i class="fas fa-folder"></i>
                                            </button>
                                        </td>

                                        <td align="center">

                                            <button type="button" class="btn btn-primary btn-link btn-lg" data-bs-toggle="modal" data-bs-target="#edit-<?= $data['id_biodata']?>">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button class="btn btn-link btn-danger btn-lg" onclick="deleteBio(<?= $data['id_biodata']; ?>, <?= $data['id_user'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </td>
                                    </tr>

                                <?php }?>
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- modal add -->
<?php
    $stmt_user = $mysqli->prepare("SELECT nama_user FROM user WHERE id_user = ?");
    $stmt_user->bind_param("i", $id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $user_data = $result_user->fetch_assoc();
    $nama_user = $user_data['nama_user'] ?? '';
?>

<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Add</span>
                    <span class="fw-light">
                        <?= $p?>
                        <?= $nama_user?>
                    </span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="settings/functions/add/add_bio" method="post" enctype="multipart/form-data">
                    <div class="row">

                        <input type="hidden" readonly class="form-control" name="id_user" id="id_user" value="<?= $_GET['id_user'] ?>">

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="nama">
                                    Nama 
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="far fa-user"></i>
                                    </span>

                                    <input type="text" readonly class="form-control" value="<?= $nama_user?>">

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

<!-- modal edit -->
<?php
    $bio = $mysqli->query("SELECT * FROM biodata JOIN user ON biodata.id_user = user.id_user WHERE biodata.id_user = $id");
    while ($eb = mysqli_fetch_array($bio)) {
    ?>
    <div class="modal fade" id="edit-<?= $eb['id_biodata'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">Edit</span>
                        <span class="fw-light">
                            <?= $p?>
                            <?= $eb['nama_user']?>
                        </span>
                    </h5>
                </div>
                
                <div class="modal-body border-0">
                    <form action="settings/functions/edit/edit_bio" method="post" enctype="multipart/form-data">
                        <div class="row">

                            <input type="hidden" name="id_biodata" id="id_biodata" readonly class="form-control" value="<?= $eb['id_biodata'] ?>">
                            <input type="hidden" name="id_user" id="id_user" readonly class="form-control" value="<?= $eb['id_user'] ?>">

                            <div class="col-sm-12">
                                <div class="form-group">

                                    <label for="nama">Nama</label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="far fa-user"></i>
                                        </span>

                                        <input type="text" readonly class="form-control" value="<?= $eb['nama_user'] ?>">

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

                                        <input type="text" name="alamat" id="alamat" class="form-control" value="<?= $eb['alamat'] ?>" placeholder="Masukan alamat lengkap" required>

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
                                            <?= $eb['jk'] == 'Laki-Laki' ? 'selected' : '' ?>>
                                                Laki-Laki
                                            </option>
                                            <option 
                                            value="Perempuan" 
                                            <?= $eb['jk'] == 'Perempuan' ? 'selected' : '' ?>>
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

                                        <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Masukan Nomor hp" value="<?= $eb['no_hp']?>" required>

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
<?php }?>

<!-- modal doc -->
<?php
    $bio = $mysqli->query("SELECT * FROM biodata JOIN user ON biodata.id_user = user.id_user WHERE biodata.id_user = $id");
    while ($vd = mysqli_fetch_array($bio)) {
    ?>
    <div class="modal fade" id="doc-<?= $vd['id_user'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">Document</span>
                        <span class="fw-light"><?= $vd['nama_user']?></span>
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

                                <input type="text" class="form-control" value="<?= $vd['no_hp'] ?>" readonly>

                            </div>

                        </div>

                        <?php if (!empty($vd['scan_ktp'])) : ?>
                            <div class="col-md-6 pe-0">
                                <div class="form-group">

                                    <label for="ktp">
                                        KTP <?= $vd['nama_user']?>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="far fa-address-card"></i>
                                        </span>

                                        <form action="/kost/assets/uploads/biodata/ktp/<?= $vd['scan_ktp']; ?>" method="get"
                                        >
                                            <button type="submit" class="btn">
                                                KTP <?= $vd['nama_user']?>
                                            </button>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($vd['scan_kk'])) : ?>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="kk">
                                        KK <?= $vd['nama_user']?>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="far fa-address-card"></i>
                                        </span>

                                        <form action="/kost/assets/uploads/biodata/kk/<?= $vd['scan_kk']; ?>" method="get"
                                        >
                                            <button type="submit" class="btn">
                                                KK <?= $vd['nama_user']?>
                                            </button>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($vd['bukti_nikah'])) : ?>
                            <div class="col-sm-12">
                                <div class="form-group">

                                    <label for="ktp">
                                        Bukti Menikah <?= $vd['nama_user']?>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="fas fa-book"></i>
                                        </span>

                                        <form action="/kost/assets/uploads/biodata/bukti_nikah/<?= $vd['bukti_nikah']; ?>" method="get"
                                        >
                                            <button type="submit" class="btn">
                                                Bukti Menikah <?= $vd['nama_user']?>
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
<?php }?>

<script>
    function deleteBio(id_biodata, id_user) {
        Swal.fire({
            title: 'Hapus permanen?',
            text: "Anda yakin akan menghapus data ini selamanya?",
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
