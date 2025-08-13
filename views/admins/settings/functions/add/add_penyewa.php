<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if (isset($_POST['username'])
            && isset($_POST['password'])
            && isset($_POST['nama_user'])
            && isset($_POST['nama_user'])
            && isset($_POST['role'])) {

                $username = $_POST['username'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $nama_user = $_POST['nama_user'];
                $role = $_POST['role'];

                $sl_user = bin2hex(random_bytes(32));

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
                    $stmt = $mysqli->prepare("INSERT INTO user (username, password, nama_user, role, sl_user) VALUES (?,?,?,?,?) ");
                    $stmt->bind_param("sssss", $username, $password, $nama_user, $role, $sl_user);

                    if ($stmt->execute()) {
                        $_SESSION['alert'] = [
                            'icon' => 'success',
                            'title' => 'Berhasil!',
                            'text' => 'Data penyewa berhasil ditambahkan!'
                        ];
                    } else {
                        $_SESSION['alert'] = [
                            'icon' => 'error',
                            'title' => 'Gagal!',
                            'text' => 'Data penyewa gagal ditambahkan!'
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
            header("Location: ../../../index?penyewa=data_penyewa");
            exit;
    } else {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Error...',
            'text' => 'Invalid request!'
        ];
        header("Location: ../../../index?penyewa=data_penyewa");
        exit;
    }