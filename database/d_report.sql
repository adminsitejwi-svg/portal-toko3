-- Tabel History Report (laporan per shift NOC)
-- pic_shift: diisi dari jadwal_noc (tanggal + shift) atau manual; bisa lebih dari satu nama
-- Shift Pagi/Siang/Malam = Shift 1/2/3 pada jadwal_noc
-- kategori: NOC Corp Dan Retail / NOC Alfa Grup (data lama bernilai NULL)
-- Database yang sudah ada: ALTER TABLE d_report ADD COLUMN kategori ENUM('NOC Corp Dan Retail','NOC Alfa Grup') NULL AFTER shift;
CREATE TABLE IF NOT EXISTS `d_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `shift` enum('Pagi','Siang','Malam') NOT NULL,
  `pic_shift` varchar(255) NOT NULL,
  `kategori` enum('NOC Corp Dan Retail','NOC Alfa Grup') DEFAULT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ringkasan` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_tanggal_shift` (`tanggal`, `shift`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
