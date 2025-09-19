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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `fasilitas` VALUES("1","F1","meja belajar","Meja Belajar ukuran 1x1 dengan laci, dan tempat menyimpan pc","0.00","50","fasilitas_68c914340f7b60.50307478.jpg","d1381f4afee6ba395e3965a806371c86bd62d146ea7ec36e528e49be6bedaead","0");
INSERT INTO `fasilitas` VALUES("2","F2","kursi","kursi plastik warna biru","0.00","50","fasilitas_68c9160d784374.73455761.jpg","623ce0fb3b03fc6ac5bc1ce5b95704843fd4719406cf9853426a377dd7e34302","0");
INSERT INTO `fasilitas` VALUES("3","F3","lemari","lemari baju","30000.00","50","fasilitas_68c9145d515b31.45767087.jpeg","78e45a08a0f2be8ce5d2923b77e6481cbceff2b3faebf02a3e8f75d57ebfd2a9","0");
INSERT INTO `fasilitas` VALUES("4","F4","kasur lantai","Kasur busa dengan ukuran 200cm x 80cm dengan ketebalan 10cm lengkap dengan bantal, guling dan selimut","25000.00","50","fasilitas_68c917d06b74c5.03747146.jpeg","b52df9ff029171da9a9705412d7b97962d9832fc2d2a60c9cd66997b67f7568a","0");
INSERT INTO `fasilitas` VALUES("5","F5","wifi","wifi ","50000.00","50","fasilitas_68c914879ca700.02763864.png","e88e770ab4adb7d6e7408be0dbca7541ca0f8d902ef41b57e2decbb4dd72f450","0");
INSERT INTO `fasilitas` VALUES("6","F6","parkir","Parkiran motor","0.00","999999","fasilitas_68c917bfc4f6d4.52128521.jpeg","4229dd6034da21237e78644d6ac1ecf786caaa0748239c80d1695035f8c770f2","0");


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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `kamar` VALUES("5","A1","kamar khusus perempuan berukuran 10x10, dilengkapi dengan listrik, kamar mandi dan dapur.","650000.00","Kosong","Perempuan","kamar_68ca8c190f4510.96824004.jpeg","70a127d9b3862c9f06fc407e5b27595b828226bca2a8133a1f01326c4acc2f1e");
INSERT INTO `kamar` VALUES("6","A2","kamar khusus perempuan berukuran 10x10, dilengkapi dengan listrik, kamar mandi dan dapur.","650000.00","Kosong","Perempuan","kamar_68ca8c2e1efd06.96093767.jpeg","514ba0e5b1656184a71cc2c7d9cf3928eeaafc3456c3bbbaa0abcd335147e13f");
INSERT INTO `kamar` VALUES("7","A3","kamar khusus perempuan berukuran 10x10, dilengkapi dengan listrik, kamar mandi dan dapur.","650000.00","Kosong","Perempuan","kamar_68ca8c461f3058.65151402.jpeg","e7923b4034cd4e24198fd40c0ecea8ab04eec06ee7ae98322a34336ef00d3ba3");
INSERT INTO `kamar` VALUES("8","A4","kamar khusus perempuan berukuran 10x10, dilengkapi dengan listrik, kamar mandi dan dapur.","650000.00","Kosong","Perempuan","kamar_68ca8c55d445d1.83466076.jpeg","7a97f7ea9ea00c1acce51aed57d057ce18db3bb6e297006d543c092f30a404a6");


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
  `id_kamar` bigint DEFAULT NULL,
  `id_fasilitas` bigint DEFAULT NULL,
  `tanggal_pesan` date NOT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `durasi` int NOT NULL COMMENT 'Dalam bulan',
  `total` decimal(12,2) NOT NULL,
  `status` enum('Pending','Diterima','Ditolak') DEFAULT 'Pending',
  PRIMARY KEY (`id_pemesanan`),
  KEY `id_user` (`id_user`),
  KEY `id_kamar` (`id_kamar`),
  KEY `id_fasilitas` (`id_fasilitas`),
  CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`),
  CONSTRAINT `pemesanan_ibfk_3` FOREIGN KEY (`id_fasilitas`) REFERENCES `fasilitas` (`id_fasilitas`) ON DELETE SET NULL ON UPDATE CASCADE
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user` VALUES("1","andra@admin.com","$2y$10$IQANylhjmFDqKPmmGwUtkuA40w.n91iLCDnGoGYdJa9xfi4J1psae","Andra Setiawan","Admin","0","69fb9015712ca37a1a096454982bea32c693b4c903d5b93865c9cb8daf5f3d2a","3fb8b228d612d4ad9ec5ddbd1b5f76b19787d729b05fbea337df58df28f14046");
INSERT INTO `user` VALUES("2","antasena@admin.com","$2y$10$GoLDjlkH4lHkhmENbJdR2OZhc9uD429nNvhvCX8QIqXQnjHyq98Pu","Antasena putra","Admin","0","","5a0b1f9fbdbc3b15f51601d9a656256ba9974567f7c7ad513d73dc5e4884edbf");
INSERT INTO `user` VALUES("3","viona@kost.com","$2y$10$AauCI.lrzTrfuLMb2vCgL.sPp9WZSZWyAAwTVUs/WQFz68.OjX2v2","Nathasya Viona","User","0","","f3888e7f8a3323e48d098351ddf98670209da7bcd6e2c3aa947a9a2d882fac2e");
INSERT INTO `user` VALUES("4","indra@admin.com","$2y$10$POEkUANjULOZCOjlwyCP9uNyWwBcgBKAfhOZpNuqj2jWHxBYuR4Ry","Nurindra Setiawan","Admin","0","","2e640f53cd90e5ebe796cb6a715a6b94551af61f27b3f09696fca55ce5031c4c");
INSERT INTO `user` VALUES("5","nurul@kost.com","$2y$10$ALmeOAuSaD3pm3/0FU9sju4lqIE4nDdDZ71ULPgHtDpTFfADxJb1K","Nurul Maryam","User","0","","caffe24a470c5572d75c806efa59119babf325b0353dcfaff36f1901a5665a52");
INSERT INTO `user` VALUES("6","percobaan@kost.com","$2y$10$ul5P9YxLzmtqix8JozBuNe83enemsRcy22LIWF6Io7oWY.UmofhBq","coba kost","User","0","","68f7213c847c99a64d482e53a868e82394f1b8d0244c46318ca7667e2303ac45");
INSERT INTO `user` VALUES("9","asep@kost.com","$2y$10$gDgmG/Wdehev3jCOj9DaL.7pz8HHJXo6X9Q0vqf9t0NeNKMoAol8q","asep cahyadi","User","0","","32bd479a6c69ae81ac6b89319541d848cf9fce3b0662d37fd4a915e9ad0d5474");
INSERT INTO `user` VALUES("10","kurnia@kost.com","$2y$10$qLGbRMVNobhmGDdOJ33eYu5Oz2MCVAgpTXWyWHOKDQ7S6ZcfH1osu","Kurnia meiga","User","0","","0b53f1e09dc70531bc77bb99e67bbbc2e8590f3a6eac3d6300383d67926f66e1");


SET FOREIGN_KEY_CHECKS = 1;
