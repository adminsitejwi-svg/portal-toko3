-- Data Router (menu Data Teknis NOC > Data Router)
-- Tidak boleh dobel: nama_router & sn unik (dijaga juga oleh aplikasi);
-- IP Mainlink / Backup / Loopback tidak boleh dipakai router lain (dicek oleh aplikasi).
-- type: MikroTik / Juniper / Cisco / Huawei
CREATE TABLE IF NOT EXISTS `d_router` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_router` varchar(150) NOT NULL,
  `ip_mainlink` varchar(45) NOT NULL,
  `ip_backup` varchar(45) DEFAULT NULL,
  `ip_loopback` varchar(45) DEFAULT NULL,
  `type` varchar(30) NOT NULL,
  `seri_router` varchar(150) DEFAULT NULL,
  `sn` varchar(150) NOT NULL,
  `versi_router` varchar(100) DEFAULT NULL,
  `lokasi_pemasangan` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_nama_router` (`nama_router`),
  UNIQUE KEY `uniq_sn_router` (`sn`),
  KEY `idx_ip_mainlink` (`ip_mainlink`),
  KEY `idx_ip_backup` (`ip_backup`),
  KEY `idx_ip_loopback` (`ip_loopback`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
