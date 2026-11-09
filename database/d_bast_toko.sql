-- BAST toko (menu Activation > BAST > Alfamidi / Lawson / Alfamart / Dandan)
-- Struktur sama persis dengan d_bast_corporate (lihat database/d_bast_corporate.sql); satu tabel per jenis BAST.
-- Dokumen tersimpan di writable/uploads/bast_alfamidi, bast_lawson, bast_alfamart, bast_dandan.

CREATE TABLE IF NOT EXISTS `d_bast_alfamidi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `no_bast` varchar(100) DEFAULT NULL,
  `tanggal_bast` date DEFAULT NULL,
  `perusahaan` varchar(150) DEFAULT NULL,
  `layanan` varchar(150) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `file_simpan` varchar(100) NOT NULL,
  `format` varchar(5) NOT NULL,
  `ukuran` bigint(20) unsigned NOT NULL,
  `hash_sha1` char(40) NOT NULL,
  `diupload_oleh` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_no_bast_alfamidi` (`no_bast`),
  UNIQUE KEY `uniq_hash_bast_alfamidi` (`hash_sha1`),
  UNIQUE KEY `uniq_file_bast_alfamidi` (`file_simpan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `d_bast_lawson` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `no_bast` varchar(100) DEFAULT NULL,
  `tanggal_bast` date DEFAULT NULL,
  `perusahaan` varchar(150) DEFAULT NULL,
  `layanan` varchar(150) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `file_simpan` varchar(100) NOT NULL,
  `format` varchar(5) NOT NULL,
  `ukuran` bigint(20) unsigned NOT NULL,
  `hash_sha1` char(40) NOT NULL,
  `diupload_oleh` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_no_bast_lawson` (`no_bast`),
  UNIQUE KEY `uniq_hash_bast_lawson` (`hash_sha1`),
  UNIQUE KEY `uniq_file_bast_lawson` (`file_simpan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `d_bast_alfamart` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `no_bast` varchar(100) DEFAULT NULL,
  `tanggal_bast` date DEFAULT NULL,
  `perusahaan` varchar(150) DEFAULT NULL,
  `layanan` varchar(150) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `file_simpan` varchar(100) NOT NULL,
  `format` varchar(5) NOT NULL,
  `ukuran` bigint(20) unsigned NOT NULL,
  `hash_sha1` char(40) NOT NULL,
  `diupload_oleh` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_no_bast_alfamart` (`no_bast`),
  UNIQUE KEY `uniq_hash_bast_alfamart` (`hash_sha1`),
  UNIQUE KEY `uniq_file_bast_alfamart` (`file_simpan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `d_bast_dandan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `no_bast` varchar(100) DEFAULT NULL,
  `tanggal_bast` date DEFAULT NULL,
  `perusahaan` varchar(150) DEFAULT NULL,
  `layanan` varchar(150) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `file_simpan` varchar(100) NOT NULL,
  `format` varchar(5) NOT NULL,
  `ukuran` bigint(20) unsigned NOT NULL,
  `hash_sha1` char(40) NOT NULL,
  `diupload_oleh` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_no_bast_dandan` (`no_bast`),
  UNIQUE KEY `uniq_hash_bast_dandan` (`hash_sha1`),
  UNIQUE KEY `uniq_file_bast_dandan` (`file_simpan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
