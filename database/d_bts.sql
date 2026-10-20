-- Tabel BTS (menu All NOC > BTS)
-- Vendor beserta IP Address & VLAN-nya disimpan di d_bts_vendor (satu BTS bisa lebih dari 1 vendor)
CREATE TABLE IF NOT EXISTS `d_bts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_bts` varchar(255) NOT NULL,
  `kapasitas` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vendor per BTS, vendor_id: id dari d_vendor (menu All NOC > Data Vendor)
-- ip_address, kapasitas & vlan_id milik vendor tersebut; vlan_id NULL berarti "Tanpa VLAN"
CREATE TABLE IF NOT EXISTS `d_bts_vendor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bts_id` int(11) unsigned NOT NULL,
  `vendor_id` int(11) unsigned NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `kapasitas` varchar(100) DEFAULT NULL,
  `vlan_id` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_bts_id` (`bts_id`),
  KEY `idx_vendor_id` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kapasitas per vendor (database yang sudah ada):
-- ALTER TABLE `d_bts_vendor` ADD COLUMN `kapasitas` varchar(100) DEFAULT NULL AFTER `ip_address`;

-- Untuk database yang tabelnya sudah dibuat dengan struktur lama
-- (ip_address & vlan_id di d_bts, nama vendor teks di d_bts_vendor):
-- ALTER TABLE `d_bts` DROP COLUMN `ip_address`, DROP COLUMN `vlan_id`;
-- ALTER TABLE `d_bts_vendor`
--   DROP COLUMN `vendor`,
--   ADD COLUMN `vendor_id` int(11) unsigned NOT NULL AFTER `bts_id`,
--   ADD COLUMN `ip_address` varchar(45) NOT NULL AFTER `vendor_id`,
--   ADD COLUMN `vlan_id` smallint(5) unsigned DEFAULT NULL AFTER `ip_address`,
--   ADD KEY `idx_vendor_id` (`vendor_id`);
