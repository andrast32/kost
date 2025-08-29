<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['id_fasilitas'], $_POST['kode_fasilitas'], $_POST['nama_fasilitas'], $_POST['deskripsi'],  $_POST['harga'], $_POST['stok'])) {

            $id_fasilitas   = intval($_POST['id_fasilitas']);
            $kode_fasilitas = $_POST['kode_fasilitas'];
            $nama_fasilitas = $_POST['nama_fasilitas'];
            $deskripsi      = $_POST['deskripsi'];
            $stok           = $_POST['stok'];
            $harga          = $_POST['harga'];

            $fotoDir    = "../../../../../assets/uploads/fasilitas/";

            function uploadFile($file, $targetDir, $allowedExt = []) {
                if ($file['error'] === 0) {
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (!empty($allowedExt) && !in_array($ext, $allowedExt)) {
                        return null;
                    }
                    $randomName = uniqid('fasilitas_', true) . '.' . $ext;
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

            $query = $mysqli->prepare("SELECT * FROM fasilitas WHERE id_fasilitas = ?");
            $query->bind_param("i", $id_fasilitas);
            $query->execute();
            $result = $query->get_result();
            $old = $result->fetch_assoc();

            $foto = !empty($_FILES['foto']['name']) ? uploadFile($_FILES['foto'], $fotoDir, ['jpg','jpeg','png']) : $old['foto'];

            if (!empty($_FILES['foto']['name']) && file_exists($fotoDir . $old['foto'])) {
                unlink($fotoDir . $old['foto']);
            }

            $stmt_cek = $mysqli->prepare("SELECT COUNT(*) FROM fasilitas WHERE kode_fasilitas = ? AND id_fasilitas != ?");
            $stmt_cek->bind_param("si", $kode_fasilitas, $id_fasilitas);
            $stmt_cek->execute();
            $stmt_cek->bind_result($count);
            $stmt_cek->fetch();
            $stmt_cek->close();

            if ($count > 0) {
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Gagal...',
                    'text' => 'Kode fasilitas sudah dipakai, gunakan kode lain!'
                ];
            } else {

                $sl_fasilitas = bin2hex(random_bytes(32));

                $stmt = $mysqli->prepare("UPDATE fasilitas SET kode_fasilitas = ?, nama_fasilitas = ?, deskripsi = ?, harga = ?, sl_fasilitas = ?, stok = ?, foto = ? WHERE id_fasilitas = ?");
                $stmt->bind_param("sssssssi", $kode_fasilitas, $nama_fasilitas, $deskripsi, $harga, $sl_fasilitas, $stok, $foto, $id_fasilitas);

                if ($stmt->execute()) {
                    $_SESSION['alert'] = [
                        'icon' => 'success',
                        'title' => 'Berhasil...',
                        'text' => 'Data fasilitas berhasil di edit!'
                    ];
                } else {
                    $_SESSION['alert'] = [
                        'icon' => 'error',
                        'title' => 'Gagal...',
                        'text' => 'Data fasilitas gagal di ubah!'
                    ];
                }
                $stmt->close();
            }

        } else {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Error...',
                'text' => 'Data tidak lengkap. hubungi admin untuk memperbaiki code!'
            ];
        }
        header("Location: ../../../index?fasilitas=data_fasilitas");
        exit;
    } else {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Error...',
            'text' => 'Invalid request!'
        ];
        header("Location: ../../../index?fasilitas=data_fasilitas");
        exit;
    }

?>