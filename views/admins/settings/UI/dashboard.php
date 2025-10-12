<?php

// --- Statistik Utama ---
$jumlah_penyewa_aktif = $mysqli->query("SELECT COUNT(*) as total FROM user WHERE role = 'User' AND deleted = 0")->fetch_assoc()['total'];
$kamar_terisi = $mysqli->query("SELECT COUNT(*) as total FROM kamar WHERE status = 'Terisi'")->fetch_assoc()['total'];
$kamar_kosong = $mysqli->query("SELECT COUNT(*) as total FROM kamar WHERE status = 'Kosong'")->fetch_assoc()['total'];
$total_kamar = $kamar_terisi + $kamar_kosong;

// --- Data Keuangan ---
$bulan_ini = date('m');
$tahun_ini = date('Y');
$pendapatan_bulan_ini = $mysqli->query("
    SELECT SUM(jumlah_bayar) as total 
    FROM pembayaran 
    WHERE status = 'Lunas' AND MONTH(tanggal_bayar) = $bulan_ini AND YEAR(tanggal_bayar) = $tahun_ini
")->fetch_assoc()['total'] ?? 0;

// --- Notifikasi Aksi Cepat ---
$query_cek_konfirmasi = $mysqli->query("SELECT id_pemesanan FROM pemesanan WHERE status_kontrak = 'Menunggu' LIMIT 1");
$perlu_konfirmasi = $query_cek_konfirmasi->num_rows > 0;

// --- Data untuk Slideshow ---
$kamar_untuk_slideshow = $mysqli->query("SELECT kode_kamar, harga, foto FROM kamar WHERE status = 'Kosong' LIMIT 5")->fetch_all(MYSQLI_ASSOC);
$fasilitas_untuk_slideshow = $mysqli->query("SELECT nama_fasilitas, harga, foto FROM fasilitas WHERE stok > 0 AND deleted != 1 LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// --- Data untuk Tabel "Kontrak Segera Berakhir" ---
$kontrak_akan_berakhir = $mysqli->query("
    SELECT u.nama_user, p.tanggal_akhir_kontrak, DATEDIFF(p.tanggal_akhir_kontrak, CURDATE()) as sisa_hari
    FROM pemesanan p
    JOIN user u ON p.id_user = u.id_user
    WHERE p.status_kontrak = 'Aktif' AND p.tanggal_akhir_kontrak BETWEEN CURDATE() AND CURDATE() + INTERVAL 30 DAY
    ORDER BY sisa_hari ASC
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

?>

<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon"><div class="icon-big text-center icon-primary bubble-shadow-small"><i class="fas fa-users"></i></div></div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Penyewa Aktif</p>
                            <h4 class="card-title"><?= $jumlah_penyewa_aktif ?></h4>
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
                    <div class="col-icon"><div class="icon-big text-center icon-info bubble-shadow-small"><i class="fas fa-door-closed"></i></div></div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Kamar Terisi</p>
                            <h4 class="card-title"><?= $kamar_terisi ?> / <?= $total_kamar ?></h4>
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
                    <div class="col-icon"><div class="icon-big text-center icon-success bubble-shadow-small"><i class="fas fa-door-open"></i></div></div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Kamar Tersedia</p>
                            <h4 class="card-title"><?= $kamar_kosong ?></h4>
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
                    <div class="col-icon"><div class="icon-big text-center icon-secondary bubble-shadow-small"><i class="fas fa-wallet"></i></div></div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Pendapatan Bulan Ini</p>
                            <h4 class="card-title">Rp <?= number_format($pendapatan_bulan_ini, 0, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php if ($perlu_konfirmasi): ?>
        <div class="card bg-warning-gradient text-white mb-4">
            <div class="card-body">
                <h4 class="card-title text-white"><i class="fas fa-exclamation-triangle"></i> Tindakan Diperlukan!</h4>
                <p>Ada pemesanan baru yang menunggu konfirmasi pembayaran Anda.</p>
                <a href="?pembayaran=konfirmasi_pembayaran" class="btn btn-light btn-round">
                    Lihat & Konfirmasi Sekarang <span class="badge bg-danger ms-2">Baru!</span>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Grafik Pendapatan (6 Bulan Terakhir)</h4>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 300px">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-profile">
            <div class="card-header" style="background-image: url(/kost/assets/UI/Dashboards/assets/images/banner-01.jpg); background-size: cover;"></div>
            <div class="card-body">
                <div class="user-profile text-center">
                    <div class="name"><?= htmlspecialchars($_SESSION['nama_user']) ?></div>
                    <div class="job"><?= htmlspecialchars($_SESSION['username']) ?></div>
                    <div class="desc"><?= htmlspecialchars($_SESSION['role']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Kamar Tersedia Saat Ini</h4></div>
            <div class="card-body">
                <div id="kamarCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php if (!empty($kamar_untuk_slideshow)): ?>
                            <?php foreach($kamar_untuk_slideshow as $index => $kamar): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="/kost/assets/uploads/kamar/<?= htmlspecialchars($kamar['foto']) ?>" class="d-block w-100" style="height: 300px; object-fit: cover; border-radius: 5px;">
                                <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0,0,0,0.5); border-radius: 5px; padding: 0.5rem;">
                                    <h5><?= htmlspecialchars($kamar['kode_kamar']) ?></h5>
                                    <p class="mb-0">Rp <?= number_format($kamar['harga']) ?> / bulan</p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="carousel-item active">
                                <div class="d-flex align-items-center justify-content-center" style="height: 300px; background-color: #f8f9fa; border-radius: 5px;">
                                    <p class="text-muted">Tidak ada kamar kosong saat ini.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#kamarCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                    <button class="carousel-control-next" type="button" data-bs-target="#kamarCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Fasilitas Tersedia</h4></div>
            <div class="card-body">
                <div id="fasilitasCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php if (!empty($fasilitas_untuk_slideshow)): ?>
                            <?php foreach($fasilitas_untuk_slideshow as $index => $fasilitas): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="/kost/assets/uploads/fasilitas/<?= htmlspecialchars($fasilitas['foto']) ?>" class="d-block w-100" style="height: 300px; object-fit: cover; border-radius: 5px;">
                                <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0,0,0,0.5); border-radius: 5px; padding: 0.5rem;">
                                    <h5><?= htmlspecialchars($fasilitas['nama_fasilitas']) ?></h5>
                                    <p class="mb-0">+ Rp <?= number_format($fasilitas['harga']) ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                             <div class="carousel-item active">
                                <div class="d-flex align-items-center justify-content-center" style="height: 300px; background-color: #f8f9fa; border-radius: 5px;">
                                    <p class="text-muted">Tidak ada fasilitas tambahan.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#fasilitasCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                    <button class="carousel-control-next" type="button" data-bs-target="#fasilitasCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Kalender Kontrak</h4></div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Kontrak Segera Berakhir (30 Hari ke Depan)</h4></div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Penyewa</th>
                            <th>Tanggal Akhir</th>
                            <th>Sisa Hari</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($kontrak_akan_berakhir)): ?>
                            <?php foreach($kontrak_akan_berakhir as $kontrak): ?>
                            <tr>
                                <td><?= htmlspecialchars($kontrak['nama_user']) ?></td>
                                <td><?= date('d F Y', strtotime($kontrak['tanggal_akhir_kontrak'])) ?></td>
                                <td><span class="badge bg-danger"><?= $kontrak['sisa_hari'] ?> hari lagi</span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted">Tidak ada kontrak yang akan berakhir dalam 30 hari ke depan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- Inisialisasi Grafik Pendapatan ---
    const ctx = document.getElementById('incomeChart');
    if (ctx) {
        fetch('settings/UI/get_chart_data.php')
            .then(response => response.json())
            .then(data => {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Pendapatan',
                            data: data.values,
                            fill: true,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true, ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') }}},
                        plugins: { tooltip: { callbacks: { label: context => `Pendapatan: Rp ${context.parsed.y.toLocaleString('id-ID')}` }}}
                    }
                });
            })
            .catch(error => console.error('Gagal memuat data grafik:', error));
    }

    // --- Inisialisasi Kalender ---
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: 'settings/UI/get_events.php',
            eventDidMount: function(info) {
                if (info.event.extendedProps.type === 'mulai') {
                    info.el.style.backgroundColor = '#28a745';
                    info.el.style.borderColor = '#28a745';
                } else if (info.event.extendedProps.type === 'selesai') {
                    info.el.style.backgroundColor = '#dc3545';
                    info.el.style.borderColor = '#dc3545';
                }
            }
        });
        calendar.render();
    }
});
</script>