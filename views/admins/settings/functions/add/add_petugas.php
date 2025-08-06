<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['username'])
            && isset($_POST['password'])
            && isset($_POST['nama_user'])
            && isset($_POST['role'])) {

                $username = $_POST['username'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $nama_user = $_POST['nama_user'];
                $role = $_POST['role'];

                $stmt_cek = $mysqli->prepare("SELECT COUNT(*) FROM user WHERE username = ? ");
                $stmt_cek->bind_param("s", $username);
                $stmt_cek->execute();
                $stmt_cek->bind_result($count);
                $stmt_cek->fetch();
                $stmt_cek->close();

                if ($count > 0) {
                    $_SESSION['alert'] = [
                        'icon' => 'error',
                        'title' => 'Oops...',
                        'text' => 'Username sudah dipakai!'
                    ];
                } else {
                    $stmt = $mysqli->prepare("INSERT INTO user (username, password, nama_user, role) VALUES (?,?,?,?) ");
                    $stmt->bind_param("ssss", $username, $password, $nama_user, $role);

                    if ($stmt->execute()) {
                        $_SESSION['alert'] = [
                            'icon' => 'success',
                            'title' => 'Berhasil!',
                            'text' => 'Data admin berhasil ditambahkan!'
                        ];
                    } else {
                        $_SESSION['alert'] = [
                            'icon' => 'error',
                            'title' => 'Gagal!',
                            'text' => 'Data admin gagal ditambahkan!'
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