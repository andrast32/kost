<?php

    include("../../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $mysqli->prepare("UPDATE user SET deleted = 2, session_token = NULL WHERE id_user = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {

            $_SESSION['alert'] = [
                'icon' => 'success',
                'title' => 'Dihapus!',
                'text' => 'Data penyewa berhasil dihapus permanen.'
            ];
        } else {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data penyewa gagal dihapus!'
            ];
        }

        $stmt->close();

    }

    header("Location: ../../../../index?penyewa=deleted_penyewa");
    exit();