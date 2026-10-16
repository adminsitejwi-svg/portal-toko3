-- Tabel Devices Backbone (menu All NOC > Devices Backbone)
-- data_center_id: lokasi penempatan, id dari md_data_center (yang ditampilkan nama data center)
CREATE TABLE IF NOT EXISTS `d_devices_backbone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_perangkat` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `sn` varchar(255) DEFAULT NULL,
  `jumlah` int(11) unsigned NOT NULL DEFAULT 1,
  `data_center_id` int(11) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_data_center_id` (`data_center_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
