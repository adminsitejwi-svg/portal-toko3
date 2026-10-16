-- Tabel Data Center (menu Data Center di sidebar)
-- kode_dc: kode data center, unik
CREATE TABLE IF NOT EXISTS `md_data_center` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_dc` varchar(255) NOT NULL,
  `kode_dc` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kode_dc` (`kode_dc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
