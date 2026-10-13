-- Riwayat pengiriman surat RFO lewat email (akun pengirim dipilih di form, lihat app/Config/RfoEmail.php)
-- Satu baris per RFO yang dikirim; status gagal menyimpan pesan error SMTP
CREATE TABLE IF NOT EXISTS `d_rfo_email_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rfo_id` int(11) unsigned NOT NULL COMMENT 'd_rfo.id',
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
  KEY `idx_rfo_id` (`rfo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Upgrade tabel lama: tambah kolom lampiran & dari
-- ALTER TABLE `d_rfo_email_log` ADD COLUMN `lampiran` text DEFAULT NULL COMMENT 'nama file lampiran, pisah koma' AFTER `subjek`;
-- ALTER TABLE `d_rfo_email_log` ADD COLUMN `dari` varchar(100) DEFAULT NULL COMMENT 'akun pengirim' AFTER `rfo_id`;
-- ALTER TABLE `d_rfo_email_log` ADD COLUMN `body` mediumtext DEFAULT NULL COMMENT 'isi body email (HTML) yang dikirim' AFTER `lampiran`;
