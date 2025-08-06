<div class="main-header">

    <div class="main-header-logo">

        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            
            <a href="#" class="logo">
                <img src="/kost/assets/UI/Dashboards/assets/images/info-icon-03.png" alt="Navbar brand" class="navbar-brand" height="40" style="margin-bottom: 1rem; filter: invert(1) grayscale(1) brightness(3);">
            </a>

            <div class="nav-toggle">
    
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
    
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
    
            </div>

            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>

        </div>

    </div>

    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom" data-background-color="dark">
        <div class="container-fluid">

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">

                    <a href="#" class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" aria-expanded="false">

                        <div class="avatar-sm">
                            <img src="/kost/assets/UI/Dashboards/assets/images/person.png" alt="Avatar user" class="avatar-img rounded-circle" style="max-width: 30px; filter: invert(1);">
                        </div>

                        <span class="profile-username">
                            <span class="op-7">Hai,</span>
                            <span class="fw-bold"><?= $_SESSION['nama_user'] ?></span>
                        </span>

                    </a>

                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll srollbar-outer">

                            <li>
                                <div class="user-box">

                                    <div class="avatar-lg">
                                        <img src="/kost/assets/UI/Dashboards/assets/images/person.png" alt="Foto profile" class="avatar-img rounded" style="max-width: 30px; filter: invert(1);">
                                    </div>

                                    <div class="u-text">
                                        <h4 class="text-white mt-4"><?= $_SESSION['nama_user'] ?></h4>
                                    </div>

                                </div>
                            </li>

                            <li>

                                <div class="dropdown-divider"></div>

                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#change_pw-<?= $_SESSION['id_user']?>">
                                    Ubah Password
                                </button>
                                <div class="dropdown-divider"></div>

                                <form action="/kost/backup/backup-db-kost.sql" method="get">
                                    <button type="submit" class="dropdown-item text-white">
                                        Unduh database
                                    </button>
                                </form>

                                <div class="dropdown-divider"></div>

                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#import">
                                    Upload database
                                </button>

                                <div class="dropdown-divider"></div>

                                <a href="../controller/logout" class="dropdown-item">
                                    Logout
                                </a>

                                <div class="dropdown-divider"></div>

                            </li>

                        </div>
                    </ul>

                </li>
            </ul>

        </div>
    </nav>

</div>