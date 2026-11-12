-- Data IP Public (menu Data Teknis NOC > Data IP Public), bertingkat:
--   md_ip_public_judul    : Perusahaan (PT Juragan Wifi Indonesia / PT Anagram Data Teknologi / PT Norlec Telekomunikasi Indonesia) + Judul (isi manual)
--   md_ip_public_subjudul : Sub Judul di bawah sebuah Judul (Judul dipilih dari dropdown)
--   d_ip_public_subnet    : Subnet + Subnet Description di bawah Judul (dan Sub Judul bila dipilih); Perusahaan ikut Judul
-- Tidak boleh dobel: judul per perusahaan, sub judul per judul, dan subnet (di semua data) - dijaga juga oleh aplikasi.
-- Judul / Sub Judul yang masih dipakai tidak bisa dihapus (dicek oleh aplikasi).
CREATE TABLE IF NOT EXISTS `md_ip_public_judul` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `perusahaan` varchar(100) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_perusahaan_judul` (`perusahaan`, `judul`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `md_ip_public_subjudul` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `judul_id` int(11) unsigned NOT NULL COMMENT 'md_ip_public_judul.id',
  `sub_judul` varchar(150) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_judul_subjudul` (`judul_id`, `sub_judul`),
  KEY `idx_judul_id` (`judul_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `d_ip_public_subnet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `judul_id` int(11) unsigned NOT NULL COMMENT 'md_ip_public_judul.id',
  `subjudul_id` int(11) unsigned DEFAULT NULL COMMENT 'md_ip_public_subjudul.id (opsional)',
  `subnet` varchar(50) NOT NULL COMMENT 'contoh 103.10.20.0/29',
  `deskripsi` text DEFAULT NULL COMMENT 'Subnet Description',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_subnet` (`subnet`),
  KEY `idx_judul_id` (`judul_id`),
  KEY `idx_subjudul_id` (`subjudul_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

