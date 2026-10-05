-- Tabel Gangguan per History Report (dibuka dari tombol Settings di halaman History Report)
-- report_id: baris d_report pemiliknya; ikut terhapus bila report dihapus
CREATE TABLE IF NOT EXISTS `d_gangguan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(11) unsigned NOT NULL,
  `grup` enum('RETAIL','CORPORATE','RNET GROUP','BACKBONE') NOT NULL,
  `sub_grup` varchar(255) DEFAULT NULL,
  `customer_site` varchar(255) NOT NULL,
  `cid_ticket` varchar(255) DEFAULT NULL,
  `status` enum('Resolved','On Progress','Monitoring','Down','Pending') NOT NULL,
  `priority` enum('High','Medium','Low') NOT NULL,
  `gangguan` text NOT NULL,
  `tindakan` text DEFAULT NULL,
  `next_action` text DEFAULT NULL,
  `pic` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_report` (`report_id`),
  CONSTRAINT `fk_gangguan_report` FOREIGN KEY (`report_id`) REFERENCES `d_report` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
