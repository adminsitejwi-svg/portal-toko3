-- Tabel Data Pelanggan (menu All NOC > Data Pelanggan)
-- Beda dengan md_pelanggan (menu Pelanggan lama).
-- bts_id: IP Address, Vendor, Kapasitas, Vlan ID diambil dari d_bts / d_bts_vendor
-- devices_backbone_id: Nama Perangkat, Type, SN, Jumlah, Lokasi Penempatan diambil dari d_devices_backbone
-- file_topologi: nama file di writable/uploads/topologi/, file_topologi_asli: nama file saat diupload
CREATE TABLE IF NOT EXISTS `d_pelanggan_noc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_pelanggan` varchar(255) NOT NULL,
  `bts_id` int(11) unsigned DEFAULT NULL,
  `detail_perangkat` text DEFAULT NULL,
  `devices_backbone_id` int(11) unsigned DEFAULT NULL,
  `file_topologi` varchar(255) DEFAULT NULL,
  `file_topologi_asli` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_bts_id` (`bts_id`),
  KEY `idx_devices_backbone_id` (`devices_backbone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
