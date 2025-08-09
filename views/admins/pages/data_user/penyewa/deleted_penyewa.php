<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data User Terhapus</h4>

                        <a href="?penyewa=data_penyewa" class="btn btn-round btn-primary btn-border ms-auto">
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
                                    $user = $mysqli->query("SELECT * FROM user WHERE deleted = 1 AND role = 'user' ORDER BY id_user ASC");
                                    $no = 0;
                                    while ($data = mysqli_fetch_array($user)) {
                                        $no++;
                                ?>
                                    <tr>
                                        <td align="center"><?= $no; ?></td>
                                        <td><?= $data['nama_user']; ?></td>
                                        <td><?= $data['username']; ?></td>
                                        <td align="center">
                                            <button class="btn btn-link btn-success btn-lg" onclick="restoreuser(<?= $data['id_user']; ?>)">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button class="btn btn-link btn-danger btn-lg" onclick="deletePermanent(<?= $data['id_user']; ?>)">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
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
    function restoreuser(id) {
        Swal.fire({
            title: 'Restore data?',
            text: "Anda yakin akan mengembalikan data ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, kembalikan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "settings/functions/restore/rest_penyewa?id=" + id;
            }
        });
    }

    function deletePermanent(id) {
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
                window.location.href = "settings/functions/delete/permanent/del_penyewa?id=" + id;
            }
        });
    }
</script>
