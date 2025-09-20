<?php

    include("../../../../../controller/connect.php");

    session_name('kost');
    session_start();

    $id = $_GET['id'];
    $mysqli->query("DELETE FROM fasilitas WHERE id_fasilitas = $id");

    $_SESSION['alert'] = [
        'icon' => 'success',
        'title' => 'Dihapus!',
        'text' => 'Data fasilitas berhasil dihapus permanen.'
    ];

    header("Location: ../../../../index?fasilitas=deleted_fasilitas");
    exit();