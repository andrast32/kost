SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `biodata`;
CREATE TABLE `biodata` (
  `id_biodata` bigint NOT NULL AUTO_INCREMENT,
  `id_user` bigint NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `jk` enum('Laki-Laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `scan_kk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `scan_ktp` varchar(255) DEFAULT NULL,
  `bukti_nikah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id_biodata`),
  UNIQUE KEY `id_user` (`id_user`),
  CONSTRAINT `biodata_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `biodata` VALUES("1","5","","Perempuan","","","","","");


DROP TABLE IF EXISTS `fasilitas`;
CREATE TABLE `fasilitas` (
  `id_fasilitas` bigint NOT NULL AUTO_INCREMENT,
  `nama_fasilitas` varchar(100) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_fasilitas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `kamar`;
CREATE TABLE `kamar` (
  `id_kamar` bigint NOT NULL AUTO_INCREMENT,
  `nama_kamar` varchar(100) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `status` enum('Kosong','Terisi') DEFAULT 'Kosong',
  PRIMARY KEY (`id_kamar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `pembayaran`;
CREATE TABLE `pembayaran` (
  `id_pembayaran` bigint NOT NULL AUTO_INCREMENT,
  `id_pemesanan` bigint NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah_bayar` decimal(12,2) NOT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `status` enum('Belum Dibayar','Menunggu Konfirmasi','Lunas') DEFAULT 'Belum Dibayar',
  PRIMARY KEY (`id_pembayaran`),
  KEY `id_pemesanan` (`id_pemesanan`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `pemesanan`;
CREATE TABLE `pemesanan` (
  `id_pemesanan` bigint NOT NULL AUTO_INCREMENT,
  `id_user` bigint NOT NULL,
  `id_kamar` bigint NOT NULL,
  `tanggal_pesan` date NOT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `durasi` int NOT NULL COMMENT 'Dalam bulan',
  `total` decimal(12,2) NOT NULL,
  `status` enum('Pending','Diterima','Ditolak') DEFAULT 'Pending',
  PRIMARY KEY (`id_pemesanan`),
  KEY `id_user` (`id_user`),
  KEY `id_kamar` (`id_kamar`),
  CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `pemesanan_fasilitas`;
CREATE TABLE `pemesanan_fasilitas` (
  `id_pemesanan` bigint NOT NULL,
  `id_fasilitas` bigint NOT NULL,
  `harga_saat_pesan` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id_pemesanan`,`id_fasilitas`),
  KEY `id_fasilitas` (`id_fasilitas`),
  CONSTRAINT `pemesanan_fasilitas_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE,
  CONSTRAINT `pemesanan_fasilitas_ibfk_2` FOREIGN KEY (`id_fasilitas`) REFERENCES `fasilitas` (`id_fasilitas`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` bigint NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_user` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Admin','User') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'User',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `session_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user` VALUES("1","andra@admin.com","$2y$10$IQANylhjmFDqKPmmGwUtkuA40w.n91iLCDnGoGYdJa9xfi4J1psae","Andra Setiawan","Admin","0","304fc5672471bbe018b13c2ec8ef841f8f00fb060e45a8b9b71ec48b8bdce9c0");
INSERT INTO `user` VALUES("2","antasena@admin.com","$2y$10$GoLDjlkH4lHkhmENbJdR2OZhc9uD429nNvhvCX8QIqXQnjHyq98Pu","Antasena","Admin","0","");
INSERT INTO `user` VALUES("3","kuyang@admin.com","$2y$10$AauCI.lrzTrfuLMb2vCgL.sPp9WZSZWyAAwTVUs/WQFz68.OjX2v2","kuyang","User","0","");
INSERT INTO `user` VALUES("4","indra@admin.com","$2y$10$POEkUANjULOZCOjlwyCP9uNyWwBcgBKAfhOZpNuqj2jWHxBYuR4Ry","Nurindra Setiawan","Admin","0","");
INSERT INTO `user` VALUES("5","nurul@kost.com","$2y$10$ALmeOAuSaD3pm3/0FU9sju4lqIE4nDdDZ71ULPgHtDpTFfADxJb1K","Nurul Maryam","User","0","");
INSERT INTO `user` VALUES("6","percobaan@admin.com","$2y$10$ul5P9YxLzmtqix8JozBuNe83enemsRcy22LIWF6Io7oWY.UmofhBq","coba admin","User","0","");


SET FOREIGN_KEY_CHECKS = 1;
