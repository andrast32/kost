<?php
    $host  = 'localhost';
    $user  = 'andrast';
    $pass  = 'Indra.132';
    $db    = 'kost';

    $mysqli = new mysqli($host, $user, $pass, $db);
    if ($mysqli->connect_error) {
        die("Koneksi database gagal: " . $mysqli->connect_error);
    }
?>