<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['id_user'])
            && isset($_POST['username'])
            && isset($_POST['nama_user'])) {

                $id_user            = $_POST['id_user'];
                $username           = $_POST['username'];
                $nama_user          = $_POST['nama_user'];

                $stmt_cek = $mysqli->prepare("SELECT COUNT(*) FROM user WHERE username = ? AND id_user != ?");
                $stmt_cek->bind_param("si", $username, $id_user);
                $stmt_cek->execute();
                $stmt_cek->bind_result($count);
                $stmt_cek->fetch();
                $stmt_cek->close();

                if ($count > 0) {
                    $_SESSION['alert'] = [
                        'icon' => 'error',
                        'title' => 'Gagal...',
                        'text' => 'Usename sudah dipakai gunakan username lain!'
                    ];
                } else {

                    $sl = bin2hex(random_bytes(32));

                    $stmt = $mysqli->prepare("UPDATE user SET username = ?, nama_user = ?, sl = ? WHERE id_user = ? ");
                    $stmt->bind_param("sssi", $username, $nama_user, $sl, $id_user);

                    if ($stmt->execute()) {
                        $_SESSION['alert'] = [
                            'icon' => 'success',
                            'title' => 'Berhasil...',
                            'text' => 'Data admin berhasil di ubah!'
                        ];
                    } else {
                        $_SESSION['alert'] = [
                            'icon' => 'error',
                            'title' => 'Gagal...',
                            'text' => 'Data admin gagal di ubah!'
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
            header("Location: ../../../index?petugas=data_petugas");
            exit;

    } else {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Error...',
            'text' => 'Invalid request!'
        ];
        header("Location: ../../../index?petugas=data_petugas");
        exit;
    }