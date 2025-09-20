<?php

    $deleted_fasilitas = $mysqli->query("SELECT * FROM fasilitas ORDER BY id_fasilitas ASC");

    $active_fasilitas = [];
    $has_deleted_fasilitas = true;

    while ($fasilitas = $deleted_fasilitas->fetch_assoc()) {
        if ($fasilitas['deleted'] != 0) {
            $active_fasilitas[] = $fasilitas;
        } else {
            $has_deleted_fasilitas = false;
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

                        <a href="?fasilitas=data_fasilitas" class="btn btn-round btn-primary btn-border ms-auto">
                            <i class="fas fa-angle-double-left"></i> Kembali
                        </a>

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

                                <?php if (empty($active_fasilitas)) : ?>
                                    <tr>
                                        <td colspan="7" align="center">Tidak ada data fasilitas yang terhapus.</td>
                                    </tr>
                                    <?php else : ?>

                                        <?php 

                                        $no = 0;
                                        foreach ($active_fasilitas as $data) {
                                            $no++;
                                            ?>
                                            <tr>

                                                <td align="center"><?= $no; ?></td>
                                                <td align="center"><?= htmlspecialchars($data['kode_fasilitas']) ?></td>
                                                <td><?= htmlspecialchars($data['nama_fasilitas']) ?></td>
                                                <td align="center">
                                                    <?php 
                                                        if ($data['stok'] >= 1) {
                                                            echo $data['stok'];
                                                        } else {
                                                            echo "Stok habis";
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="avatar avatar-xxl">
                                                        <img src="/kost/assets/uploads/fasilitas/<?= $data['foto']; ?>" alt="Foto fasilitas" class="avatar-img rounded">
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if ($data['harga'] >= 5000) {
                                                            echo "Rp " . number_format($data['harga'], 0, ',', '.');
                                                        } else {
                                                            echo "Gratis";
                                                        }
                                                    ?>
                                                </td>

                                                <td align="center">

                                                    <button
                                                        class="btn btn-link btn-success btn-lg"
                                                        onclick="restorefasilitas(
                                                            <?= $data['id_fasilitas']; ?>,
                                                            '<?= htmlspecialchars($data['nama_fasilitas']) ?>'
                                                        )">
                                                        <i class="fas fa-undo"></i>
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
                                    endif;
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

<script>
    function restorefasilitas(id, nama_fasilitas) {
        Swal.fire({
            title: 'Kembalikan <?= htmlspecialchars($p) ?>',
            text: "Anda yakin akan mengembalikan fasilitas " + nama_fasilitas + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Ya, kembalikan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'settings/functions/restore/rest_fasilitas?id=' + id;
            }
        })
    }

    function deleteFasilitas(id, nama_fasilitas) {
        Swal.fire({
            title: 'Hapus <?= htmlspecialchars($p) ?>?',
            text: "Anda yakin akan menghapus fasilitas " + nama_fasilitas + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'settings/functions/delete/permanent/del_fasilitas?id=' + id;
            }
        })
    }
</script>