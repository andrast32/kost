<?php 

    $jumlah_penyewa = $mysqli->query("SELECT COUNT(*) as total FROM user WHERE role = 'penyewa'")->fetch_assoc()['total'];
    $jumlah_kamar = $mysqli->query("SELECT COUNT(*) as total FROM kamar")->fetch_assoc()['total'];
    $jumlah_fasilitas = $mysqli->query("SELECT COUNT(*) as total FROM fasilitas")->fetch_assoc()['total'];

?>

<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Jumlah Penyewa</p>
                            <h4 class="card-title"><?= $jumlah_penyewa ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-info bubble-shadow-small">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Jumlah Kamar</p>
                            <h4 class="card-title"><?= $jumlah_kamar ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-success bubble-shadow-small">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Jumlah fasilitas</p>
                            <h4 class="card-title"><?= $jumlah_fasilitas ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total uang</p>
                            <h4 class="card-title">Rp. 1.000.000</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-round">
    <div class="row">

        <!-- Kartu Profil -->
        <div class="col-md-5" style="margin-right: 20%;">
            <div class="card card-profile">
                <div class="card-header" style="background-image: url(/kost/assets/UI/Dashboards/assets/images/banner-01.jpg); background-size: cover;">
                </div>
                <div class="card-body">
                    <div class="user-profile text-center">
                        <div class="name"><?php echo $_SESSION['nama_user'] ?></div>
                        <div class="job"><?php echo $_SESSION['username'] ?></div>
                        <div class="desc"><?php echo $_SESSION['role'] ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <strong>Quick Actions</strong>
                </div>
                <div class="card-body">
                    <button class="btn btn-info btn-sm w-100 mb-2">
                        <i class="fas fa-user-plus"></i> Tambah Penyewa
                    </button>
                    <button class="btn btn-warning btn-sm w-100 mb-2">
                        <i class="fas fa-door-open"></i> Tambah Kamar
                    </button>
                    <button class="btn btn-warning btn-sm w-100 mb-2">
                        <i class="fas fa-tools"></i> Tambah Fasilitas
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

