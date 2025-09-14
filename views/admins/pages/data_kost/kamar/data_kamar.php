<?php

    $data_kamar = $mysqli->query("SELECT * FROM kamar ORDER BY kode_kamar ASC");

    $kamar = [];
    while ($row = $data_kamar->fetch_assoc()) {
        $kamar[] = $row;
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

                        <button 
                            class="btn btn-border btn-round btn-primary ms-auto" 
                            style="margin: 0 0.5rem;" 
                            data-bs-toggle="modal" 
                            data-bs-target="#add">
                                <i class="fas fa-plus"></i>
                                tambah <?= htmlspecialchars($p) ?>
                        </button>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="laporan" class="display table table-striped table-hover">

                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Khusus</th>
                                    <th>Foto</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (empty($kamar)) : ?>
                                    <tr>
                                        <td colspan="7" align="center">Tidak ada data kamar yang tersedia.</td>
                                    </tr>
                                    <?php else : ?>

                                    <?php

                                    $no = 0;
                                    foreach ($kamar as $data) {
                                        $no++;
                                    ?>
                                    <tr>
                                        <td align="center"><?= $no; ?></td>
                                        <td align="center"><?= htmlspecialchars($data['kode_kamar']) ?></td>
                                        <td>
                                            <?= "Rp. " . number_format($data['harga'], 2, ",", "."); ?>
                                        </td>
                                        <td><?= $data['status']; ?></td>
                                        <td><?= $data['khusus']; ?></td>
                                        <td align="center">
                                            <div class="avatar avatar-xxl">
                                                <img 
                                                src="/kost/assets/uploads/kamar/<?= $data['foto']; ?>" 
                                                alt="Foto kamar <?= $data['kode_kamar']; ?>"
                                                class="avatar-img rounded">
                                            </div>
                                        </td>
                                        <td align="center">

                                            <button 
                                                type="button" 
                                                class="btn btn-link btn-primary btn-lg edit-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target ="#edit"
                                                data-id="<?= $data['id_kamar'] ?>"
                                                data-kode="<?= htmlspecialchars($data['kode_kamar']) ?>"
                                                data-harga="<?= $data['harga'] ?>"
                                                data-status="<?= $data['status'] ?>"
                                                data-khusus="<?= $data['khusus'] ?>"
                                                data-foto="<?= $data['foto'] ?>"
                                                data-deskripsi="<?= htmlspecialchars($data['deskripsi']) ?>"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button 
                                                class="btn btn-link btn-danger" 
                                                onclick="deleteKamar(
                                                <?= $data['id_kamar']?>, 
                                                '<?= htmlspecialchars($data['kode_kamar']) ?>'
                                            )">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </td>
                                    </tr>
                                    <?php } ?>
                                <?php endif; ?>
                            </tbody>

                            <tfoot>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Khusus</th>
                                    <th>Foto</th>
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
                    <span class="fw-light"> <?= $h1?></span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="settings/functions/add/add_kamar" method="post" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-6 pe-0">
                            <div class="form-group">

                                <label for="kode_kamar">kode kamar <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>

                                    <input type="text" name="kode_kamar" id="kode_kamar" class="form-control" placeholder="Masukan kode kamar" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label for="harga">Harga kamar <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>

                                    <input type="number" name="harga" id="harga" class="form-control" placeholder="Masukan harga kamar " required>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6 pe-0">
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

                        <div class="col-md-6">
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

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="deskripsi">
                                    Deskripsi Kamar 
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-list"></i>
                                    </span>

                                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Masukan deskripsi kamar" required style="resize: none;"></textarea>

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
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Edit</span>
                    <span class="fw-light"> <?= $h1?></span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="settings/functions/edit/edit_kamar" method="post" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-6 pe-0">
                            <div class="form-group">

                                <label for="nama">Kode kamar</label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>

                                    <input type="hidden" name="id_kamar" id="edit_id_kamar" class="form-control" readonly>

                                    <input type="text" name="kode_kamar" id="edit_kode_kamar" class="form-control" placeholder="Masukan kode kamar" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">


                                <label for="harga">harga <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>

                                    <input type="text" name="harga" id="edit_harga" class="form-control" placeholder="Masukan harga" required>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6 pe-0">
                            <div class="form-group">

                                <label for="khusus">Kamar Khusus <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-venus-mars"></i>
                                    </span>

                                    <select name="khusus" id="edit_khusus" class="form-control" required>
                                        <option value="" disabled>
                                            Kamar Khusus
                                        </option>
                                        <option value="Laki-Laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label for="foto">
                                    Foto Kamar
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-camera"></i>
                                    </span>

                                    <input type="file" name="foto" id="edit_foto" class="form-control" accept=".jpg, .png, .jpeg">

                                </div>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">

                                <label for="deskripsi">
                                    Deskripsi Kamar 
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-list"></i>
                                    </span>

                                    <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3" placeholder="Masukan deskripsi kamar" required style="resize: none;"></textarea>

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
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('edit');
        
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const id_kamar = button.getAttribute('data-id');
            const kode_kamar = button.getAttribute('data-kode');
            const harga = button.getAttribute('data-harga');
            const khusus = button.getAttribute('data-khusus');
            const deskripsi = button.getAttribute('data-deskripsi');

            const modalForm = editModal.querySelector('form');

            modalForm.querySelector('#edit_id_kamar').value = id_kamar;
            modalForm.querySelector('#edit_kode_kamar').value = kode_kamar;
            modalForm.querySelector('#edit_harga').value = harga;
            modalForm.querySelector('#edit_deskripsi').value = deskripsi;
            modalForm.querySelector('#edit_khusus').value = khusus; 
        });
    });

    function deleteKamar(id_kamar, kode_kamar) {
        Swal.fire({
            title: 'Yakin mau hapus <?= htmlspecialchars($p)?>?',
            text: "Data kamar dengan kode " + kode_kamar + " akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "settings/functions/delete/permanent/del_kamar?id_kamar=" + id_kamar;
            }
        });
    }
</script>
