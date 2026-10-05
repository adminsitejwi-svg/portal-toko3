-- Tabel Follow Up per History Report (tab Follow Up di halaman Shift Handover)
-- grup & priority: pilihan sama dengan d_gangguan
-- report_id: baris d_report pemiliknya; ikut terhapus bila report dihapus
CREATE TABLE IF NOT EXISTS `d_follow_up` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(11) unsigned NOT NULL,
  `grup` enum('RETAIL','CORPORATE','RNET GROUP','BACKBONE') NOT NULL,
  `customer_site` varchar(255) NOT NULL,
  `priority` enum('High','Medium','Low') NOT NULL,
  `due_date` date DEFAULT NULL,
  `pic` varchar(255) NOT NULL,
  `issue` text NOT NULL,
  `action` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_report` (`report_id`),
  CONSTRAINT `fk_follow_up_report` FOREIGN KEY (`report_id`) REFERENCES `d_report` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
