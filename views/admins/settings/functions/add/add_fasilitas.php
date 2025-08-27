<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset(
                $_POST['kode_fasilitas'],
                $_POST['nama_fasilitas'],
                $_POST['deskripsi'],
                $_POST['harga']
            )) {
                $kode_fasilitas = $_POST['kode_fasilitas'];
                $nama_fasilitas = $_POST['nama_fasilitas'];
                $deskripsi = $_POST['deskripsi'];
                $harga = $_POST['harga'];

                $sl_fasilitas = bin2hex(random_bytes(32));

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
                } else {
                    $stmt = $mysqli->prepare("INSERT INTO fasilitas (kode_fasilitas, nama_fasilitas, deskripsi, harga, sl_fasilitas) VALUES (?,?,?,?,?) ");
                    $stmt->bind_param("sssis", $kode_fasilitas, $nama_fasilitas, $deskripsi, $harga, $sl_fasilitas);

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