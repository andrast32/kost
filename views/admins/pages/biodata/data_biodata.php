<?php

    $data_biodata = $mysqli->query("SELECT * FROM biodata JOIN user ON biodata.id_user = user.id_user");

    $active_bio = [];
    while ($bio = $data_biodata->fetch_assoc()) {
        $active_bio[] = $bio;
    }

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex align-items-center">

                        <h4 class="card-title"><?= htmlspecialchars($h1);?></h4>

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
                                <?php if (empty($active_bio)) : ?>
                                    <tr>
                                        <td colspan="6" align="center">
                                            <i>Tidak ada data biodata</i>
                                        </td>
                                    </tr>
                                    <?php endif; ?>

                                    <?php
                                        $no = 0;
                                        foreach ($active_bio as $data) {
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
                                            <button type="button" class="btn btn-secondary btn-link btn-lg" data-bs-toggle="modal" data-bs-target="#document-<?= $data['id_biodata']?>">
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
<?php foreach ($active_bio as $data) : ?>
    <div class="modal fade" id="document-<?= $data['id_biodata']?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">Dokumen</span>
                        <span class="fw-light"><?= $data['nama_user']?></span>
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

                                <input type="text" class="form-control" value="<?= $data['no_hp'] ?>" readonly>

                            </div>

                        </div>

                        <?php if (!empty($data['scan_ktp'])) : ?>
                            <div class="col-md-6 pe-0">
                                <div class="form-group">

                                    <label for="ktp">
                                        KTP <?= $data['nama_user']?>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="far fa-address-card"></i>
                                        </span>

                                        <form action="/kost/assets/uploads/biodata/ktp/<?= $data['scan_ktp']; ?>" method="get" target="_blank"
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
                                        KK <?= $data['nama_user']?>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            <i class="far fa-address-card"></i>
                                        </span>

                                        <form action="/kost/assets/uploads/biodata/kk/<?= $data['scan_kk']; ?>" method="get" target="_blank"
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

                                        <form action="/kost/assets/uploads/biodata/bukti_nikah/<?= $data['bukti_nikah']; ?>" method="get"
                                        >
                                            <button type="submit" class="btn">
                                                Dokumen bukti pernikahan 
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
<?php endforeach; ?>