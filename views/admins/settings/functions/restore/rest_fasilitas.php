<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $mysqli->prepare("UPDATE fasilitas SET deleted = 0 WHERE id_fasilitas = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['alert'] = [
                'icon' => 'success',
                'title' => 'Direstore!',
                'text' => 'Fasilitas berhasil direstore!'
            ];
        } else {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Fasilitas gagal direstore!'
            ];
        }

        $stmt->close();

    }

    header("Location: ../../../index?fasilitas=deleted_fasilitas");
    exit;