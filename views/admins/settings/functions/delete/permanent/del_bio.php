<?php

include("../../../../../controller/connect.php");

session_name('kost');
session_start();

if (isset($_GET['id_biodata'], $_GET['id_user']) && is_numeric($_GET['id_biodata']) && is_numeric($_GET['id_user'])) {
    $id_biodata = intval($_GET['id_biodata']);
    $id_user = intval($_GET['id_user']);

    $stmt = $mysqli->prepare("SELECT * FROM biodata WHERE id_biodata = ?");
    $stmt->bind_param("i", $id_biodata);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();

        $baseDir = "../../../../../../assets/uploads/biodata/";
        $fotoPath        = $baseDir . "foto/" . $data['foto'];
        $kkPath          = $baseDir . "kk/" . $data['scan_kk'];
        $ktpPath         = $baseDir . "ktp/" . $data['scan_ktp'];
        $buktiNikahPath  = $baseDir . "bukti_nikah/" . $data['bukti_nikah'];

        if (file_exists($fotoPath)) unlink($fotoPath);
        if (file_exists($kkPath)) unlink($kkPath);
        if (!empty($data['scan_ktp']) && file_exists($ktpPath)) unlink($ktpPath);
        if (!empty($data['bukti_nikah']) && file_exists($buktiNikahPath)) unlink($buktiNikahPath);

        $del = $mysqli->prepare("DELETE FROM biodata WHERE id_biodata = ?");
        $del->bind_param("i", $id_biodata);

        if ($del->execute()) {
            $_SESSION['alert'] = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data biodata berhasil dihapus.'
            ];
        } else {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Biodata gagal dihapus.'
            ];
        }
    } else {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Gagal!',
            'text' => 'Data biodata tidak ditemukan.'
        ];
    }

    header("Location: ../../../../index?penyewa=biodata_user&id_user=$id_user");
    exit;
}

    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Error...',
        'text' => 'Permintaan tidak valid.'
    ];
    header("Location: ../../../../index?penyewa=data_penyewa");
    exit;
