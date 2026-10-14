-- Tabel Maintenance per History Report (tab Maintenance di halaman Shift Handover)
-- grup: pilihan sama dengan d_gangguan
-- report_id: baris d_report pemiliknya; ikut terhapus bila report dihapus
-- asal_id: id baris asal di shift sebelumnya bila hasil duplikat report; perubahan status ikut diterapkan ke baris asal
-- Database yang sudah ada: ALTER TABLE d_maintenance ADD COLUMN asal_id INT(11) UNSIGNED NULL AFTER report_id, ADD KEY idx_asal (asal_id);
CREATE TABLE IF NOT EXISTS `d_maintenance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(11) unsigned NOT NULL,
  `asal_id` int(11) unsigned DEFAULT NULL,
  `grup` enum('RETAIL','CORPORATE','RNET GROUP','BACKBONE') NOT NULL,
  `site` varchar(255) NOT NULL,
  `equipment` varchar(255) NOT NULL,
  `schedule` date NOT NULL,
  `status` enum('Planned','On Progress','Done','Pending') NOT NULL,
  `pic` varchar(255) NOT NULL,
  `issue` text DEFAULT NULL,
  `action` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_report` (`report_id`),
  KEY `idx_asal` (`asal_id`),
  CONSTRAINT `fk_maintenance_report` FOREIGN KEY (`report_id`) REFERENCES `d_report` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
