-- Tabel RFO (Reason For Outage)
-- perusahaan dipilih dari dropdown; logo = logo bawaan perusahaan (public/Logo)
-- rfo_id dibuat otomatis oleh aplikasi: RFO-ddmmyyyy-NNN (urutan reset tiap hari)
CREATE TABLE IF NOT EXISTS `d_rfo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rfo_id` varchar(30) NOT NULL,
  `perusahaan` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL COMMENT 'path logo di public/, mis. Logo/Sprintnet.png',
  `waktu_gangguan` datetime NOT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `penyebab` text NOT NULL,
  `impact` text NOT NULL,
  `action` text NOT NULL,
  `status` enum('Resolved','Monitoring','On Progress','Pending') NOT NULL DEFAULT 'On Progress',
  `petugas` varchar(255) DEFAULT NULL COMMENT 'nama petugas NOC (dari jadwal_noc)',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_rfo_id` (`rfo_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
