<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset(
                $_POST['kode'],
                $_POST['harga'],
                $_POST['status'],
                $_POST['khusus']
            )){

                $kode   = $_POST['kode'];
                $harga  = $_POST['harga'];
                $status = $_POST['status'];
                $khusus = $_POST['khusus'];

                $sl_kamar = bin2hex(random_bytes(32));

                $fotoDir    = "../../../../../assets/uploads/kamar/";

                function uploadFile($file, $targetDir, $allowedExt = []) {
                    if ($file['error'] === 0) {

                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                        if (!empty($allowedExt) && !in_array($ext, $allowedExt)) {
                            return null;
                        }

                        $randomName = uniqid('kamar_', true) . '.' . $ext;
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

                $stmt_cek = $mysqli->prepare("SELECT COUNT(*) FROM kamar WHERE kode = ? ");
                $stmt_cek->bind_param("s", $kode);
                $stmt_cek->execute();
                $stmt_cek->bind_result($count);
                $stmt_cek->fetch();
                $stmt_cek->close();

                if ($count > 0) {
                    $_SESSION['alert'] = [
                        'icon' => 'error',
                        'title' => 'Gagal!',
                        'text' => 'Kamar dengan kode tersebut sudah ada.'
                    ];
                    header("Location: ../../../index?kamar=data_kamar");
                    exit;

                } elseif ($foto) {
                    $stmt = $mysqli->prepare("INSERT INTO kamar (kode, harga, status, khusus, foto, sl_kamar) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $kode, $harga, $status, $khusus, $foto, $sl_kamar);

                    if ($stmt->execute()) {
                        $_SESSION['alert'] = [
                            'icon' => 'success',
                            'title' => 'Berhasil!',
                            'text' => 'Kamar berhasil ditambahkan!'
                        ];
                    } else {
                        $_SESSION['alert'] = [
                            'icon' => 'error',
                            'title' => 'Gagal!',
                            'text' => 'Kamar gagal ditambahkan.'
                        ];
                    }
                } else {
                    $_SESSION['alert'] = [
                        'icon' => 'error',
                        'title' => 'Upload Gagal!',
                        'text' => 'Foto wajib diunggah.'
                    ];
                }

                header("Location: ../../../index?kamar=data_kamar");
                exit;

            }
    } else {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Error...',
            'text' => 'Data tidak lengkap! Hubungi admin.'
        ];
        header("Location: ../../../index?kamar=data_kamar");
        exit;
    }

?>