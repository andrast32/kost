<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset(
                $_POST['id_user'], 
                $_POST['alamat'], 
                $_POST['jk'], 
                $_POST['no_hp']
            )) {

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
                        $destination = $targetDir . $randomName;

                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }

                        if (move_uploaded_file($file['tmp_name'], $destination)) {
                            return $randomName;
                        }

                    }
                    return null;
                }

                $foto         = isset($_FILES['foto'])        ? uploadFile($_FILES['foto'], $fotoDir, ['jpg','jpeg','png']) : null;
                $scan_kk      = isset($_FILES['scan_kk'])     ? uploadFile($_FILES['scan_kk'], $kkDir, ['pdf'])              : null;
                $scan_ktp     = isset($_FILES['scan_ktp'])    ? uploadFile($_FILES['scan_ktp'], $ktpDir, ['pdf'])            : null;
                $bukti_nikah  = isset($_FILES['bukti_nikah']) ? uploadFile($_FILES['bukti_nikah'], $nikahDir, ['pdf'])       : null;

                if ($foto) {
                    $stmt = $mysqli->prepare("INSERT INTO biodata (id_user, alamat, jk, no_hp, foto, scan_kk, scan_ktp, bukti_nikah) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isssssss", $id_user, $alamat, $jk, $no_hp, $foto, $scan_kk, $scan_ktp, $bukti_nikah);

                    if ($stmt->execute()) {
                        $_SESSION['alert'] = [
                            'icon' => 'success',
                            'title' => 'Berhasil!',
                            'text' => 'Data biodata berhasil ditambahkan!'
                        ];
                    } else {
                        $_SESSION['alert'] = [
                            'icon' => 'error',
                            'title' => 'Gagal!',
                            'text' => 'Gagal menyimpan ke database.'
                        ];
                    }
                } else {
                    $_SESSION['alert'] = [
                        'icon' => 'error',
                        'title' => 'Upload Gagal!',
                        'text' => 'Foto wajib diunggah.'
                    ];
                }

                header("Location: ../../../index?penyewa=biodata_user&id_user=$id_user");
                exit;
            }
    } else {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Error...',
            'text' => 'Data tidak lengkap! Hubungi admin.'
        ];
        header("Location: ../../../index?penyewa=biodata_user&id_user=$id_user");
        exit;
    }

?>