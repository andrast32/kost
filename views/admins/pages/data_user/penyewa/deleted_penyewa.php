<?php

    $delete_user_result = $mysqli->query("SELECT * FROM user WHERE role = 'User' ORDER BY id_user ASC");

    $active_users = [];
    $has_deleted_users = true;

    while ($user = $delete_user_result->fetch_assoc()) {
        if ($user['deleted'] != 0) {
            $active_users[] = $user;
        } else {
            $has_deleted_users = false;
        }
    }

?>