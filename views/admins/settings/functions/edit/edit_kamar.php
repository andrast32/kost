<?php

    include("../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset(
                $_POST['id_kamar'], 
                $_POST['kode_kamar'],
                $_POST['deskripsi'], 
                $_POST['harga'], 
                $_POST['khusus'] 
            )) {

                $id_kamar = intval($_POST['id_kamar']);
                $kode_kamar = $_POST['kode_kamar'];
                $deskripsi = $_POST['deskripsi'];
                $harga = $_POST['harga'];
                $khusus = $_POST['khusus'];

                $fotoDir    = "../../../../../assets/uploads/kamar/";

                function uploadFile($file, $targetDir, $allowedExt = []) {
                    if ($file['error'] === 0) {

                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                        if (!empty($allowedExt) && !in_array($ext, $allowedExt)) {
                            return null;
                        }

                        $randomName = uniqid('kamar_', true) . '.' . $ext;

                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }

                        $destination = $targetDir . $randomName;

                        if (move_uploaded_file($file['tmp_name'], $destination)) {
                            return $randomName;
                        }

                    }
                    return null;
                }

                $query = $mysqli->prepare("SELECT * FROM kamar WHERE id_kamar = ?");
                $query->bind_param("i", $id_kamar);
                $query->execute();
                $result = $query->get_result();
                $data = $result->fetch_assoc();

                $foto = !empty($_FILES['foto']['name']) ? uploadFile($_FILES['foto'], $fotoDir, ['jpg','jpeg','png']) : $data['foto'];

                if (!empty($_FILES['foto']['name']) && !empty($data['foto']) && file_exists($fotoDir . $data['foto'])) {
                    unlink($fotoDir . $data['foto']);
                }

                $stmt_cek = $mysqli->prepare("SELECT COUNT(*) FROM kamar WHERE kode_kamar = ? AND id_kamar != ?");
                $stmt_cek->bind_param("si", $kode_kamar, $id_kamar);
                $stmt_cek->execute();
                $stmt_cek->bind_result($count);
                $stmt_cek->fetch();
                $stmt_cek->close();

                if ($count > 0) {
                    $_SESSION['alert'] = [
                        'icon' => 'error',
                        'title' => 'Gagal!',
                        'text' => 'Kamar dengan kode tersebut sudah ada.'
                    ];
                    header("Location: ../../../index?kamar=data_kamar");
                    exit;

                } else {

                    $sl_kamar = bin2hex(random_bytes(32));

                    $stmt = $mysqli->prepare("UPDATE kamar SET kode_kamar = ?, deskripsi = ?, harga = ?, khusus = ?, foto = ?, sl_kamar = ? WHERE id_kamar = ?");
                    $stmt->bind_param("ssssssi", $kode_kamar, $deskripsi, $harga, $khusus, $foto, $sl_kamar, $id_kamar);

                    if ($stmt->execute()) {
                        $_SESSION['alert'] = [
                            'icon' => 'success',
                            'title' => 'Berhasil!',
                            'text' => 'Data kamar berhasil diubah!'
                        ];
                    } else {
                        $_SESSION['alert'] = [
                            'icon' => 'error',
                            'title' => 'Gagal!',
                            'text' => 'Data kamar gagal diubah!'
                        ];
                    }
                    $stmt->close();

                }

            } else {
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Error...',
                    'text' => 'Data tidak lengkap. Hubungi admin untuk memperbaiki code!'
                ];
            }
            header("Location: ../../../index?kamar=data_kamar");
            exit;
    } else {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Error...',
            'text' => 'Invalid request.'
        ];
        header("Location: ../../../index?kamar=data_kamar");
        exit;
    }

?>