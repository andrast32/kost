# 🏠 The Kost - Sistem Manajemen Kost Modern

The Kost adalah aplikasi web berbasis **PHP** dan **MySQL** yang dirancang khusus untuk menyederhanakan dan memodernisasi manajemen usaha rumah kost.  
Proyek ini berfokus pada **kemudahan penggunaan bagi admin, keamanan data, dan fleksibilitas** dalam menangani berbagai skenario penyewaan, mulai dari pemesanan, pembayaran, hingga pelaporan.

Aplikasi ini mengubah proses manual yang rumit menjadi **alur kerja digital yang terstruktur, efisien, dan elegan**.  
Dari manajemen data penyewa dan kamar, hingga proses pemesanan interaktif ala **e-commerce**, sistem ini menyediakan semua alat yang dibutuhkan oleh seorang manajer atau pemilik kost untuk mengelola propertinya dengan lebih baik.

---

## 📜 Daftar Isi

-   [Tentang Proyek](#tentang-proyek)
    -   [Latar Belakang](#latar-belakang)
    -   [Tujuan Proyek](#tujuan-proyek)
-   [✨ Fitur Utama](#-fitur-utama)
-   [💻 Teknologi yang Digunakan](#-teknologi-yang-digunakan)
-   [🗄️ Struktur Database](#️-struktur-database)
    -   [Diagram Relasi Entitas (ERD)](#diagram-relasi-entitas-erd)
    -   [Detail Setiap Tabel](#detail-setiap-tabel)
-   [🚀 Instalasi dan Konfigurasi](#-instalasi-dan-konfigurasi)
    -   [Prasyarat](#prasyarat)
    -   [Langkah-langkah Instalasi](#langkah-langkah-instalasi)
-   [📁 Struktur Folder Proyek](#-struktur-folder-proyek)
-   [🔎 Panduan Fitur Mendalam](#-panduan-fitur-mendalam)
    -   [Manajemen Data Master](#manajemen-data-master)
    -   [Alur Pemesanan (Gaya E-commerce)](#alur-pemesanan-gaya-e-commerce)
    -   [Alur Pembayaran dan Konfirmasi](#alur-pembayaran-dan-konfirmasi)
    -   [Sistem Pelaporan Bulanan](#sistem-pelaporan-bulanan)
    -   [Proses Check-Out Otomatis](#proses-check-out-otomatis)
-   [🔒 Filosofi Kode dan Keamanan](#-filosofi-kode-dan-keamanan)
-   [🗺️ Rencana Pengembangan](#️-rencana-pengembangan)
-   [⚖️ Lisensi](#️-lisensi)

---

## 📖 Tentang Proyek

### Latar Belakang

Manajemen kost secara tradisional melibatkan pencatatan manual, komunikasi terpisah, dan administrasi yang memakan waktu. Semua proses ini rentan terhadap **human error** dan tidak efisien seiring bertambahnya jumlah kamar serta penyewa.  
Proyek **The Kost** hadir untuk mengatasi masalah ini dengan menyediakan **platform digital terintegrasi** yang mencakup seluruh aspek manajemen kost.

### Tujuan Proyek

-   **Sentralisasi Data**: Semua data (penyewa, kamar, fasilitas, pemesanan, pembayaran) terkumpul dalam satu database.
-   **Otomatisasi Proses**: Status kamar, stok fasilitas, dan laporan keuangan diperbarui otomatis.
-   **Meningkatkan Efisiensi**: Antarmuka cepat dan intuitif untuk kontrak sewa, pembayaran, dan konfirmasi transaksi.
-   **Keamanan & Integritas Data**: Menggunakan **prepared statements, output escaping, transaksi database**.
-   **Laporan Akurat**: Ekspor laporan ke **Excel** dan **PDF** untuk analisis bisnis.

---

## ✨ Fitur Utama

-   🔐 **Manajemen Autentikasi**: Login aman dengan peran berbeda (Admin & User).
-   📦 **CRUD Data Master**: Kamar, Fasilitas, Penyewa, Biodata Penyewa.
-   🛒 **Sistem Pemesanan Interaktif** ala e-commerce.
-   💳 **Manajemen Pembayaran & Konfirmasi** dengan status real-time.
-   📊 **Pelaporan Bulanan** dengan opsi ekspor.
-   ⏳ **Check-Out Otomatis** saat kontrak berakhir.

---

## 💻 Teknologi yang Digunakan

-   **Backend**: PHP 8.1+
-   **Database**: MySQL / MariaDB
-   **Frontend**:
    -   HTML5, CSS3, Bootstrap 5.3
    -   JavaScript (ES6+)
-   **Library JS**: SweetAlert2, html2pdf.js

---

## 🗄️ Struktur Database

### Diagram Relasi Entitas (ERD)

```

[ User ] ---< [ Biodata ]
|
+-----< [ Pemesanan ] ---< [ Detail_Pemesanan ]
|            |
|            +---< [ Pembayaran ]
|
[ Kamar ] <---> [ Detail_Pemesanan ]
[ Fasilitas ] <---> [ Detail_Pemesanan ]

[ Pengaturan ] (Tabel utilitas)

```

### Detail Setiap Tabel

📌 **User**, **Biodata**, **Kamar**, **Fasilitas**, **Pemesanan**, **Detail_Pemesanan**, **Pembayaran**, **Pengaturan**  
(Tabel lengkap beserta kolom & tipe data sudah dijelaskan di atas).

---

## 🚀 Instalasi dan Konfigurasi

### Prasyarat

-   Server Web (XAMPP, Laragon, WAMP, dll.)
-   PHP 8.1+
-   MySQL/MariaDB
-   Composer (opsional)

### Langkah-langkah Instalasi

1. Clone/Unduh proyek ke folder web server (`/kost/`).
2. Buat database baru `kost` di phpMyAdmin.
3. Import file `.sql` ke database.
4. Sesuaikan koneksi database di `controller/connect.php`:

```php
$host = 'localhost';
$user = 'root';
$pass = ''; // kosongkan jika tanpa password
$dbname = 'kost';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}
```

5. Akses via browser: [http://localhost/kost/](http://localhost/kost/)

---

## 📁 Struktur Folder Proyek

```
/kost/
├── assets/        # CSS, JS, Gambar, Font
├── controller/    # Logika aplikasi (koneksi, autentikasi, dll)
├── views/         # File tampilan
│   ├── admins/    # Tampilan khusus Admin
```

---

## 🔎 Panduan Fitur Mendalam

### Manajemen Data Master

CRUD untuk kamar, fasilitas, penyewa dengan **SweetAlert2** konfirmasi.

### Alur Pemesanan (E-commerce Style)

-   Pilih kamar → Panel detail muncul.
-   Pilih penyewa dari dropdown.
-   Tambah fasilitas (add-ons).
-   Kalkulasi biaya real-time.
-   Submit → data tersimpan ke `add_pemesanan.php`.

### Alur Pembayaran & Konfirmasi

-   Kontrak dibuat (status `Pending`).
-   Admin konfirmasi → status `Aktif` atau `Dibatalkan`.
-   Riwayat pembayaran tersimpan di tabel `pembayaran`.

### Sistem Pelaporan Bulanan

-   Arsip laporan per bulan/tahun.
-   Detail laporan dapat dicetak/ekspor PDF & Excel.

### Proses Check-Out Otomatis

-   Skrip `cek_checkout.php` memastikan kontrak yang berakhir otomatis diselesaikan.
-   Status kamar kembali `Kosong`, stok fasilitas dikembalikan.

---

## 🔒 Filosofi Kode dan Keamanan

-   **SQL Injection & XSS** → gunakan prepared statements & `htmlspecialchars()`.
-   **Transaksi Database** → semua proses kritis dibungkus dalam `begin_transaction`.
-   **UX Modern** → interaktif tanpa reload, SweetAlert2 untuk notifikasi elegan.

---

## 🗺️ Rencana Pengembangan

-   Portal Penyewa (akses tagihan, riwayat pembayaran).
-   Integrasi Payment Gateway (Midtrans/Xendit).
-   Modul Manajemen Pengeluaran (listrik, air, dll).
-   Sistem Notifikasi (Email/WhatsApp).
-   Dashboard Statistik (okupansi, pendapatan, dsb).

---

## ⚖️ Lisensi

Proyek ini dilisensikan di bawah **MIT License**.
Silakan gunakan, modifikasi, dan kembangkan sesuai kebutuhan.

---
