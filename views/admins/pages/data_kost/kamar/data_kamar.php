<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex align-items-center">

                        <h4 class="card-title">
                            <?= $h1; ?>
                        </h4>

                        <button class="btn btn-border btn-round btn-primary ms-auto" style="margin-right: 0.5rem;" data-bs-toggle="modal" data-bs-target="#add">
                                <i class="fas fa-plus"></i> Add <?= $p;?>
                        </button>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="laporan" class="display table table-striped table-hover">

                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Id Kamar</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Khusus</th>
                                    <th>Foto</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Id Kamar</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Khusus</th>
                                    <th>Foto</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>

                            <tbody>
                                <?php
                                    $kamar = $mysqli->query("SELECT * FROM kamar ORDER BY kode ASC");

                                    $no = 0;
                                    while ($data = mysqli_fetch_array($kamar)) {
                                        $no++;
                                ?>
                                    <tr>
                                        <td align="center"><?= $no; ?></td>
                                        <td align="center"><?= $data['kode']; ?></td>
                                        <td>
                                            <?php
                                                $harga = "Rp. " . number_format($data['harga'], 2 , ",", ".");
                                                echo $harga;
                                            ?>
                                        </td>
                                        <td><?= $data['status']; ?></td>
                                        <td><?= $data['khusus']; ?></td>
                                        <td align="center">
                                            <div class="avatar avatar-xxl">
                                                <img 
                                                src="/kost/assets/uploads/kamar/<?= $data['foto']; ?>" 
                                                alt="Foto kamar <?= $data['kode']; ?>"
                                                class="avatar-img rounded">
                                            </div>
                                        </td>
                                        <td align="center">

                                            <button type="button" class="btn btn-link btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#edit-<?= $data['id_kamar']?>">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button class="btn btn-link btn-danger" onclick="deleteKamar(<?= $data['id_kamar']?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </td>
                                    </tr>
                                <?php }  ?>
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
                    <span class="fw-mediumbold">Add</span>
                    <span class="fw-light"> <?= $p?></span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="settings/functions/add/add_kamar" method="post" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="kode">kode <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>

                                    <input type="text" name="kode" id="kode" class="form-control" placeholder="Masukan kode kamar" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="harga">Harga <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>

                                    <input type="number" name="harga" id="harga" class="form-control" placeholder="Masukan harga kamar " required>

                                </div>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="khusus">Kamar Khusus <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-venus-mars"></i>
                                    </span>

                                    <select name="khusus" id="khusus" class="form-control">
                                        <option value="" disabled selected>Kamar Khusus</option>
                                        <option value="Laki-Laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>

                                </div>

                                <div class="input-group">

                                    <input type="hidden" name="status" id="status" class="form-control" value="Kosong" readonly required>

                                </div>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="foto">
                                    Foto Kamar 
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

                    </div>
                    <div class="modal-footer">
                        <input type="reset" value="Reset" class="btn btn-border btn-round btn-primary float-right">
                        <input type="submit" value="Submit" class="btn btn-border btn-round btn-success float-right">
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- modal edit -->
<?php
    $kamar = $mysqli->query("SELECT * FROM kamar WHERE id_kamar");
    while ($ea = mysqli_fetch_array($kamar)) {
    ?>
    <div class="modal fade" id="edit-<?= $ea['id_kamar']?>" tabindex="-1" role="dialog" aria-hidden="true">
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

                <div class="modal-body">
                    <form action="settings/functions/edit/edit_petugas" method="post" enctype="multipart/form-data">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">

                                    <input type="hidden" name="id_kamar" id="id_kamar" value="<?= $ea['id_kamar']?>" class="form-control" readonly>

                                    <label for="harga">harga <span class="text-danger">*</span></label>

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

                                        <input type="text" name="nama_kamar" id="nama_kamar" class="form-control" placeholder="Masukan nama" value="<?= $ea['nama_kamar']?>" required>

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
    function deleteUser(id_kamar) {
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
                window.location.href = "settings/functions/delete/soft/sft_petugas?id=" + id_kamar;
            }
        });
    }
</script>
