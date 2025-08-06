<?php

    include("../../../../../controller/connect.php");

    session_name('kost');
    session_start();

    $id = $_GET['id'];
    $mysqli->query("DELETE FROM user WHERE id_user = $id");

    $_SESSION['alert'] = [
        'icon' => 'success',
        'title' => 'Dihapus!',
        'text' => 'Data penyewa berhasil dihapus permanen.'
    ];

    header("Location: ../../../../index?penyewa=deleted_penyewa");
    exit();