<?php
    $menus = [
        [
            'label'     => 'Dashboard',
            'icon'      => 'fas fa-home',
            'link'      => 'index',
            'active'    => $page === 'index',
        ],
        [
            'label'     => 'Biodata',
            'icon'      => 'fas fa-book-reader',
            'link'      => '?biodata=data_biodata',
            'active'    => $page === 'data_biodata',
        ],
        [
            'label'     => 'Data Pembayaran',
            'icon'      => 'fas fa-wallet',
            'link'      => '?pembayaran=data_pembayaran',
            'active'    => $page === 'data_pembayaran',
        ],
        [
            'label'     => 'Data Pemesanan',
            'icon'      => 'fas fa-clipboard-list',
            'link'      => '?pemesanan=data_pemesanan',
            'active'    => $page === 'data_pemesanan',
        ],
        [
            'label'         => 'Data Kost',
            'icon'          => 'fas fa-building',
            'submenu_id'    => 'dakost',
            'active'        => in_array($page, [
                'data_kamar', 
                'data_fasilitas'
            ]),
            'submenus'      => [
                [
                    'label'     => 'Data Kamar',
                    'link'      => '?kamar=data_kamar',
                    'active'    => $page === 'data_kamar',
                ],
                [
                    'label'     => 'Data Fasilitas',
                    'link'      => '?fasilitas=data_fasilitas',
                    'active'    => $page === 'data_fasilitas',
                ],
            ]
        ],
        [
            'label'         => 'Data User',
            'icon'          => 'fas fa-building',
            'submenu_id'    => 'daus',
            'active'        => in_array($page, [
                'data_penyewa', 
                'data_petugas', 
                'deleted_petugas', 
                'deleted_penyewa', 
                'biodata_user',
                'pemesanan_user'
            ]),
            'submenus'      => [
                [
                    'label'     => 'Data Penyewa',
                    'link'      => '?penyewa=data_penyewa',
                    'active'    => in_array($page, [
                        'data_penyewa',
                        'deleted_penyewa',
                        'biodata_user',
                        'pemesanan_user'
                    ]),
                ],
                [
                    'label'     => 'Data Petugas',
                    'link'      => '?petugas=data_petugas',
                    'active'    => in_array($page, ['data_petugas', 'deleted_petugas']),
                ],
            ]
        ]
    ];
?>

<!-- sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header mb-6" data-background-color="dark">
            <a href="#" class="logo mt-2">
                <img src="/kost/assets/UI/Dashboards/assets/images/info-icon-03.png" alt="navbar brand" class="navbar-brand" height="40" style="margin-bottom: 1rem; filter: invert(1) grayscale(1) brightness(3);">
                <span class="text-white" style="margin-left: 1rem; font-size: large;">
                    The <b>Kost</b>
                </span>
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

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <?php foreach ($menus as $menu): ?>
                    <?php if (!isset($menu['submenus'])): ?>
                        <li class="nav-item <?= $menu['active'] ? 'active' : '' ?>">
                            <a href="<?= $menu['link'] ?>">
                                <i class="<?= $menu['icon'] ?>"></i>
                                <p><?= $menu['label'] ?></p>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item <?= $menu['active'] ? 'submenu active' : '' ?>">
                            <a href="#<?= $menu['submenu_id'] ?>" data-bs-toggle="collapse">
                                <i class="<?= $menu['icon'] ?>"></i>
                                <p><?= $menu['label'] ?></p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse <?= $menu['active'] ? 'show' : '' ?>" id="<?= $menu['submenu_id'] ?>">
                                <ul class="nav nav-collapse">
                                    <?php foreach ($menu['submenus'] as $submenu): ?>
                                        <li class="nav-link <?= $submenu['active'] ? 'active' : '' ?>">
                                            <a href="<?= $submenu['link'] ?>">
                                                <span class="sub-item"><?= $submenu['label'] ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<div class="main-panel">