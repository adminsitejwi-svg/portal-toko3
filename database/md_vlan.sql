-- Data VLAN (menu All NOC > Data VLAN)
-- vlan_id: nomor VLAN 1-4094; kombinasi vlan_id + lokasi_pop tidak boleh dobel
-- jenis_layanan: Corporate, Retail, Management, Monitoring, Backbone, Interkoneksi, atau isian manual
-- status: Aktif / Non Aktif
CREATE TABLE IF NOT EXISTS `md_vlan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vlan_id` smallint(5) unsigned NOT NULL,
  `nama_vlan` varchar(150) NOT NULL,
  `jenis_layanan` varchar(100) NOT NULL,
  `lokasi_pop` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Aktif',
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_vlan_lokasi` (`vlan_id`, `lokasi_pop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mapping perangkat per VLAN (bisa lebih dari satu baris per VLAN)
-- vendor: MikroTik, Cisco, Huawei, Juniper, Arista, ZTE, HSGQ, atau isian manual
-- mode_port: Access, Trunk, Routed, atau isian manual
CREATE TABLE IF NOT EXISTS `md_vlan_perangkat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `md_vlan_id` int(11) unsigned NOT NULL,
  `nama_perangkat` varchar(150) NOT NULL,
  `vendor` varchar(100) DEFAULT NULL,
  `interface_port` varchar(150) DEFAULT NULL,
  `mode_port` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `urutan` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_md_vlan_id` (`md_vlan_id`),
  CONSTRAINT `fk_vlan_perangkat_vlan` FOREIGN KEY (`md_vlan_id`) REFERENCES `md_vlan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
