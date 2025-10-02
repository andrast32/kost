<?php
// Tampilkan semua error untuk proses debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sesuaikan path ke file koneksi Anda
require '../controller/connect.php'; 

// Jika tidak ada session, mulai session baru
if (session_status() === PHP_SESSION_NONE) {
    session_name('kost');
    session_start();
    // Untuk tes, kita set id_user admin secara manual
    $_SESSION['id_user'] = 1; 
}

// =================================================================
// BAGIAN LOGIKA PROSES (AKAN BERJALAN SAAT FORM DI-SUBMIT)
// =================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    echo "<h1>Memulai Proses Debug...</h1>";
    echo "<pre>";
    echo "Data POST yang diterima:\n";
    print_r($_POST);
    echo "</pre><hr>";

    if (isset($_POST['id_user'], $_POST['id_kamar'], $_POST['tanggal_mulai_kontrak'], $_POST['tanggal_akhir_kontrak'])) {

        $id_user = intval($_POST['id_user']);
        $id_kamar = intval($_POST['id_kamar']);
        $tanggal_mulai = $_POST['tanggal_mulai_kontrak'];
        $tanggal_akhir = $_POST['tanggal_akhir_kontrak'];
        $jumlah_fasilitas_map = $_POST['jumlah_fasilitas'] ?? [];
        $tanggal_pesan_hari_ini = date('Y-m-d');

        if (new DateTime($tanggal_akhir) <= new DateTime($tanggal_mulai)) {
            die("<h2>GAGAL: Tanggal akhir kontrak harus setelah tanggal mulai.</h2>");
        }

        $mysqli->begin_transaction();
        try {
            echo "1. Transaksi dimulai.<br>";

            $stmt_cek_pending = $mysqli->prepare("SELECT id_pemesanan FROM pemesanan WHERE id_user = ? AND status_kontrak IN ('Pending', 'Aktif')");
            $stmt_cek_pending->bind_param("i", $id_user);
            $stmt_cek_pending->execute();
            if ($stmt_cek_pending->get_result()->num_rows > 0) throw new Exception("Penyewa ini sudah memiliki kontrak aktif.");
            $stmt_cek_pending->close();
            echo "2. Pengecekan duplikat pesanan berhasil.<br>";

            $stmt_kamar = $mysqli->prepare("SELECT harga FROM kamar WHERE id_kamar = ? AND status = 'Kosong' FOR UPDATE");
            $stmt_kamar->bind_param("i", $id_kamar);
            $stmt_kamar->execute();
            $kamar_data = $stmt_kamar->get_result()->fetch_assoc();
            if (!$kamar_data) throw new Exception("Kamar tidak tersedia.");
            $harga_kamar = $kamar_data['harga'];
            $stmt_kamar->close();
            echo "3. Data kamar diambil (Harga: {$harga_kamar}).<br>";

            $biaya_bulanan_kamar = $harga_kamar;
            $biaya_tambahan_fasilitas = 0;
            $fasilitas_to_insert = [];

            if (!empty($jumlah_fasilitas_map)) {
                $selected_fasilitas_ids = [];
                foreach ($jumlah_fasilitas_map as $id => $jumlah) {
                    if (intval($jumlah) > 0) $selected_fasilitas_ids[] = intval($id);
                }
                if (!empty($selected_fasilitas_ids)) {
                    $placeholders = implode(',', array_fill(0, count($selected_fasilitas_ids), '?'));
                    $types = str_repeat('i', count($selected_fasilitas_ids));
                    $stmt_fasilitas = $mysqli->prepare("SELECT id_fasilitas, nama_fasilitas, harga, stok FROM fasilitas WHERE id_fasilitas IN ($placeholders)");
                    $stmt_fasilitas->bind_param($types, ...$selected_fasilitas_ids);
                    $stmt_fasilitas->execute();
                    $result_fasilitas = $stmt_fasilitas->get_result();
                    while ($fasilitas = $result_fasilitas->fetch_assoc()) {
                        $id_fas = $fasilitas['id_fasilitas'];
                        $jumlah_dipesan = intval($jumlah_fasilitas_map[$id_fas]);
                        if ($fasilitas['stok'] < $jumlah_dipesan) throw new Exception("Stok untuk " . htmlspecialchars($fasilitas['nama_fasilitas']) . " tidak cukup.");
                        $biaya_tambahan_fasilitas += $fasilitas['harga'] * $jumlah_dipesan;
                        $fasilitas_to_insert[] = ['id' => $id_fas, 'harga' => $fasilitas['harga'], 'jumlah' => $jumlah_dipesan];
                    }
                    $stmt_fasilitas->close();
                }
            }
            echo "4. Kalkulasi biaya fasilitas selesai.<br>";

            $biaya_bulanan_total = $biaya_bulanan_kamar + $biaya_tambahan_fasilitas;
            $date1 = new DateTime($tanggal_mulai);
            $date2 = new DateTime($tanggal_akhir);
            $bulan_sewa = ceil($date1->diff($date2)->days / 30);
            if ($bulan_sewa < 1) $bulan_sewa = 1;
            $total_biaya_keseluruhan = $biaya_bulanan_total * $bulan_sewa;
            echo "5. Perhitungan total biaya selesai.<br>";

            $stmt_pemesanan = $mysqli->prepare("INSERT INTO pemesanan (id_user, tanggal_pesan, tanggal_mulai_kontrak, tanggal_akhir_kontrak, total, biaya_bulanan, status_kontrak) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
            $stmt_pemesanan->bind_param("isssds", $id_user, $tanggal_pesan_hari_ini, $tanggal_mulai, $tanggal_akhir, $total_biaya_keseluruhan, $biaya_bulanan_total);
            if(!$stmt_pemesanan->execute()) throw new Exception("Gagal INSERT ke tabel pemesanan: " . $stmt_pemesanan->error);
            $id_pemesanan_baru = $mysqli->insert_id;
            $stmt_pemesanan->close();
            echo "6. Data utama pemesanan berhasil disimpan (ID: #{$id_pemesanan_baru}).<br>";

            $stmt_detail = $mysqli->prepare("INSERT INTO detail_pemesanan (id_pemesanan, tipe_item, id_item, jumlah, harga_saat_pesan) VALUES (?, ?, ?, ?, ?)");
            $tipe_kamar = 'kamar'; $jumlah_kamar = 1;
            $stmt_detail->bind_param("isidi", $id_pemesanan_baru, $tipe_kamar, $id_kamar, $jumlah_kamar, $harga_kamar);
            if(!$stmt_detail->execute()) throw new Exception("Gagal INSERT detail kamar: " . $stmt_detail->error);
            echo "7. Detail item 'kamar' berhasil disimpan.<br>";

            if (!empty($fasilitas_to_insert)) {
                $tipe_fasilitas = 'fasilitas';
                $stmt_stok = $mysqli->prepare("UPDATE fasilitas SET stok = stok - ? WHERE id_fasilitas = ?");
                foreach ($fasilitas_to_insert as $item) {
                    $stmt_detail->bind_param("isidi", $id_pemesanan_baru, $tipe_fasilitas, $item['id'], $item['jumlah'], $item['harga']);
                    if(!$stmt_detail->execute()) throw new Exception("Gagal INSERT detail fasilitas: " . $stmt_detail->error);
                    $stmt_stok->bind_param("ii", $item['jumlah'], $item['id']);
                    if(!$stmt_stok->execute()) throw new Exception("Gagal UPDATE stok fasilitas: " . $stmt_stok->error);
                }
                $stmt_stok->close();
                echo "8. Detail 'fasilitas' dan update stok berhasil.<br>";
            }
            $stmt_detail->close();
            
            $stmt_update_kamar = $mysqli->prepare("UPDATE kamar SET status = 'Terisi' WHERE id_kamar = ?");
            $stmt_update_kamar->bind_param("i", $id_kamar);
            if(!$stmt_update_kamar->execute()) throw new Exception("Gagal UPDATE status kamar: " . $stmt_update_kamar->error);
            $stmt_update_kamar->close();
            echo "9. Status kamar berhasil diubah.<br>";

            $mysqli->commit();
            echo "<hr><h2 style='color:green;'>BERHASIL! Transaksi telah disimpan ke database.</h2>";

        } catch (Exception $e) {
            $mysqli->rollback();
            die("<hr><h2 style='color:red;'>PROSES GAGAL:</h2><p>{$e->getMessage()}</p>");
        }
    } else {
        echo "<h2>Data POST tidak lengkap. Memuat form...</h2>";
    }
}

