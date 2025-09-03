<?php

    $delete_admin_result = $mysqli->query("SELECT * FROM user WHERE role = 'Admin' ORDER BY id_user ASC");

    $active_admin = [];
    $has_deleted_users = true;

    while ($admin = $delete_admin_result->fetch_assoc()) {
        if ($admin['deleted'] != 0) {
            $active_admin[] = $admin;
        } else {
            $has_deleted_users = false;
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

                        <a href="?petugas=data_petugas" class="btn btn-round btn-primary btn-border ms-auto">
                            <i class="fas fa-angle-double-left"></i> Kembali
                        </a>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data" class="display table table-striped table-hover">

                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php

                                    $no = 0;
                                    foreach ($active_admin as $data) {
                                        $no++;
                                        ?>
                                        <tr align="center">

                                            <td><?= $no ?></td>

                                            <td><?= htmlspecialchars($data['nama_user']) ?></td>

                                            <td><?= htmlspecialchars($data['username']) ?></td>

                                            <td align="center">

                                                <button 
                                                    class="btn btn-link btn-success btn-lg" 
                                                    onclick="restoreuser(
                                                        <?= $data['id_user']; ?>, 
                                                        '<?= htmlspecialchars($data['nama_user']) ?>'
                                                    )">
                                                        <i class="fas fa-undo"></i>
                                                </button>

                                                <button 
                                                    class="btn btn-link btn-danger btn-lg" 
                                                    onclick="deletePermanent(
                                                        <?= $data['id_user']; ?>,
                                                        '<?= htmlspecialchars($data['nama_user']) ?>'
                                                    )">
                                                    <i class="fas fa-trash-alt"></i>
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
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th style="width: 15%;">Action</th>
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
    function restoreuser(id, nama_user) {
        Swal.fire({
            title: 'Kembalikan <?= htmlspecialchars($p) ?>',
            text: "Anda yakin akan mengembalikan data " + nama_user + "?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, kembalikan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "settings/functions/restore/rest_petugas?id=" + id;
            }
        });
    }

    function deletePermanent(id, nama_user) {
        Swal.fire({
            title: 'Hapus <?= htmlspecialchars($p) ?>?',
            text: "Data " + nama_user + " akan dihapus selamanya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e74c3c'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "settings/functions/delete/permanent/del_petugas?id=" + id;
            }
        });
    }
</script>
