-- Tabel Devices Backbone (menu All NOC > Devices Backbone)
-- lokasi_penempatan, rak, u: diisi manual
CREATE TABLE IF NOT EXISTS `d_devices_backbone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_perangkat` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `sn` varchar(255) DEFAULT NULL,
  `jumlah` int(11) unsigned NOT NULL DEFAULT 1,
  `lokasi_penempatan` varchar(255) NOT NULL,
  `rak` varchar(100) DEFAULT NULL,
  `u` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
