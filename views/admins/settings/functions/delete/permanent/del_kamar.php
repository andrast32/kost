<?php

    include("../../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if (isset($_GET['id_kamar']) && is_numeric($_GET['id_kamar'])) {
        $id_kamar = intval($_GET['id_kamar']);

        $stmt = $mysqli->prepare("SELECT * FROM kamar WHERE id_kamar = ?");
        $stmt->bind_param("i", $id_kamar);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();

            $fotoDir = "../../../../../../assets/uploads/kamar/";
            if (!empty($data['foto']) && file_exists($fotoDir . $data['foto'])) {
                unlink($fotoDir . $data['foto']);
            }

            $del = $mysqli->prepare("DELETE FROM kamar WHERE id_kamar = ?");
            $del->bind_param("i", $id_kamar);

            if ($del->execute()) {
                $_SESSION['alert'] = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Data kamar berhasil dihapus dan tidak dapat dikembalikan.'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Kamar gagal dihapus.'
                ];
            }
        } else {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data kamar tidak ditemukan.'
            ];
        }

        header("Location: ../../../../index?kamar=data_kamar");
        exit;

    }

    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Error...',
        'text' => 'Permintaan tidak valid.'
    ];
    header("Location: ../../../../index?kamar=data_kamar");
    exit;

?>