<?php

    session_name('kost');
    session_start();

    if (!isset($_SESSION['id_user']) || !isset($_SESSION['session_token'])) {
        header("Location: /kost/views/dashboards/login");
        exit();
    }

    $id = $_SESSION['id_user'];
    $token = $_SESSION['session_token'];

    $cek = $mysqli->query("SELECT deleted FROM user WHERE id_user = $id");
    $data = $cek->fetch_assoc();

    if ($data['deleted'] == 1) {

        session_unset();
        session_destroy();
        header("Location: /kost/views/dashboards/login");
        exit();
        
    }

    $id_user_session = $_SESSION['id_user'];

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