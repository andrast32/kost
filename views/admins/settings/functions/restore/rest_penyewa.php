<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $mysqli->prepare("UPDATE user SET deleted = 0 WHERE id_user = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['alert'] = [
                'icon' => 'success',
                'title' => 'Direstore!',
                'text' => 'Data penyewa berhasil direstore!'
            ];
        } else {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data penyewa gagal direstore!'
            ];
        }

        $stmt->close();

    }

    header("Location: ../../../index?penyewa=deleted_penyewa");
    exit;