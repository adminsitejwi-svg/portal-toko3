-- Tabel Data Pelanggan (menu All NOC > Data Pelanggan)
-- Beda dengan md_pelanggan (menu Pelanggan lama).
-- bts_id: BTS pelanggan (IP Address, Kapasitas, Vlan ID BTS & pilihan vendor diambil dari d_bts / d_bts_vendor)
-- file_topologi: nama file di writable/uploads/topologi/, file_topologi_asli: nama file saat diupload
CREATE TABLE IF NOT EXISTS `d_pelanggan_noc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_pelanggan` varchar(255) NOT NULL,
  `bts_id` int(11) unsigned DEFAULT NULL,
  `file_topologi` varchar(255) DEFAULT NULL,
  `file_topologi_asli` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_bts_id` (`bts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vendor per pelanggan, vendor_id: id dari d_vendor (dipilih dari vendor milik BTS pelanggan)
-- ip_address, vlan_id & kapasitas milik pelanggan di vendor itu; vlan_id NULL berarti "Tanpa VLAN"
CREATE TABLE IF NOT EXISTS `d_pelanggan_noc_vendor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pelanggan_id` int(11) unsigned NOT NULL,
  `vendor_id` int(11) unsigned NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `vlan_id` smallint(5) unsigned DEFAULT NULL,
  `kapasitas` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pelanggan_id` (`pelanggan_id`),
  KEY `idx_vendor_id` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Detail perangkat pelanggan di lokasi (diisi manual)
CREATE TABLE IF NOT EXISTS `d_pelanggan_noc_perangkat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pelanggan_id` int(11) unsigned NOT NULL,
  `merk` varchar(255) DEFAULT NULL,
  `jenis` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `sn` varchar(255) DEFAULT NULL,
  `jumlah` int(11) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_pelanggan_id` (`pelanggan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Untuk database yang tabelnya sudah dibuat dengan struktur lama (detail perangkat teks & Devices Backbone):
-- ALTER TABLE `d_pelanggan_noc`
--   DROP INDEX `idx_devices_backbone_id`,
--   DROP COLUMN `detail_perangkat`,
--   DROP COLUMN `devices_backbone_id`;
