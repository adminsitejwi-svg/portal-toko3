-- Tabel BTS (menu All NOC > BTS)
-- vlan_id: NULL berarti "Tanpa VLAN"
CREATE TABLE IF NOT EXISTS `d_bts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_bts` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `kapasitas` varchar(100) NOT NULL,
  `vlan_id` smallint(5) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vendor per BTS (satu BTS bisa lebih dari 1 vendor)
CREATE TABLE IF NOT EXISTS `d_bts_vendor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bts_id` int(11) unsigned NOT NULL,
  `vendor` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_bts_id` (`bts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
