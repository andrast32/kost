<?php

    $data_fasilitas = $mysqli->query("SELECT * FROM fasilitas ORDER BY id_fasilitas ASC");

    $active_fasilitas = [];
    $has_deleted_fasilitas = false;

    while ($fasilitas = $data_fasilitas->fetch_assoc()) {
        if ($fasilitas['deleted'] != 1) {
            $active_fasilitas[] = $fasilitas;
        } else {
            $has_deleted_fasilitas = true;
        }
    }

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex align-items-center">

                        <h4 class="card-title">
                            <?= htmlspecialchars($h1) ?>
                        </h4>

                        <button class="btn btn-border btn-round btn-primary ms-auto" style="margin-right: 0.5rem;" data-bs-toggle="modal" data-bs-target="#add">
                            <i class="fas fa-plus"></i> Add <?= htmlspecialchars($p) ?>
                        </button>

                        <?php 
                            if ($has_deleted_fasilitas) :
                        ?>

                            <a href="?fasilitas=deleted_fasilitas" class="btn btn-round btn-danger btn-border">
                                <i class="fas fa-trash"></i> Lihat sampah
                            </a>

                        <?php endif; ?>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="laporan" class="display table table-striped table-hover">

                            <thead>
                                <tr align="center">

                                    <th style="width: 10%;">No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Stok</th>
                                    <th>Foto</th>
                                    <th>Harga</th>
                                    <th style="width: 10%;">Action</th>

                                </tr>
                            </thead>

                            <tbody>

                                <?php 

                                    $no = 0;
                                    foreach ($active_fasilitas as $data) {
                                        $no++;
                                        ?>
                                    <tr>

                                        <td align="center"><?= $no; ?></td>

                                        <td align="center"><?= htmlspecialchars($data['kode_fasilitas']) ?></td>

                                        <td><?= htmlspecialchars($data['nama_fasilitas']) ?></td>

                                        <td align="center"><?= htmlspecialchars($data['stok']) ?></td>

                                        <td>
                                            <div class="avatar avatar-xxl">
                                                <img src="/kost/assets/uploads/fasilitas/<?= $data['foto']; ?>" alt="Foto fasilitas" class="avatar-img rounded">
                                            </div>
                                        </td>

                                        <td>
                                            <?php 
                                                if ($data['harga'] >= 5000) {
                                                    echo "Rp " . number_format($data['harga'], 2, ',', '.');
                                                } else {
                                                    echo "Gratis";
                                                }
                                            ?>
                                        </td>

                                        <td align="center">

                                            <button 
                                                class="btn btn-link btn-primary btn-lg edit-btn"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#edit"
                                                data-id="<?= $data['id_fasilitas']; ?>"
                                                data-kode="<?= htmlspecialchars($data['kode_fasilitas']); ?>"
                                                data-nama="<?= htmlspecialchars($data['nama_fasilitas']); ?>"
                                                data-stok="<?= htmlspecialchars($data['stok']); ?>"
                                                data-harga="<?= htmlspecialchars($data['harga']); ?>"
                                                data-foto="<?= $data['foto']; ?>"
                                                data-deskripsi="<?= htmlspecialchars($data['deskripsi']); ?>">
                                                    <i class="fas fa-edit"></i>
                                            </button>

                                            <button 
                                                class="btn btn-link btn-danger btn-lg"
                                                onclick="deleteFasilitas(
                                                    <?= $data['id_fasilitas']; ?>,
                                                    '<?= htmlspecialchars($data['nama_fasilitas']); ?>'
                                                )">
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
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Stok</th>
                                    <th>Foto</th>
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
                    <span class="fw-light"><?= $h1?></span>
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

                        <div class="col-md-6 pe-0">
                            <div class="form-group">

                                <label for="stok">Stok Fasilitas <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-box"></i>
                                    </span>

                                    <input type="number" name="stok" id="stok" class="form-control" placeholder="Masukan stok fasilitas" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-control">

                                <label for="foto">
                                    Foto Fasilitas
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-camera"></i>
                                    </span>

                                    <input type="file" name="foto" id="foto" class="form-control" accept=".jpg, .jpeg, .png">

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
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Edit</span>
                    <span class="fw-light">
                        <?= $h1?>
                    </span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="settings/functions/edit/edit_fasilitas" method="post" enctype="multipart/form-data">
                    <div class="row">

                        <input type="text" class="form-control" id="id_fasilitas" name="id_fasilitas" readonly>

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

                        <div class="col-md-6 pe-0">
                            <div class="form-group">

                                <label for="stok">Stok Fasilitas <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-box"></i>
                                    </span>

                                    <input type="number" name="stok" id="stok" class="form-control" placeholder="Masukan stok fasilitas" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-control">

                                <label for="foto">
                                    Foto Fasilitas
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-camera"></i>
                                    </span>

                                    <input type="file" name="foto" id="foto" class="form-control" accept=".jpg, .jpeg, .png">

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

<script>
    function deleteFasilitas(id_fasilitas, nama_fasilitas) {
        Swal.fire({
            title: 'Yakin mau hapus <?= $p?>?',
            text: "Fasilitas " + nama_fasilitas + " akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'settings/functions/delete/soft/sft_fasilitas?id_fasilitas=' + id_fasilitas;
            }
        })
    }
</script>