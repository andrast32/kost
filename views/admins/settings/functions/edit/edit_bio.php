<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['id_biodata'], $_POST['id_user'], $_POST['alamat'], $_POST['jk'], $_POST['no_hp'])) {

            $id_biodata = intval($_POST['id_biodata']);
            $id_user    = intval($_POST['id_user']);
            $alamat     = $_POST['alamat'];
            $jk         = $_POST['jk'];
            $no_hp      = $_POST['no_hp'];

            $fotoDir    = "../../../../../assets/uploads/biodata/foto/";
            $kkDir      = "../../../../../assets/uploads/biodata/kk/";
            $ktpDir     = "../../../../../assets/uploads/biodata/ktp/";
            $nikahDir   = "../../../../../assets/uploads/biodata/bukti_nikah/";

            function uploadFile($file, $targetDir, $allowedExt = []) {
                if ($file['error'] === 0) {
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (!empty($allowedExt) && !in_array($ext, $allowedExt)) {
                        return null;
                    }
                    $randomName = uniqid('file_', true) . '.' . $ext;
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }
                    $destination = $targetDir . $randomName;
                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        return $randomName;
                    }
                }
                return null;
            }

            $query = $mysqli->prepare("SELECT * FROM biodata WHERE id_biodata = ?");
            $query->bind_param("i", $id_biodata);
            $query->execute();
            $result = $query->get_result();
            $old = $result->fetch_assoc();

            $foto         = !empty($_FILES['foto']['name'])        ? uploadFile($_FILES['foto'], $fotoDir, ['jpg','jpeg','png']) : $old['foto'];
            $scan_kk      = !empty($_FILES['scan_kk']['name'])     ? uploadFile($_FILES['scan_kk'], $kkDir, ['pdf'])              : $old['scan_kk'];
            $scan_ktp     = !empty($_FILES['scan_ktp']['name'])    ? uploadFile($_FILES['scan_ktp'], $ktpDir, ['pdf'])            : $old['scan_ktp'];
            $bukti_nikah  = !empty($_FILES['bukti_nikah']['name']) ? uploadFile($_FILES['bukti_nikah'], $nikahDir, ['pdf'])       : $old['bukti_nikah'];

            if (!empty($_FILES['foto']['name']) && file_exists($fotoDir . $old['foto'])) {
                unlink($fotoDir . $old['foto']);
            }
            if (!empty($_FILES['scan_kk']['name']) && file_exists($kkDir . $old['scan_kk'])) {
                unlink($kkDir . $old['scan_kk']);
            }
            if (!empty($_FILES['scan_ktp']['name']) && file_exists($ktpDir . $old['scan_ktp'])) {
                unlink($ktpDir . $old['scan_ktp']);
            }
            if (!empty($_FILES['bukti_nikah']['name']) && file_exists($nikahDir . $old['bukti_nikah'])) {
                unlink($nikahDir . $old['bukti_nikah']);
            }

            $stmt = $mysqli->prepare("UPDATE biodata SET alamat=?, jk=?, no_hp=?, foto=?, scan_kk=?, scan_ktp=?, bukti_nikah=? WHERE id_biodata=?");
            $stmt->bind_param("sssssssi", $alamat, $jk, $no_hp, $foto, $scan_kk, $scan_ktp, $bukti_nikah, $id_biodata);

            if ($stmt->execute()) {
                $_SESSION['alert'] = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Data biodata berhasil diubah!'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Gagal menyimpan perubahan.'
                ];
            }

            header("Location: ../../../index?penyewa=biodata_user&id_user=$id_user");
            exit;
        }
    }

    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Error...',
        'text' => 'Data tidak lengkap! Hubungi admin.'
    ];
    header("Location: ../../../index?penyewa=biodata_user&id_user=$id_user");
    exit;

?>