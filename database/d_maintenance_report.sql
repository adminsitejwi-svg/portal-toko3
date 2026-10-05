-- Tabel Maintenance Report (menu Report NOC > Maintenance Report)
-- nama: petugas, dipilih dari data shift (jadwal_noc)
-- maintenance / perpindahan_perangkat: kotak ceklis (1 = dicentang)
CREATE TABLE IF NOT EXISTS `d_maintenance_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `id_pelanggan` varchar(100) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `odp_port` varchar(255) NOT NULL,
  `maintenance` tinyint(1) NOT NULL DEFAULT 0,
  `perpindahan_perangkat` tinyint(1) NOT NULL DEFAULT 0,
  `kendala` text NOT NULL,
  `no_tiket` varchar(100) NOT NULL,
  `action` text NOT NULL,
  `status` enum('On Progress','Monitoring','Pending','Resolved') NOT NULL DEFAULT 'On Progress',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
