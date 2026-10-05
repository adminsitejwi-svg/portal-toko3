-- Tabel Catatan per History Report (tab Catatan di halaman Shift Handover)
-- grup: pilihan sama dengan d_gangguan ditambah UMUM; priority sama dengan d_gangguan
-- catatan: daftar poin catatan dalam format JSON array, contoh ["cek ulang jam 14:00","info ke vendor"]
-- report_id: baris d_report pemiliknya; ikut terhapus bila report dihapus
CREATE TABLE IF NOT EXISTS `d_catatan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(11) unsigned NOT NULL,
  `grup` enum('RETAIL','CORPORATE','RNET GROUP','BACKBONE','UMUM') NOT NULL,
  `kategori` enum('Info','Note','Recurring') NOT NULL,
  `priority` enum('High','Medium','Low') NOT NULL,
  `status` enum('Open','Done') NOT NULL DEFAULT 'Open',
  `judul` varchar(255) NOT NULL,
  `catatan` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_report` (`report_id`),
  CONSTRAINT `fk_catatan_report` FOREIGN KEY (`report_id`) REFERENCES `d_report` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
