# The Kost - Sistem Manajemen Kost Modern

The Kost adalah aplikasi web berbasis PHP dan MySQL yang dirancang khusus untuk menyederhanakan dan memodernisasi manajemen usaha rumah kost. Proyek ini dibangun dengan fokus pada kemudahan penggunaan bagi admin, keamanan data, dan fleksibilitas dalam menangani berbagai skenario penyewaan, mulai dari pemesanan, pembayaran, hingga pelaporan.

Aplikasi ini mengubah proses manual yang rumit menjadi alur kerja digital yang terstruktur, efisien, dan elegan. Dari manajemen data penyewa dan kamar, hingga proses pemesanan yang interaktif seperti e-commerce, sistem ini menyediakan semua alat yang dibutuhkan oleh seorang manajer atau pemilik kost untuk mengelola propertinya dengan lebih baik.

## 📜 Daftar Isi

-   [Tentang Proyek](#tentang-proyek)
-   [Latar Belakang](#latar-belakang)
-   [Tujuan Proyek](#tujuan-proyek)
-   [Fitur Utama](#fitur-utama)
-   [Teknologi yang Digunakan](#teknologi-yang-digunakan)
-   [Struktur Database](#struktur-database)
-   [Diagram Relasi Entitas (ERD)](#diagram-relasi-entitas-erd)
-   [Detail Setiap Tabel](#detail-setiap-tabel)
-   [Instalasi dan Konfigurasi](#instalasi-dan-konfigurasi)
-   [Prasyarat](#prasyarat)
-   [Langkah-langkah Instalasi](#langkah-langkah-instalasi)
-   [Struktur Folder Proyek](#struktur-folder-proyek)
-   [Panduan Fitur Mendalam](#panduan-fitur-mendalam)
-   [Manajemen Data Master](#manajemen-data-master)
-   [Alur Pemesanan (Gaya E-commerce)](#alur-pemesanan-gaya-e-commerce)
-   [Alur Pembayaran dan Konfirmasi](#alur-pembayaran-dan-konfirmasi)
-   [Sistem Pelaporan Bulanan](#sistem-pelaporan-bulanan)
-   [Proses Check-Out Otomatis](#proses-check-out-otomatis)
-   [Filosofi Kode dan Keamanan](#filosofi-kode-dan-keamanan)
-   [Keamanan Data (SQL Injection & XSS)](#keamanan-data-sql-injection--xss)
-   [Integritas Data (Transaksi Database)](#integritas-data-transaksi-database)
-   [Pengalaman Pengguna (UX)](#pengalaman-pengguna-ux)
-   [Rencana Pengembangan](#rencana-pengembangan)
-   [Lisensi](#lisensi)

## 📖 Tentang Proyek

### Latar Belakang

Manajemen rumah kost secara tradisional seringkali melibatkan banyak pencatatan manual, komunikasi yang terfragmentasi, dan proses administrasi yang memakan waktu. Mulai dari melacak ketersediaan kamar, mencatat data penyewa, mengelola fasilitas tambahan, hingga merekapitulasi pembayaran bulanan, semua proses ini rentan terhadap kesalahan manusia (human error) dan menjadi tidak efisien seiring bertambahnya jumlah kamar dan penyewa. Kurangnya sistem yang terpusat membuat pemilik atau manajer kost kesulitan mendapatkan gambaran umum tentang kondisi bisnis mereka secara cepat dan akurat. Proyek "The Kost" lahir dari kebutuhan untuk mengatasi tantangan-tantangan ini dengan menyediakan sebuah platform digital tunggal yang mengintegrasikan semua aspek manajemen kost.

### Tujuan Proyek

Tujuan utama dari proyek "The Kost" adalah untuk menciptakan sebuah sistem informasi manajemen yang kuat, aman, dan ramah pengguna dengan target utama sebagai berikut:

Sentralisasi Data: Mengumpulkan semua data penting—penyewa, kamar, fasilitas, pemesanan, dan pembayaran—ke dalam satu database yang terstruktur, menghilangkan kebutuhan akan spreadsheet atau buku catatan fisik yang tersebar.

Otomatisasi Proses: Mengotomatiskan tugas-tugas rutin seperti mengubah status kamar setelah dipesan, mengembalikan stok fasilitas setelah kontrak selesai, dan menghasilkan laporan keuangan, sehingga mengurangi beban kerja admin.

Meningkatkan Efisiensi: Menyediakan antarmuka yang cepat dan intuitif untuk tugas-tugas krusial seperti membuat kontrak sewa baru, memproses pembayaran, dan mengonfirmasi transaksi, sehingga admin dapat bekerja lebih cepat dan efektif.

Menjamin Keamanan dan Integritas Data: Menerapkan praktik-praktik keamanan standar industri (seperti prepared statements dan output escaping) dan logika bisnis yang kokoh (seperti transaksi database) untuk memastikan data tidak hanya aman dari serangan tetapi juga konsisten dan andal.

Menyediakan Laporan yang Akurat: Memberikan kemudahan bagi admin untuk melihat, menyaring, dan mengekspor data riwayat pembayaran dalam berbagai format (PDF, Excel) untuk keperluan analisis bisnis dan akuntansi.

✨ Fitur Utama
Manajemen Autentikasi: Sistem login yang aman untuk Admin dan User dengan peran yang berbeda.

Manajemen Data Master (CRUD): Fungsionalitas Create, Read, Update, Delete yang lengkap untuk:

Data Kamar (termasuk foto, harga, status, dll.)

Data Fasilitas (termasuk stok, harga, foto, dll.)

Data Penyewa (User)

Data Biografi Penyewa

Sistem Pemesanan Interaktif: Halaman pembuatan kontrak sewa dengan antarmuka modern gaya e-commerce:

Tampilan daftar kamar yang visual dengan foto.

Panel detail pemesanan yang dinamis, muncul saat kamar dipilih.

Keranjang belanja (shopping cart) untuk menambahkan fasilitas (add-ons).

Input jumlah untuk setiap fasilitas dengan tombol +/- yang interaktif.

Kalkulasi biaya bulanan dan total biaya keseluruhan secara real-time menggunakan JavaScript.

Manajemen Pembayaran & Konfirmasi:

Alur kerja yang jelas untuk pemesanan Pending (menunggu pembayaran).

Halaman khusus untuk admin mengonfirmasi pembayaran yang masuk.

Kemampuan untuk menerima atau menolak pembayaran.

Sistem Pelaporan Cerdas:

Halaman arsip laporan yang mengelompokkan riwayat pembayaran berdasarkan bulan dan tahun.

Halaman detail laporan per periode yang rapi dan siap cetak.

Tombol ekspor laporan manual ke format Excel (.xls) dan PDF.

Proses Check-Out Otomatis:

Skrip "Poor Man's Cron Job" yang secara otomatis memeriksa dan menyelesaikan kontrak yang telah berakhir.

Mengubah status kamar menjadi 'Kosong' dan mengembalikan stok fasilitas secara otomatis.

💻 Teknologi yang Digunakan
Backend: PHP 8.1+

Database: MySQL / MariaDB

Frontend:

HTML5

CSS3

Bootstrap 5.3 (untuk layout dan komponen UI)

JavaScript (ES6+) untuk interaktivitas dinamis

Library JavaScript:

SweetAlert2: Untuk notifikasi dan pesan yang modern.

html2pdf.js: Untuk fungsionalitas download laporan PDF di sisi klien.

🗄️ Struktur Database
Struktur database adalah jantung dari aplikasi ini, dirancang untuk menjadi fleksibel dan normal. Desain ini menggunakan model "invoice" terpusat, di mana pemesanan bertindak sebagai kontrak utama dan detail_pemesanan mencatat semua item yang terkait.

Diagram Relasi Entitas (ERD)
[ User ] ---< [ Biodata ]
|
+-----< [ Pemesanan ] ---< [ Detail_Pemesanan ]
|
+-----------< [ Pembayaran ]

[ Kamar ] <- - - (Logika PHP) - - -> [ Detail_Pemesanan ]
[ Fasilitas ] <- - - (Logika PHP) - - -> [ Detail_Pemesanan ]

[ Pengaturan ] (Tabel utilitas)
Relasi padat (---<) adalah Foreign Key fisik. Relasi putus-putus (<- - -) adalah relasi logis yang dikelola oleh aplikasi.

Detail Setiap Tabel
user
Menyimpan data login dan informasi dasar pengguna.
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_user | BIGINT (PK) | ID unik untuk setiap user. |
| username | VARCHAR(255) | Username untuk login (unik). |
| password | VARCHAR(255) | Password yang sudah di-hash. |
| nama_user | TEXT | Nama lengkap user. |
| role | ENUM('Admin','User') | Peran pengguna dalam sistem. |
| deleted | TINYINT(1) | Penanda soft delete (0=aktif, 1=dihapus). |
| session_token| VARCHAR(255) | Token untuk validasi sesi dan mencegah login ganda. |
| sl_user | TEXT | ID unik tambahan (slug/salt) untuk URL. |

biodata
Menyimpan data pribadi detail dari penyewa.
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_biodata | BIGINT (PK) | ID unik untuk biodata. |
| id_user | BIGINT (FK) | Terhubung ke tabel user. |
| alamat | TEXT | Alamat lengkap penyewa. |
| jk | ENUM('Laki-Laki','Perempuan')| Jenis kelamin. |
| no_hp | VARCHAR(50) | Nomor telepon aktif. |
| foto | VARCHAR(255) | Nama file foto profil. |
| scan_kk | VARCHAR(255) | Nama file scan Kartu Keluarga. |
| scan_ktp | VARCHAR(255) | Nama file scan KTP. |
| bukti_nikah| VARCHAR(255) | Nama file scan bukti nikah. |

kamar
Menyimpan data semua unit kamar yang tersedia.
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_kamar | BIGINT (PK) | ID unik untuk kamar. |
| kode_kamar | VARCHAR(50) | Kode unik untuk kamar (misal: A101). |
| harga | DECIMAL(12,2) | Harga sewa per bulan. |
| deskripsi | TEXT | Deskripsi detail tentang kamar. |
| status | ENUM('Kosong','Terisi') | Status ketersediaan kamar. |
| khusus | ENUM('Laki-Laki','Perempuan') | Peruntukan kamar. |
| foto | VARCHAR(255) | Nama file foto utama kamar. |

fasilitas
Menyimpan data fasilitas tambahan yang bisa disewa.
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_fasilitas | BIGINT (PK) | ID unik untuk fasilitas. |
| nama_fasilitas | VARCHAR(100) | Nama fasilitas (misal: Kasur Tambahan). |
| deskripsi | TEXT | Deskripsi fasilitas. |
| harga | DECIMAL(12,2) | Harga sewa fasilitas (bisa per bulan atau sekali bayar). |
| stok | INT | Jumlah stok yang tersedia. |
| foto | VARCHAR(255) | Nama file foto fasilitas. |
| deleted | TINYINT(1) | Penanda soft delete. |

pemesanan (Tabel Kontrak/Invoice)
Tabel induk yang mencatat setiap transaksi sewa.
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_pemesanan | BIGINT (PK) | ID unik untuk setiap kontrak. |
| id_user | BIGINT (FK) | Penyewa yang membuat kontrak. |
| tanggal_pesan| DATE | Tanggal saat kontrak dibuat. |
| tanggal_mulai_kontrak | DATE | Tanggal mulai sewa. |
| tanggal_akhir_kontrak | DATE | Tanggal akhir sewa. |
| total | DECIMAL(12,2) | Total biaya keseluruhan selama periode kontrak. |
| biaya_bulanan| DECIMAL(12,2) | Total tagihan per bulan (kamar + fasilitas). |
| status_kontrak| ENUM(...) | Status kontrak: 'Pending', 'Aktif', 'Selesai', 'Dibatalkan'. |

detail_pemesanan (Tabel "Keranjang")
Tabel detail yang mencatat semua item dalam satu kontrak.
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_detail | BIGINT (PK) | ID unik. |
| id_pemesanan| BIGINT (FK) | Terhubung ke tabel pemesanan. |
| tipe_item | ENUM('kamar','fasilitas') | Menentukan jenis item. |
| id_item | BIGINT | Merujuk ke id_kamar atau id_fasilitas. |
| jumlah | INT | Jumlah item yang dipesan. |
| harga_saat_pesan | DECIMAL(10,2) | "Mengunci" harga item saat transaksi. |

pembayaran
Mencatat semua riwayat pembayaran yang masuk.
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_pembayaran| BIGINT (PK) | ID unik untuk setiap pembayaran. |
| id_pemesanan | BIGINT (FK) | Terhubung ke tabel pemesanan. |
| tanggal_bayar| DATE | Tanggal saat pembayaran dilakukan. |
| jumlah_bayar | DECIMAL(12,2) | Jumlah uang yang dibayarkan. |
| bukti_transaksi| VARCHAR(255) | Nama file bukti transfer. |
| status | ENUM(...) | Status pembayaran: 'Belum Dibayar', 'Menunggu Konfirmasi', 'Lunas'. |
| dikonfirmasi_oleh| BIGINT (FK)| ID admin yang mengonfirmasi pembayaran. |

pengaturan
Tabel utilitas untuk menyimpan pengaturan sistem.
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_pengaturan | INT (PK) | ID unik. |
| nama_pengaturan | VARCHAR(50) | Nama pengaturan (misal: 'terakhir_cek_checkout'). |
| nilai_pengaturan | DATETIME | Nilai dari pengaturan tersebut. |

🚀 Instalasi dan Konfigurasi
Prasyarat
Server web lokal (Laragon, XAMPP, WAMP) atau hosting.

PHP versi 8.1 atau lebih baru.

Database MySQL atau MariaDB.

Composer (untuk instalasi library di masa depan).

Langkah-langkah Instalasi
Unduh atau Clone Proyek: Letakkan semua file proyek di dalam direktori web server Anda (misal: C:/laragon/www/kost/).

Buat Database: Buat database baru di phpMyAdmin dengan nama kost.

Impor Database: Impor file .sql yang berisi struktur dan data awal ke dalam database kost Anda.

Konfigurasi Koneksi: Buka file controller/connect.php dan sesuaikan detail koneksi database ($host, $user, $pass, $dbname) dengan konfigurasi server Anda.

PHP

// controller/connect.php
$host = 'localhost';
$user = 'root';
$pass = ''; // Kosongkan jika tidak ada password
$dbname = 'kost';

$mysqli = new mysqli($host, $user, $pass, $dbname);

if ($mysqli->connect_error) {
die("Koneksi gagal: " . $mysqli->connect_error);
}
Akses Aplikasi: Buka browser dan akses proyek Anda melalui http://localhost/kost/.

📁 Struktur Folder Proyek
/kost/
├── assets/ # Aset publik (CSS, JS, gambar, font)
├── controller/ # Logika inti aplikasi (koneksi, autentikasi, dll)
├── views/ # Semua file tampilan
│ ├── admins/ # Tampilan khusus Admin

#### Contoh Koneksi PHP

Mengubah Data: Melalui tombol "Edit" di setiap baris data yang juga menggunakan form modal.

Menghapus Data: Melalui tombol "Hapus" dengan konfirmasi SweetAlert untuk mencegah kesalahan.

Alur Pemesanan (Gaya E-commerce)
Fitur ini terdapat pada file views/admins/pages/data_pemesanan.php dengan ?action=tambah. Alurnya didesain agar intuitif dan cepat.

Pilih Kamar: Halaman awalnya menampilkan galeri kamar yang tersedia. Admin mengklik salah satu kamar untuk memulai proses.

Tampilan Detail: Setelah kamar dipilih, tampilan berubah menjadi panel pemesanan detail tanpa reload halaman.

Pilih Penyewa: Admin memilih penyewa dari dropdown yang secara otomatis hanya menampilkan user yang belum memiliki kontrak aktif.

Pilih Fasilitas (Add-ons): Admin bisa membuka dropdown "Tambah Fasilitas" yang menampilkan daftar add-ons lengkap dengan gambar, harga, stok, dan input jumlah (+/-).

Kalkulasi Real-time: Setiap kali fasilitas ditambahkan/dihapus atau jumlahnya diubah, JavaScript akan secara otomatis menghitung ulang "Biaya Bulanan". Saat tanggal kontrak diisi, "Estimasi Total Biaya" juga akan terhitung.

Submit Kontrak: Data yang dikirim ke settings/functions/add/add_pemesanan.php adalah id_user, id_kamar, tanggal, dan array fasilitas[] yang digenerate oleh JavaScript berdasarkan jumlah yang diinput.

Alur Pembayaran dan Konfirmasi
Sistem ini memisahkan antara kontrak sewa (pemesanan) dan transaksi uang (pembayaran).

Kontrak Dibuat: Setelah admin membuat pesanan, sebuah baris baru dibuat di tabel pemesanan dengan status_kontrak = 'Pending'.

Konfirmasi: Di halaman riwayat pembayaran atau dashboard, jika ada pesanan 'Pending', akan muncul tombol "Konfirmasi Pembayaran".

Proses Konfirmasi: Admin mengklik tombol tersebut untuk menuju halaman konfirmasi_pembayaran.php yang menampilkan daftar pesanan 'Pending'.

Terima/Tolak:

Jika Diterima: Admin menekan tombol "Terima", yang akan memunculkan modal untuk mencatat detail pembayaran. Setelah disubmit, skrip proses_konfirmasi.php akan:

Membuat baris baru di tabel pembayaran dengan status = 'Lunas'.

Mengubah status_kontrak di tabel pemesanan menjadi 'Aktif'.

Jika Ditolak: Skrip akan mengubah status_kontrak menjadi 'Dibatalkan' dan mengembalikan semua sumber daya (kamar menjadi 'Kosong', stok fasilitas bertambah).

Sistem Pelaporan Bulanan
Fitur ini dirancang untuk rekapitulasi yang mudah, terbagi menjadi dua level.

Halaman Arsip: File utama (laporan_pembayaran.php) akan secara otomatis mendeteksi dan mengelompokkan semua pembayaran berdasarkan Bulan dan Tahun, menampilkannya sebagai "kartu arsip" yang bisa diklik.

Halaman Detail: Saat kartu arsip diklik, pengguna diarahkan ke halaman yang sama dengan parameter ?bulan=...&tahun=.... Halaman ini akan menampilkan tabel detail pembayaran hanya untuk periode yang dipilih dan menyediakan tombol untuk mencetak atau menyimpan sebagai PDF.

Proses Check-Out Otomatis
Untuk menjaga konsistensi data, sebuah skrip bernama cek_checkout.php dirancang untuk dijalankan secara berkala.

Pemicu: Skrip ini "dititipkan" di halaman utama admin (index.php). Ia hanya akan berjalan jika sudah lewat periode waktu tertentu (misal, 12 jam) sejak terakhir kali berjalan.

Proses:

Mencari semua kontrak di tabel pemesanan yang status_kontrak-nya 'Aktif' dan tanggal_akhir_kontrak-nya sudah lewat.

Untuk setiap kontrak yang ditemukan, ia akan:

Mengubah status_kontrak menjadi 'Selesai'.

Mencari item kamar di detail_pemesanan dan mengubah status kamar terkait menjadi 'Kosong'.

Mencari item fasilitas di detail_pemesanan dan mengembalikan stok ke tabel fasilitas.

Semua proses ini dilakukan dalam transaksi database untuk setiap kontrak, memastikan tidak ada data yang setengah jalan.

🔬 Filosofi Kode dan Keamanan
Keamanan Data (SQL Injection & XSS): Setiap interaksi dengan database yang melibatkan input dari pengguna wajib menggunakan prepared statements (prepare, bind_param, execute) untuk mencegah SQL Injection. Setiap data yang dicetak ke HTML wajib dibungkus dengan htmlspecialchars() untuk mencegah Cross-Site Scripting (XSS).

Integritas Data (Transaksi Database): Untuk operasi kompleks yang melibatkan perubahan pada beberapa tabel sekaligus (seperti membuat pesanan atau konfirmasi pembayaran), prosesnya dibungkus dalam transaksi database (begin_transaction, commit, rollback). Ini menjamin bahwa semua langkah berhasil, atau jika satu saja gagal, semua perubahan akan dibatalkan. Ini mencegah adanya "data sampah" atau data yang tidak konsisten.

Pengalaman Pengguna (UX): Interaksi yang sering dilakukan, seperti pencarian atau kalkulasi, diusahakan terjadi di sisi klien menggunakan JavaScript untuk memberikan respons yang instan tanpa perlu me-reload halaman. Notifikasi menggunakan SweetAlert2 untuk memberikan umpan balik yang elegan dan tidak mengganggu alur kerja pengguna.

🗺️ Rencana Pengembangan
Proyek ini memiliki fondasi yang kuat dan dapat dikembangkan lebih lanjut dengan fitur-fitur berikut:

Portal Penyewa (User): Membuat antarmuka khusus untuk penyewa agar bisa melihat detail tagihan, riwayat pembayaran, dan mungkin mengajukan keluhan atau permintaan perbaikan.

Integrasi Payment Gateway: Menghubungkan sistem dengan payment gateway (seperti Midtrans atau Xendit) agar penyewa bisa membayar tagihan secara online dan proses konfirmasi bisa berjalan otomatis.

Manajemen Pengeluaran: Menambahkan modul untuk mencatat pengeluaran operasional kost (listrik, air, perbaikan, dll.) untuk laporan laba-rugi yang lebih lengkap.

Sistem Notifikasi: Mengirim notifikasi otomatis melalui email atau WhatsApp kepada penyewa saat tagihan baru muncul atau mendekati jatuh tempo.

Dashboard Statistik: Membuat dashboard utama yang lebih kaya dengan grafik dan statistik, seperti tingkat hunian (okupansi), pendapatan per bulan, dan fasilitas paling populer.

⚖️ Lisensi
Proyek ini dilisensikan di bawah Lisensi MIT.
