-- Tabel Aktivasi per History Report (tab Aktivasi di halaman Shift Handover)
-- Semua kolom diisi manual: id pelanggan, nama pelanggan, kapasitas (Mbps), SN, tanggal aktivasi
-- report_id: baris d_report pemiliknya; ikut terhapus bila report dihapus
CREATE TABLE IF NOT EXISTS `d_aktivasi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(11) unsigned NOT NULL,
  `id_pelanggan` varchar(100) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `kapasitas_mbps` varchar(50) NOT NULL,
  `sn` varchar(255) NOT NULL,
  `tanggal_aktivasi` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_report` (`report_id`),
  CONSTRAINT `fk_aktivasi_report` FOREIGN KEY (`report_id`) REFERENCES `d_report` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
