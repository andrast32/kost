SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `biodata`;
CREATE TABLE `biodata` (
  `id_biodata` bigint NOT NULL AUTO_INCREMENT,
  `id_user` bigint NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `jk` enum('Laki-Laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_hp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `scan_kk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `scan_ktp` varchar(255) DEFAULT NULL,
  `bukti_nikah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id_biodata`),
  UNIQUE KEY `id_user` (`id_user`),
  CONSTRAINT `biodata_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `biodata` VALUES("1","5","jln pegangsaan timur no 100 blok abc","Perempuan","123","1.jpg","","5nurul.pdf","");
INSERT INTO `biodata` VALUES("2","3","jln pegangsaan timur no 10","Perempuan","012345678912","file_688a9f5c2e4c63.69148899.png","file_68899891a4e088.34731846.pdf","file_68899891a52022.45624450.pdf","file_68899891a55bc7.73072456.pdf");
INSERT INTO `biodata` VALUES("5","6","rumah sinta aulia cantika putri ramdani lestari anggunita","Perempuan","012345678912","file_688fef5333d019.26633974.png","","","");


DROP TABLE IF EXISTS `fasilitas`;
CREATE TABLE `fasilitas` (
  `id_fasilitas` bigint NOT NULL AUTO_INCREMENT,
  `kode_fasilitas` varchar(50) NOT NULL,
  `nama_fasilitas` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sl_fasilitas` text NOT NULL,
  PRIMARY KEY (`id_fasilitas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `kamar`;
CREATE TABLE `kamar` (
  `id_kamar` bigint NOT NULL AUTO_INCREMENT,
  `kode_kamar` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `status` enum('Kosong','Terisi') DEFAULT 'Kosong',
  `khusus` enum('Laki-Laki','Perempuan') NOT NULL,
  `foto` varchar(255) NOT NULL,
  `sl_kamar` text NOT NULL,
  PRIMARY KEY (`id_kamar`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `kamar` VALUES("1","A1","Kamar ukuran 10 x 10 kamar mandi bersama (diluar) dilengkapi listrik dan sudah termasuk uang kebersihan","650000.00","Kosong","Perempuan","kamar_689ea957635525.29517637.jpg","dbcb4cbfa21c1cd3461ccd2013de2f3569b88d2d0cd3c9cd7f3d520a27e58b85");
INSERT INTO `kamar` VALUES("3","B1","Kamar ukuran 10 x 10 kamar mandi dilengkapi listrik dan sudah termasuk uang kebersihan","750000.00","Kosong","Laki-Laki","kamar_68a1b46a608dc6.78322436.jpg","718faf839f00af6a795148e58d74a962a7b1e28887f5be2d457fcecc1a8ccbad");
INSERT INTO `kamar` VALUES("4","A2","Kamar ukuran 10 x 10 kamar mandi bersama (diluar) dilengkapi listrik dan sudah termasuk uang kebersihan","650000.00","Kosong","Perempuan","kamar_68a3be0a84f9b3.31023546.jpg","dbb259ef8aa000cf63d7315c6241332fa544694510b804452de319f228c93128");


DROP TABLE IF EXISTS `pembayaran`;
CREATE TABLE `pembayaran` (
  `id_pembayaran` bigint NOT NULL AUTO_INCREMENT,
  `id_pemesanan` bigint NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah_bayar` decimal(12,2) DEFAULT NULL,
  `bukti_transaksi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
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
  `sl_user` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user` VALUES("1","andra@admin.com","$2y$10$IQANylhjmFDqKPmmGwUtkuA40w.n91iLCDnGoGYdJa9xfi4J1psae","Andra Setiawan","Admin","0","a97fea34bc50be3791eafa79e2f289d2c298b703e54c977319d5906aa5d6930b","3fb8b228d612d4ad9ec5ddbd1b5f76b19787d729b05fbea337df58df28f14046");
INSERT INTO `user` VALUES("2","antasena@admin.com","$2y$10$GoLDjlkH4lHkhmENbJdR2OZhc9uD429nNvhvCX8QIqXQnjHyq98Pu","Antasena","Admin","0","","7a02508f87efb8529fd2233fa7318bb4ef6d83a2f94a6d4f73420add8bde5502");
INSERT INTO `user` VALUES("3","viona@kost.com","$2y$10$AauCI.lrzTrfuLMb2vCgL.sPp9WZSZWyAAwTVUs/WQFz68.OjX2v2","Viona","User","0","","2c9710262340c61e435dea4a3a010f31c30b79c0286f47d1661477a136226c65");
INSERT INTO `user` VALUES("4","indra@admin.com","$2y$10$POEkUANjULOZCOjlwyCP9uNyWwBcgBKAfhOZpNuqj2jWHxBYuR4Ry","Nurindra Setiawan","Admin","0","","2e640f53cd90e5ebe796cb6a715a6b94551af61f27b3f09696fca55ce5031c4c");
INSERT INTO `user` VALUES("5","nurul@kost.com","$2y$10$ALmeOAuSaD3pm3/0FU9sju4lqIE4nDdDZ71ULPgHtDpTFfADxJb1K","Nurul Maryam","User","0","","caffe24a470c5572d75c806efa59119babf325b0353dcfaff36f1901a5665a52");
INSERT INTO `user` VALUES("6","percobaan@kost.com","$2y$10$ul5P9YxLzmtqix8JozBuNe83enemsRcy22LIWF6Io7oWY.UmofhBq","coba kost","User","0","","68f7213c847c99a64d482e53a868e82394f1b8d0244c46318ca7667e2303ac45");
INSERT INTO `user` VALUES("9","asep@kost.com","$2y$10$gDgmG/Wdehev3jCOj9DaL.7pz8HHJXo6X9Q0vqf9t0NeNKMoAol8q","asep cahyadi","User","0","","32bd479a6c69ae81ac6b89319541d848cf9fce3b0662d37fd4a915e9ad0d5474");


SET FOREIGN_KEY_CHECKS = 1;
