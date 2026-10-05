-- Tabel Maintenance (jadwal maintenance jaringan; halaman & fitur seperti RFO)
-- perusahaan dipilih dari dropdown; logo = logo bawaan perusahaan (public/Logo)
-- maintenance_id dibuat otomatis oleh aplikasi: MT-ddmmyyyy-NNN (urutan reset tiap hari)
CREATE TABLE IF NOT EXISTS `md_maintenance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `maintenance_id` varchar(30) NOT NULL,
  `perusahaan` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL COMMENT 'path logo di public/, mis. Logo/Sprintnet.png',
  `tanggal` date NOT NULL COMMENT 'hari/tanggal maintenance',
  `waktu_mulai` time NOT NULL COMMENT 'waktu awal maintenance',
  `waktu_selesai` time NOT NULL COMMENT 'waktu selesai maintenance',
  `estimasi` varchar(150) NOT NULL COMMENT 'isi manual, mis. 2 jam',
  `kegiatan` text NOT NULL COMMENT 'isi manual',
  `impact` text NOT NULL COMMENT 'isi manual',
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_maintenance_id` (`maintenance_id`),
  KEY `idx_tanggal` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
