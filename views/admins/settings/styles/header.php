<?php

    session_name('kost');
    session_start();

    if (!isset($_SESSION['id_user']) || !isset($_SESSION['session_token'])) {
        header("Location: /kost/views/dashboards/login");
        exit();
    }

    $id = $_SESSION['id_user'];
    $token = $_SESSION['session_token'];

    $stmt_cek = $mysqli->prepare("SELECT deleted FROM user WHERE id_user = ?");
    $stmt_cek->bind_param("i", $id);
    $stmt_cek->execute();
    $cek = $stmt_cek->get_result();
    $data = $cek->fetch_assoc();
    $stmt_cek->close();

    if (!$data || $data['deleted'] == 1) {

        session_unset();
        session_destroy();
        header("Location: /kost/views/dashboards/login");
        exit();
        
    }

    $id_user_session = $_SESSION['id_user'];

    $stmt_pengaturan = $mysqli->prepare("SELECT nilai_pengaturan FROM pengaturan WHERE nama_pengaturan = 'terakhir_cek_checkout'");
    $stmt_pengaturan->execute();
    $result_pengaturan = $stmt_pengaturan->get_result();
    $row_pengaturan = $result_pengaturan->fetch_assoc();
    $stmt_pengaturan->close();

    if(!$row_pengaturan) {
        $terakhir_cek = new DateTime('2020-01-01 00:00:00');
    } else {
        $terakhir_cek = new DateTime($row_pengaturan['nilai_pengaturan']);
    }

    $sekarang = new DateTime();

    $selisih_jam = ($sekarang->getTimestamp() - $terakhir_cek->getTimestamp()) / 3600;

    if ($selisih_jam > 12) {
        include('settings/functions/checkout.php'); 
        
        $stmt_update_pengaturan = $mysqli->prepare("UPDATE pengaturan SET nilai_pengaturan = NOW() WHERE nama_pengaturan = 'terakhir_cek_checkout'");
        $stmt_update_pengaturan->execute();
        $stmt_update_pengaturan->close();
    }

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title; ?></title>
        <link rel="icon" href="/kost/assets/UI/Dashboards/assets/images/info-icon-03.png" type="image/x-icon">

        <!-- Fonts and icons -->
        <script src="/kost/assets/UI/Admins/js/plugin/webfont/webfont.min.js"></script>
        <script>
            WebFont.load({
                google: { families: ["Public Sans:300,400,500,600,700"] },
                custom: {
                    families: [
                        "Font Awesome 5 Solid",
                        "Font Awesome 5 Regular",
                        "Font Awesome 5 Brands",
                        "simple-line-icons",
                    ],
                    urls: ["/kost/assets/UI/Admins/css/fonts.min.css"],
                },
                active: function () {
                    sessionStorage.fonts = true;
                },
            });
        </script>

        <!-- CSS Files -->
        <link rel="stylesheet" href="/kost/assets/UI/Admins/css/bootstrap.min.css">
        <link rel="stylesheet" href="/kost/assets/UI/Admins/css/plugins.min.css">
        <link rel="stylesheet" href="/kost/assets/UI/Admins/css/kaiadmin.min.css">

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    </head>

    <body>
        <div class="wrapper">