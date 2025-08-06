<?php

    if (isset($_GET['settings'])) {
        include ("settings/functions/".$_GET['settings'].".php");
    } 

    elseif (isset($_GET['kamar'])) {
        include ("pages/data_kost/kamar/".$_GET['kamar'].".php");
    } 

    elseif (isset($_GET['biodata'])) {
        include ("pages/biodata/".$_GET['biodata'].".php");
    } 

    elseif (isset($_GET['fasilitas'])) {
        include ("pages/data_kost/fasilitas/".$_GET['fasilitas'].".php");
    }

    elseif (isset($_GET['penyewa'])) {
        include ("pages/data_user/penyewa/".$_GET['penyewa'].".php");
    }

    elseif (isset($_GET['petugas'])) {
        include ("pages/data_user/petugas/".$_GET['petugas'].".php");
    }

    elseif (isset($_GET['pembayaran'])) {
        include ("pages/data_pembayaran/".$_GET['pembayaran'].".php");
    }

    elseif (isset($_GET['pemesanan'])) {
        include ("pages/data_pemesanan/".$_GET['pemesanan'].".php");
    }

    else {
        include "settings/UI/dashboard.php";
    }

?>