<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex align-items-center">

                        <h4 class="card-title"><?= $h1;?></h4>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data" class="display table table-striped table-hover">

                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th style="width: 25%;">Alamat</th>
                                    <th>Foto</th>
                                    <th style="width: 15%;">Document</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $no = 0;
                                    $bio = $mysqli->query("SELECT * FROM biodata JOIN user ON biodata.id_user = user.id_user ORDER BY nama_user ASC");
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

                                    </tr>

                                <?php }?>
                            </tbody>

                            <tfoot>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th style="width: 25%;">Alamat</th>
                                    <th>Foto</th>
                                    <th style="width: 15%;">Document</th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- modal doc -->
<?php
    $bio = $mysqli->query("SELECT * FROM biodata JOIN user ON biodata.id_user = user.id_user");
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
