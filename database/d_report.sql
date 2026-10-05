-- Tabel History Report (laporan per shift NOC)
-- pic_shift: diisi dari jadwal_noc (tanggal + shift) atau manual; bisa lebih dari satu nama
-- Shift Pagi/Siang/Malam = Shift 1/2/3 pada jadwal_noc
CREATE TABLE IF NOT EXISTS `d_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `shift` enum('Pagi','Siang','Malam') NOT NULL,
  `pic_shift` varchar(255) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ringkasan` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_tanggal_shift` (`tanggal`, `shift`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
