<?php

    include("../../../../../controller/connect.php");

    session_name('kost');
    session_start();

    if (isset($_GET['id_fasilitas']) && is_numeric($_GET['id_fasilitas'])) {
        $id_fasilitas = intval($_GET['id_fasilitas']);

        $stmt = $mysqli->prepare("SELECT * FROM fasilitas WHERE id_fasilitas = ?");
        $stmt->bind_param("i", $id_fasilitas);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();

            $fotoDir = "../../../../../../assets/uploads/fasilitas/";
            if (!empty($data['foto']) && file_exists($fotoDir . $data['foto'])) {
                unlink($fotoDir . $data['foto']);
            }

            $del = $mysqli->prepare("DELETE FROM fasilitas WHERE id_fasilitas = ?");
            $del->bind_param("i", $id_fasilitas);

            if ($del->execute()) {
                $_SESSION['alert'] = [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Fasilitas berhasil dihapus dan tidak dapat dikembalikan.'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Fasilitas gagal dihapus.'
                ];
            }
        } else {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Fasilitas tidak ditemukan.'
            ];
        }

        header("Location: ../../../../index?fasilitas=deleted_fasilitas");
        exit;

    }

    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Error...',
        'text' => 'Permintaan tidak valid.'
    ];
    header("Location: ../../../../index?fasilitas=deleted_fasilitas");
    exit;

?>