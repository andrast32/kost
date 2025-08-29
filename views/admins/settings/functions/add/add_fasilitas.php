<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset(
                $_POST['kode_fasilitas'],
                $_POST['nama_fasilitas'],
                $_POST['deskripsi'],
                $_POST['harga'],
                $_POST['stok']
            )) {
                $kode_fasilitas = $_POST['kode_fasilitas'];
                $nama_fasilitas = $_POST['nama_fasilitas'];
                $deskripsi = $_POST['deskripsi'];
                $harga = $_POST['harga'];
                $stok = $_POST['stok'];

                $sl_fasilitas = bin2hex(random_bytes(32));

                $fotoDir = "../../../../../assets/uploads/fasilitas/";

                function uploadFile($file, $targetDir, $allowedExt = []) {
                    if ($file['error'] === 0) {

                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                        if (!empty($allowedExt) && !in_array($ext, $allowedExt)) {
                            return null;
                        }

                        $randomName = uniqid('fasilitas_', true) . '.' . $ext;
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

                $foto = isset($_FILES['foto']) ? uploadFile($_FILES['foto'], $fotoDir, ['jpg','jpeg','png']) : null;

                $stmt_cek = $mysqli->prepare("SELECT COUNT(*) FROM fasilitas WHERE kode_fasilitas = ? ");
                $stmt_cek->bind_param("s", $kode_fasilitas);
                $stmt_cek->execute();
                $stmt_cek->bind_result($count);
                $stmt_cek->fetch();
                $stmt_cek->close();

                if ($count > 0) {
                    $_SESSION['alert'] = [
                        'icon' => 'error',
                        'title' => 'Oops...',
                        'text' => 'Kode fasilitas sudah dipakai!'
                    ];
                    header("Location: ../../../index?fasilitas=data_fasilitas");
                    exit;

                } else {
                    $stmt = $mysqli->prepare("INSERT INTO fasilitas (kode_fasilitas, nama_fasilitas, deskripsi, harga, stok, foto, sl_fasilitas) VALUES (?,?,?,?,?,?,?) ");
                    $stmt->bind_param("ssssiss", $kode_fasilitas, $nama_fasilitas, $deskripsi, $harga, $stok, $foto, $sl_fasilitas);

                    if ($stmt->execute()) {
                        $_SESSION['alert'] = [
                            'icon' => 'success',
                            'title' => 'Berhasil!',
                            'text' => 'Data fasilitas berhasil ditambahkan!'
                        ];
                    } else {
                        $_SESSION['alert'] = [
                            'icon' => 'error',
                            'title' => 'Gagal!',
                            'text' => 'Data fasilitas gagal ditambahkan!'
                        ];
                    }
                    $stmt->close();
                }

            } else {
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Error...',
                    'text' => 'Data tidak lengkap! hubungi admin untuk memperbaiki code!'
                ];
            }
            header("Location: ../../../index?fasilitas=data_fasilitas");
            exit;
    } else {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Error...',
            'text' => 'Invalid request.'
        ];
        header("Location: ../../../index?fasilitas=data_fasilitas");
        exit;
    }

?>