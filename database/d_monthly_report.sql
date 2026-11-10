-- Monthly Report (menu Report NOC > Monthly Report)
-- Duration (jam & menit) dihitung otomatis oleh aplikasi dari Start Time - Finish Time,
-- begitu juga Duration Using Backup dari Start Time - Finish Time backup; disimpan dalam menit.
CREATE TABLE IF NOT EXISTS `d_monthly_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client` varchar(150) NOT NULL,
  `start_time` datetime NOT NULL,
  `finish_time` datetime NOT NULL,
  `durasi_menit` int(11) unsigned NOT NULL DEFAULT 0,
  `jenis_layanan` varchar(100) NOT NULL,
  `problem` text NOT NULL,
  `action` text NOT NULL,
  `note` text DEFAULT NULL,
  `solver` varchar(150) NOT NULL,
  `backup_start` datetime DEFAULT NULL,
  `backup_finish` datetime DEFAULT NULL,
  `backup_durasi_menit` int(11) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_start_time` (`start_time`),
  KEY `idx_client` (`client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
