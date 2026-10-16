-- Tabel Maintenance Report (menu Report NOC > Maintenance Report)
-- nama: petugas, dipilih dari data shift (jadwal_noc)
-- tanggal_report: tanggal report yang diisi manual di form
-- waktu_kendala: tanggal & jam kendala
-- maintenance / perpindahan_perangkat / gangguan: kotak ceklis (1 = dicentang)
-- waktu_resolved: tanggal & jam saat status diubah ke Resolved (jam komputer pengguna), kosong jika belum Resolved
CREATE TABLE IF NOT EXISTS `d_maintenance_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal_report` date DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `id_pelanggan` varchar(100) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `odp_port` varchar(255) NOT NULL,
  `waktu_kendala` datetime DEFAULT NULL,
  `maintenance` tinyint(1) NOT NULL DEFAULT 0,
  `perpindahan_perangkat` tinyint(1) NOT NULL DEFAULT 0,
  `gangguan` tinyint(1) NOT NULL DEFAULT 0,
  `kendala` text NOT NULL,
  `no_tiket` varchar(100) NOT NULL,
  `action` text NOT NULL,
  `status` enum('On Progress','Monitoring','Pending','Resolved') NOT NULL DEFAULT 'On Progress',
  `waktu_resolved` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Untuk tabel yang sudah ada:
-- ALTER TABLE `d_maintenance_report` ADD `tanggal_report` date DEFAULT NULL AFTER `id`;
-- ALTER TABLE `d_maintenance_report` ADD `waktu_kendala` datetime DEFAULT NULL AFTER `odp_port`, ADD `gangguan` tinyint(1) NOT NULL DEFAULT 0 AFTER `perpindahan_perangkat`;
-- ALTER TABLE `d_maintenance_report` ADD `waktu_resolved` datetime DEFAULT NULL AFTER `status`;
