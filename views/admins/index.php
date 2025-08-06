<?php

    $params = [
        'pages',
        'kamar',
        'biodata',
        'fasilitas',
        'pembayaran',
        'pemesanan',
        'penyewa',
        'petugas'
    ];

    foreach ($params as $key) {
        if (isset($_GET[$key])) {
            $page = $_GET[$key];
            break;
        }
    }

    $page = $page ?? 'index';

    $configs = [
        'data_kamar' => [
            'title'     => 'The Kost | Data Kamar',
            'breadcumb' => 'Data Kost | Data Kamar',
            'h1'        => 'Data Kamar',
            'p'         => 'Kamar',
        ],
        'data_fasilitas' => [
            'title'     => 'The Kost | Data Fasilitas',
            'breadcumb' => 'Data Kost | Data Fasilitas',
            'h1'        => 'Data Fasilitas',
            'p'         => 'Fasilitas',
        ],
        'data_penyewa' => [
            'title'     => 'The Kost | Data Penyewa',
            'breadcumb' => 'Data User | Data Penyewa',
            'h1'        => 'Data Penyewa',
            'p'         => 'Penyewa',
        ],
        'data_petugas' => [
            'title'     => 'The Kost | Data Petugas',
            'breadcumb' => 'Data User | Data Petugas',
            'h1'        => 'Data Petugas',
            'p'         => 'Petugas',
        ],
        'data_pembayaran' => [
            'title'     => 'The Kost | Data Pembayaran',
            'breadcumb' => 'Data Pembayaran',
            'h1'        => 'Data Pembayaran',
            'p'         => 'Pembayaran',
        ],
        'data_pemesanan' => [
            'title'     => 'The Kost | Data Pemesanan',
            'breadcumb' => 'Data Pemesanan',
            'h1'        => 'Data Pemesanan',
            'p'         => 'Pemesanan',
        ],
        'index' => [
            'title'     => 'The Kost | Dashboard',
            'breadcumb' => 'Dashboard',
            'h1'        => 'Dashboard',
            'p'         => '',
        ],
        'deleted_penyewa' => [
            'title'     => 'The Kost | Deleted Penyewa',
            'breadcumb' => 'Data User | Data Penyewa | Deleted Penyewa',
            'h1'        => 'Deleted Data Penyewa',
            'p'         => ''
        ],
        'data_biodata' => [
            'title'     => 'The Kost | Biodata Penyewa',
            'breadcumb' => 'Data User | Biodata',
            'h1'        => 'Biodata Penyewa',
            'p'         => 'Biodata',
        ],
        'biodata_user' => [
            'title'     => 'The Kost | Biodata Penyewa',
            'breadcumb' => 'Data User | Data Penyewa | Biodata',
            'h1'        => 'Biodata Penyewa',
            'p'         => 'Biodata',
        ],
        'pesan/pemesanan_user' => [
            'title'     => 'The Kost | Pemesanan Penyewa',
            'breadcumb' => 'Data User | Data Penyewa | Pemesanan',
            'h1'        => 'Pemesanan Penyewa',
            'p'         => 'Pemesanan',
        ],
        'pesan/pemesanan_user' => [
            'title'     => 'The Kost | Pemesanan Penyewa',
            'breadcumb' => 'Data User | Data Penyewa | Pemesanan',
            'h1'        => 'Pemesanan Penyewa',
            'p'         => 'Pemesanan',
        ],
        'deleted_petugas' => [
            'title'     => 'The Kost | Deleted Petugas',
            'breadcumb' => 'Data User | Data Petugas | Deleted Petugas',
            'h1'        => 'Deleted Data Petugas',
            'p'         => ''
        ],
    ];

    $page_config = $configs[$page] ?? $configs['index'];

    $title      = $page_config['title'];
    $breadcumb  = $page_config['breadcumb'];
    $h1         = $page_config['h1'];
    $p          = $page_config['p'];

    include "../controller/connect.php";
    include "settings/styles/header.php";
    include "settings/styles/sidebar.php";
    include "settings/styles/navbar.php";
    ?>

    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3"><span style="color: #8b0420;">The Kost</span> | <?= $breadcumb; ?></h3>
                    <h6 class="op-7 mb-2">Halaman <?= $h1; ?></h6>
                </div>
            </div>

            <section class="content">
                <?php
                    include "settings/functions/control.php";
                ?>
            </section>

        </div>
    </div>

    <?php 
    include "settings/styles/footer.php";
    include "settings/styles/scripts.php";

    $admin = $mysqli->query("SELECT * FROM user WHERE id_user = '$_SESSION[id_user]'");
    while ($a = mysqli_fetch_array($admin)) {
?>

    <div class="modal" id="change_pw-<?= $a['id_user']?>">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="modal-title">Ubah password</h3>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form method="POST" action="<?= htmlspecialchars('?settings=functions/reset/pw'); ?>" enctype="multipart/form-data">

                        <div class="mb-3">

                            <label for="username" class="form-label">Username</label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>

                                <input type="text" name="username" id="username" class="form-control" value="<?= $a['username'] ?>" readonly>
                                
                            </div>

                        </div>

                        <div class="mb-3">

                            <label for="current_pass" class="form-label">Password saat ini <span class="text-danger">*</span></label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>

                                <input type="password" name="current_pass" id="current_pass" class="form-control" placeholder="Masukan password saat ini">

                                <span class="input-group-text" onclick="togglePassword('current_pass')">
                                    <i class="fas fa-eye toggle-icon"></i>
                                </span>

                            </div>

                        </div>

                        <div class="mb-3">

                            <label for="new_pass" class="form-label">Password baru <span class="text-danger">*</span></label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>

                                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Masukan password baru">

                                <span class="input-group-text" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye toggle-icon"></i>
                                </span>

                            </div>

                        </div>

                        <div class="mb-3">

                            <label for="confirm_password" class="form-label">Confirm password baru <span class="text-danger">*</span></label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>

                                <input type="password" name="confrim_pass" id="confrim_pass" class="form-control" placeholder="Konfirmasi password baru">

                                <span class="input-group-text" onclick="togglePassword('confrim_pass')">
                                    <i class="fas fa-eye toggle-icon"></i>
                                </span>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <input type="reset" value="Reset" class="btn btn-round btn-border btn-primary float-right">
                            <input type="submit" value="Submit" class="btn btn-round btn-border btn-warning float-right">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

<?php } ?>

<?php
    if (isset($_SESSION['alert'])) {
        echo "
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: '{$_SESSION['alert']['icon']}',
                    title: '{$_SESSION['alert']['title']}',
                    text: '{$_SESSION['alert']['text']}',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true
                });
            </script>
        ";
        unset($_SESSION['alert']);
    }
?>

<div class="modal" id="import">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title">Upload database</h3>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-label="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="settings/functions/sql/import" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="file">Upload database <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-control" accept=".sql" required>
                    </div>

                    <div class="modal-footer">
                        <input type="reset" value="Reset" class="btn btn-round btn-border btn-primary float-right">
                        <input type="submit" value="Submit" class="btn btn-round btn-border btn-danger float-right">
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>