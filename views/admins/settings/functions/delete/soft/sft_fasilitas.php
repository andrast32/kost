<?php

    include("../../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if (isset($_GET['id_fasilitas'])) {
        $id = $_GET['id_fasilitas'];
        $stmt = $mysqli->prepare("UPDATE fasilitas SET deleted = 1 WHERE id_fasilitas = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['alert'] = [
                'icon' => 'success',
                'title' => 'Terhapus!',
                'text' => 'Data fasilitas berhasil dihapus!'
            ];
        } else {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data fasilitas gagal dihapus!'
            ];
        }

        $stmt->close();

    }

    header("Location: ../../../../index?fasilitas=data_fasilitas");
    exit;

?>