-- Riwayat pengiriman surat Maintenance lewat email (sama dengan d_rfo_email_log; akun pengirim lihat app/Config/RfoEmail.php)
-- Satu baris per Maintenance yang dikirim; status gagal menyimpan pesan error SMTP
CREATE TABLE IF NOT EXISTS `md_maintenance_email_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mt_id` int(11) unsigned NOT NULL COMMENT 'md_maintenance.id',
  `dari` varchar(100) DEFAULT NULL COMMENT 'akun pengirim',
  `kepada` text NOT NULL,
  `cc` text DEFAULT NULL,
  `subjek` varchar(255) NOT NULL,
  `lampiran` text DEFAULT NULL COMMENT 'nama file lampiran, pisah koma',
  `body` mediumtext DEFAULT NULL COMMENT 'isi body email (HTML) yang dikirim',
  `status` enum('Terkirim','Gagal') NOT NULL,
  `pesan_error` text DEFAULT NULL,
  `dikirim_oleh` varchar(100) DEFAULT NULL COMMENT 'username yang mengirim',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_mt_id` (`mt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
