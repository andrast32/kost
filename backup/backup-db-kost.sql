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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `biodata` VALUES("1","5","jln pegangsaan timur no 100 blok abc","Perempuan","123","1.jpg","","5nurul.pdf","");
INSERT INTO `biodata` VALUES("2","3","jln pegangsaan timur no 10","Perempuan","012345678912","file_688a9f5c2e4c63.69148899.png","file_68899891a4e088.34731846.pdf","file_68899891a52022.45624450.pdf","file_68899891a55bc7.73072456.pdf");
INSERT INTO `biodata` VALUES("5","6","rumah sinta aulia cantika putri ramdani lestari anggunita","Perempuan","012345678912","file_68c9ef216e8da6.15501401.jpg","","","");
INSERT INTO `biodata` VALUES("10","9","jln pegangsaan timur no 02","Laki-Laki","123456789012","file_68ccd216aabfe4.42741366.jpg","file_68ccd216ac1486.79320093.pdf","file_68ccd216ac5bf4.23434056.pdf","file_68ccd216acae74.87616592.pdf");


DROP TABLE IF EXISTS `detail_pemesanan`;
CREATE TABLE `detail_pemesanan` (
  `id_detail` bigint NOT NULL AUTO_INCREMENT,
  `id_pemesanan` bigint NOT NULL,
  `tipe_item` enum('kamar','fasilitas') NOT NULL,
  `id_item` bigint NOT NULL COMMENT 'Merujuk ke id_kamar atau id_fasilitas',
  `jumlah` int NOT NULL DEFAULT '1',
  `harga_saat_pesan` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `id_pemesanan` (`id_pemesanan`),
  CONSTRAINT `detail_pemesanan_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `detail_pemesanan` VALUES("1","1","kamar","5","1","650000.00");
INSERT INTO `detail_pemesanan` VALUES("2","1","fasilitas","1","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("3","1","fasilitas","3","1","30000.00");
INSERT INTO `detail_pemesanan` VALUES("4","1","fasilitas","4","1","25000.00");
INSERT INTO `detail_pemesanan` VALUES("5","1","fasilitas","5","1","50000.00");
INSERT INTO `detail_pemesanan` VALUES("6","1","fasilitas","7","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("7","1","fasilitas","8","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("8","2","kamar","6","1","650000.00");
INSERT INTO `detail_pemesanan` VALUES("9","2","fasilitas","1","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("10","2","fasilitas","4","1","25000.00");
INSERT INTO `detail_pemesanan` VALUES("11","2","fasilitas","7","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("12","2","fasilitas","8","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("13","5","kamar","8","1","650000.00");
INSERT INTO `detail_pemesanan` VALUES("14","5","fasilitas","1","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("15","5","fasilitas","3","1","30000.00");
INSERT INTO `detail_pemesanan` VALUES("16","5","fasilitas","4","1","25000.00");
INSERT INTO `detail_pemesanan` VALUES("17","5","fasilitas","5","1","50000.00");
INSERT INTO `detail_pemesanan` VALUES("18","5","fasilitas","7","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("19","5","fasilitas","8","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("20","6","kamar","7","1","650000.00");
INSERT INTO `detail_pemesanan` VALUES("21","6","fasilitas","1","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("22","6","fasilitas","7","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("23","6","fasilitas","8","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("24","7","kamar","5","1","650000.00");
INSERT INTO `detail_pemesanan` VALUES("25","7","fasilitas","4","1","25000.00");
INSERT INTO `detail_pemesanan` VALUES("26","8","kamar","6","1","650000.00");
INSERT INTO `detail_pemesanan` VALUES("27","8","fasilitas","4","1","25000.00");
INSERT INTO `detail_pemesanan` VALUES("28","9","kamar","7","1","650000.00");
INSERT INTO `detail_pemesanan` VALUES("29","9","fasilitas","1","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("30","9","fasilitas","3","1","30000.00");
INSERT INTO `detail_pemesanan` VALUES("31","9","fasilitas","4","1","25000.00");
INSERT INTO `detail_pemesanan` VALUES("32","9","fasilitas","5","1","50000.00");
INSERT INTO `detail_pemesanan` VALUES("33","9","fasilitas","7","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("34","9","fasilitas","8","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("35","10","kamar","7","1","650000.00");
INSERT INTO `detail_pemesanan` VALUES("36","10","fasilitas","1","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("37","10","fasilitas","3","1","30000.00");
INSERT INTO `detail_pemesanan` VALUES("38","10","fasilitas","4","1","25000.00");
INSERT INTO `detail_pemesanan` VALUES("39","10","fasilitas","5","1","50000.00");
INSERT INTO `detail_pemesanan` VALUES("40","10","fasilitas","7","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("41","10","fasilitas","8","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("42","11","kamar","8","1","650000.00");
INSERT INTO `detail_pemesanan` VALUES("43","11","fasilitas","1","2","0.00");
INSERT INTO `detail_pemesanan` VALUES("44","11","fasilitas","3","2","30000.00");
INSERT INTO `detail_pemesanan` VALUES("45","11","fasilitas","4","2","25000.00");
INSERT INTO `detail_pemesanan` VALUES("46","11","fasilitas","5","1","50000.00");
INSERT INTO `detail_pemesanan` VALUES("47","11","fasilitas","7","1","0.00");
INSERT INTO `detail_pemesanan` VALUES("48","11","fasilitas","8","1","0.00");


DROP TABLE IF EXISTS `fasilitas`;
CREATE TABLE `fasilitas` (
  `id_fasilitas` bigint NOT NULL AUTO_INCREMENT,
  `kode_fasilitas` varchar(50) NOT NULL,
  `nama_fasilitas` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stok` int NOT NULL DEFAULT '1',
  `foto` text,
  `sl_fasilitas` text NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_fasilitas`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `fasilitas` VALUES("1","F1","meja belajar","Meja Belajar ukuran 1x1 dengan laci, dan tempat menyimpan pc","0.00","47","fasilitas_68c914340f7b60.50307478.jpg","d1381f4afee6ba395e3965a806371c86bd62d146ea7ec36e528e49be6bedaead","0");
INSERT INTO `fasilitas` VALUES("3","F3","lemari","lemari baju","30000.00","47","fasilitas_68c9145d515b31.45767087.jpeg","78e45a08a0f2be8ce5d2923b77e6481cbceff2b3faebf02a3e8f75d57ebfd2a9","0");
INSERT INTO `fasilitas` VALUES("4","F4","kasur lantai","Kasur busa dengan ukuran 200cm x 80cm dengan ketebalan 10cm lengkap dengan bantal, guling dan selimut","25000.00","45","fasilitas_68c917d06b74c5.03747146.jpeg","b52df9ff029171da9a9705412d7b97962d9832fc2d2a60c9cd66997b67f7568a","0");
INSERT INTO `fasilitas` VALUES("5","F5","wifi","wifi ","50000.00","48","fasilitas_68c914879ca700.02763864.png","e88e770ab4adb7d6e7408be0dbca7541ca0f8d902ef41b57e2decbb4dd72f450","0");
INSERT INTO `fasilitas` VALUES("7","F2","kursi","Kursi Plastik warna biru","0.00","48","fasilitas_68ce626d640f97.21488486.jpg","9a26f1c42b57a7b66fe51af6a84ed055fbe02006951fcae84b4063e6629cb8c9","0");
INSERT INTO `fasilitas` VALUES("8","F6","parkir","parkiran basement","0.00","9997","fasilitas_68ce7189a23955.66455164.jpeg","04eb32f6a094551bbdbb932520d5c02508f9c24b4736c936b4ac53de52d86db3","0");


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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `kamar` VALUES("5","A1","kamar khusus perempuan berukuran 10x10, dilengkapi dengan listrik, kamar mandi dan dapur.","650000.00","Terisi","Perempuan","kamar_68ca8c190f4510.96824004.jpeg","e51fe92b11310291ab3351798574f4493539064f6cfb466374659800edf39383");
INSERT INTO `kamar` VALUES("6","A2","kamar khusus perempuan berukuran 10x10, dilengkapi dengan listrik, kamar mandi dan dapur.","650000.00","Terisi","Perempuan","kamar_68ca8c2e1efd06.96093767.jpeg","514ba0e5b1656184a71cc2c7d9cf3928eeaafc3456c3bbbaa0abcd335147e13f");
INSERT INTO `kamar` VALUES("7","A3","kamar khusus perempuan berukuran 10x10, dilengkapi dengan listrik, kamar mandi dan dapur.","650000.00","Terisi","Perempuan","kamar_68ca8c461f3058.65151402.jpeg","e7923b4034cd4e24198fd40c0ecea8ab04eec06ee7ae98322a34336ef00d3ba3");
INSERT INTO `kamar` VALUES("8","A4","kamar khusus perempuan berukuran 10x10, dilengkapi dengan listrik, kamar mandi dan dapur.","650000.00","Terisi","Perempuan","kamar_68ca8c55d445d1.83466076.jpeg","7a97f7ea9ea00c1acce51aed57d057ce18db3bb6e297006d543c092f30a404a6");
INSERT INTO `kamar` VALUES("9","A5","kamar khusus perempuan berukuran 10x10, dilengkapi dengan listrik, kamar mandi dan dapur.","750000.00","Kosong","Perempuan","kamar_68d77348d64f01.96399383.jpeg","2cd495c08ab6ac7cc9b16ef8a33dd43cfdf2a1579383b1a77bb5454c5d6e4e11");


DROP TABLE IF EXISTS `pembayaran`;
CREATE TABLE `pembayaran` (
  `id_pembayaran` bigint NOT NULL AUTO_INCREMENT,
  `id_pemesanan` bigint NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah_bayar` decimal(12,2) DEFAULT NULL,
  `bukti_transaksi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `status` enum('Belum Lunas','Lunas') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Belum Lunas',
  `dikonfirmasi_oleh` bigint DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`),
  KEY `id_pemesanan` (`id_pemesanan`),
  KEY `dikonfirmasi_oleh` (`dikonfirmasi_oleh`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`dikonfirmasi_oleh`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `pembayaran` VALUES("7","2","2025-09-27","675000.00","","Lunas","");
INSERT INTO `pembayaran` VALUES("8","1","2025-09-27","755000.00","","Lunas","");
INSERT INTO `pembayaran` VALUES("9","5","2025-09-27","755000.00","","Lunas","");
INSERT INTO `pembayaran` VALUES("10","6","2025-09-27","650000.00","","Lunas","");
INSERT INTO `pembayaran` VALUES("11","7","2025-10-01","675000.00","","Lunas","");
INSERT INTO `pembayaran` VALUES("13","9","2025-10-01","755000.00","","Lunas","");
INSERT INTO `pembayaran` VALUES("16","8","2025-10-01","675000.00","","Lunas","");
INSERT INTO `pembayaran` VALUES("18","10","2025-10-04","755000.00","","Lunas","1");
INSERT INTO `pembayaran` VALUES("19","11","2025-10-07","810000.00","","Lunas","1");


DROP TABLE IF EXISTS `pemesanan`;
CREATE TABLE `pemesanan` (
  `id_pemesanan` bigint NOT NULL AUTO_INCREMENT,
  `id_user` bigint NOT NULL,
  `tanggal_pesan` date DEFAULT NULL,
  `tanggal_mulai_kontrak` date DEFAULT NULL,
  `tanggal_akhir_kontrak` date DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `biaya_bulanan` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status_kontrak` enum('Menunggu','Aktif','Selesai','Dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Menunggu',
  PRIMARY KEY (`id_pemesanan`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `pemesanan` VALUES("1","9","2025-08-27","2025-08-27","2025-09-27","755000.00","755000.00","Selesai");
INSERT INTO `pemesanan` VALUES("2","6","2025-08-27","2025-08-27","2025-09-27","675000.00","675000.00","Selesai");
INSERT INTO `pemesanan` VALUES("5","5","2025-08-27","2025-08-27","2025-09-27","755000.00","755000.00","Selesai");
INSERT INTO `pemesanan` VALUES("6","3","2025-08-27","2025-08-27","2025-09-27","650000.00","650000.00","Selesai");
INSERT INTO `pemesanan` VALUES("7","9","2025-10-01","2025-10-01","2025-10-31","675000.00","675000.00","Aktif");
INSERT INTO `pemesanan` VALUES("8","9","2025-10-01","2025-10-01","2025-10-31","675000.00","675000.00","Aktif");
INSERT INTO `pemesanan` VALUES("9","6","2025-10-01","2025-10-01","2025-10-02","755000.00","755000.00","Selesai");
INSERT INTO `pemesanan` VALUES("10","5","2025-10-03","2025-10-04","2025-11-03","755000.00","755000.00","Aktif");
INSERT INTO `pemesanan` VALUES("11","3","2025-10-07","2025-10-08","2025-11-07","810000.00","810000.00","Aktif");


DROP TABLE IF EXISTS `pengaturan`;
CREATE TABLE `pengaturan` (
  `id_pengaturan` int NOT NULL AUTO_INCREMENT,
  `nama_pengaturan` varchar(50) NOT NULL,
  `nilai_pengaturan` datetime NOT NULL,
  PRIMARY KEY (`id_pengaturan`),
  UNIQUE KEY `nama_pengaturan` (`nama_pengaturan`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `pengaturan` VALUES("1","terakhir_cek_checkout","2025-10-11 13:45:12");


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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user` VALUES("1","andra@admin.com","$2y$10$IQANylhjmFDqKPmmGwUtkuA40w.n91iLCDnGoGYdJa9xfi4J1psae","Andra Setiawan","Admin","0","e229f5613f5c353ebe9ac290106833c1afda9ac793482dd3de9bcdca0744bfb2","3fb8b228d612d4ad9ec5ddbd1b5f76b19787d729b05fbea337df58df28f14046");
INSERT INTO `user` VALUES("2","antasena@admin.com","$2y$10$GoLDjlkH4lHkhmENbJdR2OZhc9uD429nNvhvCX8QIqXQnjHyq98Pu","Antasena putra","Admin","0","","5a0b1f9fbdbc3b15f51601d9a656256ba9974567f7c7ad513d73dc5e4884edbf");
INSERT INTO `user` VALUES("3","viona@kost.com","$2y$10$AauCI.lrzTrfuLMb2vCgL.sPp9WZSZWyAAwTVUs/WQFz68.OjX2v2","Nathasya Viona","User","0","","f3888e7f8a3323e48d098351ddf98670209da7bcd6e2c3aa947a9a2d882fac2e");
INSERT INTO `user` VALUES("4","indra@admin.com","$2y$10$POEkUANjULOZCOjlwyCP9uNyWwBcgBKAfhOZpNuqj2jWHxBYuR4Ry","Nurindra Setiawan","Admin","2","","2e640f53cd90e5ebe796cb6a715a6b94551af61f27b3f09696fca55ce5031c4c");
INSERT INTO `user` VALUES("5","nurul@kost.com","$2y$10$ALmeOAuSaD3pm3/0FU9sju4lqIE4nDdDZ71ULPgHtDpTFfADxJb1K","Nurul Maryam","User","0","","caffe24a470c5572d75c806efa59119babf325b0353dcfaff36f1901a5665a52");
INSERT INTO `user` VALUES("6","percobaan@kost.com","$2y$10$ul5P9YxLzmtqix8JozBuNe83enemsRcy22LIWF6Io7oWY.UmofhBq","coba kost","User","0","","68f7213c847c99a64d482e53a868e82394f1b8d0244c46318ca7667e2303ac45");
INSERT INTO `user` VALUES("9","asep@kost.com","$2y$10$gDgmG/Wdehev3jCOj9DaL.7pz8HHJXo6X9Q0vqf9t0NeNKMoAol8q","asep cahyadi","User","0","","32bd479a6c69ae81ac6b89319541d848cf9fce3b0662d37fd4a915e9ad0d5474");
INSERT INTO `user` VALUES("10","kurnia@kost.com","$2y$10$qLGbRMVNobhmGDdOJ33eYu5Oz2MCVAgpTXWyWHOKDQ7S6ZcfH1osu","Kurnia meiga","User","2","","0b53f1e09dc70531bc77bb99e67bbbc2e8590f3a6eac3d6300383d67926f66e1");


SET FOREIGN_KEY_CHECKS = 1;