// =================================================================
// BAGIAN FORM TAMPILAN
// =================================================================
$users = $mysqli->query("SELECT id_user, nama_user FROM user WHERE role = 'User' AND deleted != 1 ORDER BY nama_user ASC");
$kamar_kosong = $mysqli->query("SELECT * FROM kamar WHERE status = 'Kosong' ORDER BY kode_kamar ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>TES PEMESANAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <div class="card">
        <div class="card-header bg-danger text-white"><h3>HALAMAN TES PEMESANAN</h3></div>
        <div class="card-body">
            <form action="tes_pemesanan.php" method="POST">
                <div class="mb-3">
                    <label for="id_user" class="form-label fw-bold">Pilih Penyewa</label>
                    <select class="form-select" id="id_user" name="id_user" required>
                        <option value="" disabled selected>-- Pilih --</option>
                        <?php while($user = $users->fetch_assoc()): ?>
                            <option value="<?= $user['id_user'] ?>"><?= htmlspecialchars($user['nama_user']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_kamar" class="form-label fw-bold">Pilih Kamar</label>
                    <select class="form-select" id="id_kamar" name="id_kamar" required>
                        <option value="" disabled selected>-- Pilih --</option>
                        <?php while($kamar = $kamar_kosong->fetch_assoc()): ?>
                            <option value="<?= $kamar['id_kamar'] ?>"><?= htmlspecialchars($kamar['kode_kamar']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label for="tanggal_mulai_kontrak" class="form-label">Tgl Mulai</label>
                        <input type="date" class="form-control" name="tanggal_mulai_kontrak" required>
                    </div>
                    <div class="col-6">
                        <label for="tanggal_akhir_kontrak" class="form-label">Tgl Akhir</label>
                        <input type="date" class="form-control" name="tanggal_akhir_kontrak" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">SUBMIT DATA TES</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>