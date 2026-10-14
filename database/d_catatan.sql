-- Tabel Catatan per History Report (tab Catatan di halaman Shift Handover)
-- grup: pilihan sama dengan d_gangguan ditambah UMUM; priority sama dengan d_gangguan
-- catatan: daftar poin catatan dalam format JSON array, contoh ["cek ulang jam 14:00","info ke vendor"]
-- report_id: baris d_report pemiliknya; ikut terhapus bila report dihapus
-- asal_id: id baris asal di shift sebelumnya bila hasil duplikat report; perubahan priority ikut diterapkan ke baris asal
-- Database yang sudah ada: ALTER TABLE d_catatan ADD COLUMN asal_id INT(11) UNSIGNED NULL AFTER report_id, ADD KEY idx_asal (asal_id);
CREATE TABLE IF NOT EXISTS `d_catatan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(11) unsigned NOT NULL,
  `asal_id` int(11) unsigned DEFAULT NULL,
  `grup` enum('RETAIL','CORPORATE','RNET GROUP','BACKBONE','UMUM') NOT NULL,
  `kategori` enum('Info','Note','Recurring') NOT NULL,
  `priority` enum('High','Medium','Low') NOT NULL,
  `status` enum('Open','Done') NOT NULL DEFAULT 'Open',
  `judul` varchar(255) NOT NULL,
  `catatan` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_report` (`report_id`),
  KEY `idx_asal` (`asal_id`),
  CONSTRAINT `fk_catatan_report` FOREIGN KEY (`report_id`) REFERENCES `d_report` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
