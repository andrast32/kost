<?php

include("../../../../controller/connect.php");

session_name('kost');
session_start();

// Cek koneksi
if (!isset($mysqli) || $mysqli->connect_errno) {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops...!',
        'text' => 'Gagal koneksi database: ' . addslashes($mysqli->connect_error)
    ];
    header("Location: ../../../index");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {

        $file_tmp  = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $ext       = pathinfo($file_name, PATHINFO_EXTENSION);

        if (strtolower($ext) !== 'sql') {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Oops...!',
                'text' => 'File harus berekstensi .sql!'
            ];
            header("Location: ../../../index");
            exit();
        }

        $sql_content = file_get_contents($file_tmp);
        if (empty(trim($sql_content))) {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'File Kosong!',
                'text' => 'File SQL tidak memiliki isi.'
            ];
            header("Location: ../../../index");
            exit();
        }

        // Reset database
        $mysqli->query("SET FOREIGN_KEY_CHECKS = 0");
        $result = $mysqli->query("SHOW TABLES");
        while ($row = $result->fetch_array()) {
            $mysqli->query("DROP TABLE IF EXISTS `" . $row[0] . "`");
        }
        $mysqli->query("SET FOREIGN_KEY_CHECKS = 1");

        // Import file SQL
        if ($mysqli->multi_query($sql_content)) {
            do {
                if ($res = $mysqli->store_result()) {
                    $res->free();
                }
            } while ($mysqli->more_results() && $mysqli->next_result());

            $_SESSION['alert'] = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Database berhasil di-import.'
            ];
        } else {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Import Gagal',
                'text' => 'Terjadi kesalahan: ' . addslashes($mysqli->error)
            ];
        }

        header("Location: ../../../index");
        exit();

    } else {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Upload Gagal',
            'text' => 'File tidak ditemukan atau gagal upload.'
        ];
        header("Location: ../../../index");
        exit();
    }
}
?>
