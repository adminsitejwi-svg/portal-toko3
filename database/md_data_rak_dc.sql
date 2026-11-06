-- Tabel halaman Data Rak DC (menu All NOC > Data Rak DC)
-- Relasi: md_data_center_dc 1-n md_data_rak 1-n md_data_perangkat
-- ON DELETE RESTRICT: data center yang masih punya rak & rak yang masih punya perangkat tidak bisa dihapus
-- Tabel ini terpisah dari md_data_center (menu Data Center, dipakai Data Interkoneksi)

CREATE TABLE IF NOT EXISTS `md_data_center_dc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kode_dc` varchar(50) NOT NULL,
  `nama_dc` varchar(150) NOT NULL,
  `alamat` text DEFAULT NULL,
  `gedung` varchar(100) DEFAULT NULL,
  `lantai` varchar(50) DEFAULT NULL,
  `pic` varchar(150) DEFAULT NULL,
  `kontak` varchar(30) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kode_dc` (`kode_dc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ukuran_u: tinggi rak dalam satuan U (mis. 42.00); kode_rak unik per data center
CREATE TABLE IF NOT EXISTS `md_data_rak` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `data_center_id` int(11) unsigned NOT NULL,
  `kode_rak` varchar(50) NOT NULL,
  `nama_rak` varchar(100) NOT NULL,
  `ukuran_u` decimal(5,2) NOT NULL,
  `tinggi_cm` decimal(8,2) DEFAULT NULL,
  `lebar_cm` decimal(8,2) DEFAULT NULL,
  `kedalaman_cm` decimal(8,2) DEFAULT NULL,
  `lokasi_rak` varchar(150) DEFAULT NULL,
  `kapasitas_listrik` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kode_rak_dc` (`data_center_id`, `kode_rak`),
  CONSTRAINT `fk_rak_data_center` FOREIGN KEY (`data_center_id`) REFERENCES `md_data_center_dc` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- posisi_u: U paling bawah yang ditempati (mis. posisi 5, ukuran 2 = U5-U6); NULL = belum ditempatkan di rak
CREATE TABLE IF NOT EXISTS `md_data_perangkat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `data_rak_id` int(11) unsigned NOT NULL,
  `kode_perangkat` varchar(50) NOT NULL,
  `nama_perangkat` varchar(150) NOT NULL,
  `jenis_perangkat` varchar(100) NOT NULL,
  `merk` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `serial_number` varchar(150) DEFAULT NULL,
  `ukuran_u` decimal(5,2) NOT NULL,
  `posisi_u` decimal(5,2) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `mac_address` varchar(50) DEFAULT NULL,
  `tanggal_pasang` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kode_perangkat` (`kode_perangkat`),
  KEY `idx_data_rak_id` (`data_rak_id`),
  CONSTRAINT `fk_perangkat_rak` FOREIGN KEY (`data_rak_id`) REFERENCES `md_data_rak` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
