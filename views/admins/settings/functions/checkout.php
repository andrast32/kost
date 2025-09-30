<?php

    require '../controller/connect.php'; 

    $tanggal_hari_ini = date('Y-m-d');

    $stmt_expired = $mysqli->prepare(
        "SELECT id_pemesanan FROM pemesanan WHERE tanggal_akhir_kontrak < ? AND status_kontrak = 'Aktif'"
    );
    $stmt_expired->bind_param("s", $tanggal_hari_ini);
    $stmt_expired->execute();
    $result_expired = $stmt_expired->get_result();

    if ($result_expired->num_rows === 0) {

        return;
    }

    while ($pesanan = $result_expired->fetch_assoc()) {
        $id_pemesanan = $pesanan['id_pemesanan'];
        
        $mysqli->begin_transaction();
        try {
            $stmt_items = $mysqli->prepare(
                "SELECT tipe_item, id_item, jumlah FROM detail_pemesanan WHERE id_pemesanan = ?"
            );
            $stmt_items->bind_param("i", $id_pemesanan);
            $stmt_items->execute();
            $result_items = $stmt_items->get_result();
            
            if($result_items->num_rows > 0) {
                $stmt_update_kamar = $mysqli->prepare("UPDATE kamar SET status = 'Kosong' WHERE id_kamar = ?");
                $stmt_update_stok = $mysqli->prepare("UPDATE fasilitas SET stok = stok + ? WHERE id_fasilitas = ?");
                
                while ($item = $result_items->fetch_assoc()) {
                    if ($item['tipe_item'] === 'kamar') {
                        $stmt_update_kamar->bind_param("i", $item['id_item']);
                        $stmt_update_kamar->execute();
                    } 
                    elseif ($item['tipe_item'] === 'fasilitas') {
                        $stmt_update_stok->bind_param("ii", $item['jumlah'], $item['id_item']);
                        $stmt_update_stok->execute();
                    }
                }
                $stmt_update_kamar->close();
                $stmt_update_stok->close();
            }
            $stmt_items->close();

            $stmt_update_pesanan = $mysqli->prepare("UPDATE pemesanan SET status_kontrak = 'Selesai' WHERE id_pemesanan = ?");
            $stmt_update_pesanan->bind_param("i", $id_pemesanan);
            $stmt_update_pesanan->execute();
            $stmt_update_pesanan->close();

            $mysqli->commit();

        } catch (Exception $e) {
            $mysqli->rollback();
        }
    }

?>