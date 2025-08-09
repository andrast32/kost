<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex align-items-center">

                        <h4 class="card-title">
                            <?= $h1 ?>
                        </h4>

                        <button class="btn btn-border btn-round btn-primary ms-auto" style="margin-right: 0.5rem;" data-bs-toggle="modal" data-bs-target="#add">
                                <i class="fas fa-plus"></i> Add <?= $p?>
                        </button>

                        <a href="?penyewa=deleted_penyewa" class="btn btn-round btn-danger btn-border">
                            <i class="fas fa-trash"></i> Lihat sampah
                        </a>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="laporan" class="display table table-striped table-hover">

                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $user = $mysqli->query("SELECT * FROM user WHERE deleted != 1 AND role = 'User' ORDER BY id_user ASC");

                                    $no = 0;
                                    while ($data = mysqli_fetch_array($user)) {
                                        $no++;
                                ?>
                                    <tr>
                                        <td align="center"><?= $no; ?></td>
                                        <td><?= $data['nama_user']; ?></td>
                                        <td><?= $data['username']; ?></td>
                                        <td align="center">

                                            <a href="?penyewa=biodata_user&sl=<?= $data['sl'] ?>" class="btn btn-link btn-primary btn-lg">
                                                <i class="far fa-eye"></i>
                                            </a>

                                            <a href="?penyewa=pemesanan_user&id_user=<?= $data['id_user'] ?>" class="btn btn-link btn-success btn-lg">
                                                <i class="fas fa-clipboard-list"></i>
                                            </a>

                                            <button type="button" class="btn btn-link btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#edit-<?= $data['id_user']?>">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button class="btn btn-link btn-danger btn-lg" onclick="deleteUser(<?= $data['id_user']?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </td>
                                    </tr>
                                <?php }  ?>
                            </tbody>

                            <tfoot>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>

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
                    <span class="fw-mediumbold">Add</span>
                    <span class="fw-light"> <?= $p?></span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="settings/functions/add/add_penyewa" method="post" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="Username">Username <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-at"></i>
                                    </span>

                                    <input type="text" name="username" id="username" class="form-control" placeholder="Masukan username" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="Password">Password <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>

                                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukan password" required>

                                    <span class="input-group-text" onclick="togglePassword('password')">
                                        <i class="fas fa-eye toggle-icon"></i>
                                    </span>

                                </div>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="nama">Nama</label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>

                                    <input type="text" name="nama_user" id="nama_user" class="form-control" placeholder="Masukan nama" required>

                                </div>

                                <div class="input-group">

                                    <input type="hidden" name="role" id="role" class="form-control" value="User" readonly required>

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
    $user = $mysqli->query("SELECT * FROM user WHERE id_user");
    while ($ea = mysqli_fetch_array($user)) {
    ?>
    <div class="modal fade" id="edit-<?= $ea['id_user']?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">Edit</span>
                        <span class="fw-light"> <?= $p?></span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body border-0">
                    <form action="settings/functions/edit/edit_penyewa" method="post" enctype="multipart/form-data">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">

                                    <input type="hidden" name="id_user" id="id_user" value="<?= $ea['id_user']?>" class="form-control" readonly>

                                    <label for="Username">Username <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="fas fa-at"></i>
                                        </span>

                                        <input type="text" name="username" id="username" class="form-control" placeholder="Masukan username" value="<?= $ea['username']?>" required>

                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">

                                    <label for="nama">Nama</label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>

                                        <input type="text" name="nama_user" id="nama_user" class="form-control" placeholder="Masukan nama" value="<?= $ea['nama_user']?>" required>

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
<?php  } ?>

<script>
    function deleteUser(id_user) {
        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data ini akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "settings/functions/delete/soft/sft_penyewa?id=" + id_user;
            }
        });
    }
</script>
