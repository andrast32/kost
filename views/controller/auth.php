<?php

session_name('kost');
session_start();
include "connect.php";

// Cek koneksi database
if ($mysqli->connect_error) {
    die("Koneksi ke database gagal: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT * FROM user WHERE BINARY username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result_user = $stmt->get_result();

    if ($result_user && $result_user->num_rows === 1) {
        $row_user = $result_user->fetch_assoc();

        if ((int)$row_user['deleted'] === 1) {
            $error_message = "Akun anda disuspend. Silakan hubungi admin untuk aktivasi kembali.";

        } elseif (!empty($row_user['session_token'])) {
            $error_message = "Akun ini sedang login di perangkat lain. Harap logout terlebih dahulu.";

        } elseif (password_verify($password, $row_user['password'])) {
            $session_user_token = bin2hex(random_bytes(32));

            $update_user_token = $mysqli->prepare("UPDATE user SET session_token = ? WHERE id_user = ?");
            $update_user_token->bind_param("si", $session_user_token, $row_user['id_user']);
            $update_user_token->execute();
            $update_user_token->close();

            session_regenerate_id(true);
            $_SESSION['id_user']        = $row_user['id_user'];
            $_SESSION['username']       = $row_user['username'];
            $_SESSION['nama_user']      = $row_user['nama_user'];
            $_SESSION['role']           = $row_user['role'];
            $_SESSION['deleted']        = $row_user['deleted'];
            $_SESSION['session_token']  = $session_user_token;

            if ($row_user['role'] === 'Admin') {
                header("Location: /kost/views/admins/index");
            } elseif ($row_user['role'] === 'User') {
                header("Location: /kost/views/users/index");
            } else {
                $error_message = "Role tidak dikenali.";
            }

        } else {
            $error_message = "Username atau Password salah.";
        }

    } else {
        $error_message = "Username tidak ditemukan.";
    }
}
?>
