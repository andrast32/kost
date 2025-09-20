<?php

    $deleted_fasilitas = $mysqli->query("SELECT * FROM fasilitas ORDER BY id_fasilitas ASC");

    $active_fasilitas = [];
    $has_deleted_fasilitas = true;

    while ($fasilitas = $deleted_fasilitas->fetch_assoc()) {
        if ($fasilitas['deleted'] != 0) {
            $active_fasilitas[] = $fasilitas;
        } else {
            $has_deleted_fasilitas = false;
        }
    }

?>