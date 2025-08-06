<?php
    session_name('kost');
    session_start();
    include "connect.php";

    function exportDatabase($host, $user, $pass, $dbname, $tables = '*') {
        $return = '';
        $link = mysqli_connect($host, $user, $pass, $dbname);

        if ($tables == '*') {
            $tables = [];
            $result = mysqli_query($link, 'SHOW TABLES');
            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        $return .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        foreach ($tables as $table) {
            $result = mysqli_query($link, "SELECT * FROM $table");
            $num_fields = mysqli_num_fields($result);

            $return .= "DROP TABLE IF EXISTS `$table`;\n";
            $create = mysqli_fetch_row(mysqli_query($link, "SHOW CREATE TABLE `$table`"));
            $return .= $create[1] . ";\n\n";

            while ($row = mysqli_fetch_row($result)) {
                $return .= "INSERT INTO `$table` VALUES(";
                for ($j = 0; $j < $num_fields; $j++) {
                    $value = isset($row[$j]) ? addslashes($row[$j]) : '';
                    $value = str_replace("\n", "\\n", $value);
                    $return .= '"' . $value . '"';
                    if ($j < $num_fields - 1) $return .= ',';
                }
                $return .= ");\n";
            }
            $return .= "\n\n";
        }

        $return .= "SET FOREIGN_KEY_CHECKS = 1;\n";

        $backup_root = __DIR__ . '/../../backup/';
        if (!is_dir($backup_root)) {
            mkdir($backup_root, 0777, true);
        }

        array_map('unlink', glob($backup_root . "*.sql"));

        $backup_file = $backup_root . "backup-db-kost.sql";
        $handle = fopen($backup_file, 'w+');
        fwrite($handle, $return);
        fclose($handle);

        return $backup_file;
    }

    if (isset($_SESSION['id_user']) && $_SESSION['role'] === 'Admin') {
        exportDatabase('localhost', 'root', '', 'kost');
    }

    if (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
        $mysqli->query("UPDATE user SET session_token = NULL WHERE id_user = $id_user");
    }

    $_SESSION = [];
    session_unset();
    session_destroy();

?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>The Kost | Logout</title>
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

        <!-- SweetAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>
        <div class="wrapper">
            <script>
                if (!sessionStorage.getItem("logoutReloaded")) {
                    sessionStorage.setItem("logoutReloaded", "true");

                    Swal.fire({
                        title: 'Logout Berhasil!',
                        text: 'Terimakasih sudah mengunjungi website ini.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });

                } else {
                    sessionStorage.removeItem("logoutReloaded");
                    window.location.href = "/kost/";
                }
            </script>
        </div>
    </body>

</html>
