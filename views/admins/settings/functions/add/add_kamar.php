<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset(
                $_POST['kode'],
                $_POST['harga'],
                $_POST['status'],
                $_POST['khusus'],
            )){

                $kode   = $_POST['kode'];
                $harga  = $_POST['harga'];
                $status = $_POST['status'];
                $khusus = $_POST['khusus'];

                $fotoDir    = "../../../../../assets/uploads/kamar/";

                function uploadFile($file, $targetDir, $allowedExt = []) {
                    if ($file['error'] === 0) {

                        $ext = strtolower(pathinfo($file['name'], PATHINFO_FILENAME));

                        if (!empty($allowedExt) && !in_array($ext, $allowedExt)) {
                            return null;
                        }

                        $randomName = uniqid('kamar_', true) . '.' . $ext;
                        $destination = $targetDir . $randomName;

                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }

                        if (move_uploaded_file($file['tmp_name'], $destination)) {
                            return $randomName;
                        }

                    }
                    return null;
                }

                $foto = isset($_FILES['foto']) ? uploadFile($_FILES['foto'], $fotoDir, ['jpg', 'jpeg', 'png']) : null;

                if ($foto) {
                    $stmt = $mysqli->prepare("INSERT INTO kamar");
                }

            }
    }

?>