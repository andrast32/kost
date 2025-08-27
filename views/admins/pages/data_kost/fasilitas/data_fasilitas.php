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
                            <i class="fas fa-plus"></i> Add <?= $p; ?>
                        </button>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="laporan" class="display table table-striped table-hover">

                            <thead>
                                <tr align="center">

                                    <th>No</th>
                                    <th>Id Fasilitas</th>
                                    <th>Nama Fasilitas</th>
                                    <th style="width: 30%;">Deskripsi</th>
                                    <th>Harga</th>
                                    <th style="width: 10%;">Action</th>

                                </tr>
                            </thead>

                            <tbody>

                                <?php 
                                    $fasilitas = $mysqli->query("SELECT * FROM fasilitas where deleted != 1 ORDER BY kode_fasilitas, nama_fasilitas ASC");

                                    $no = 0;
                                    while ($data = mysqli_fetch_array($fasilitas)) {
                                        $no++;
                                ?>
                                    <tr align="center">

                                        <td><?= $no; ?></td>

                                        <td><?= $data['kode_fasilitas']; ?></td>

                                        <td><?= $data['nama_fasilitas']; ?></td>

                                        <td><?= $data['deskripsi']; ?></td>

                                        <td>
                                            <?php 
                                                if ($data['harga'] > 0) {
                                                    echo "Rp " . number_format($data['harga'], 0, ',', '.');
                                                } else {
                                                    echo "Gratis";
                                                }
                                            ?>
                                        </td>

                                        <td align="center">

                                            <button class="btn btn-link btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#edit-<?= $data['id_fasilitas']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button class="btn btn-link btn-danger btn-lg" onclick="deleteFasilitas(<?= $data['id_fasilitas']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </td>

                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Id Fasilitas</th>
                                    <th>Nama Fasilitas</th>
                                    <th>Deskripsi</th>
                                    <th>Harga</th>
                                    <th style="width: 10%;">Action</th>
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
                    <span class="fw-light"><?= $p?></span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="settings/functions/add/add_fasilitas" method="post" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="nama_fasilitas">Nama Fasilitas <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-pen"></i>
                                    </span>

                                    <input type="text" name="nama_fasilitas" id="nama_fasilitas" class="form-control" placeholder="Masukan nama fasilitas" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6 pe-0">
                            <div class="form-group">

                                <label for="kode_fasilitas">Kode Fasilitas <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>

                                    <input type="text" name="kode_fasilitas" id="kode_fasilitas" class="form-control" placeholder="Masukan kode fasilitas" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label for="harga">Harga Fasilitas <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>

                                    <input type="number" name="harga" id="harga" class="form-control" placeholder="Masukan harga fasilitas" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="deskripsi">Deskripsi Fasilitas <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-list"></i>
                                    </span>

                                    <textarea name="deskripsi" id="deskripsi" class="form-control" placeholder="Masukan deskripsi fasilitas" required rows="3" style="resize: none;"></textarea>

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
    $fasilitas = $mysqli->query("SELECT * FROM fasilitas WHERE id_fasilitas");
    while ($ef = mysqli_fetch_array($fasilitas)) {
?>
    <div class="modal fade" id="edit-<?= $ef['id_fasilitas']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">Edit</span>
                        <span class="fw-light">
                            <?= $p?>
                            <?= $ef['nama_fasilitas'] ?>
                        </span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body"></div>

            </div>
        </div>
    </div>

<?php } ?>