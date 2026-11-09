-- Tabel NetMon (menu All NOC > NetMon): kumpulan link website monitoring jaringan
-- judul: nama yang tampil di kartu, link: alamat website (http/https) yang dibuka tombol "Buka Link"
CREATE TABLE IF NOT EXISTS `md_netmon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(150) NOT NULL,
  `link` varchar(500) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
