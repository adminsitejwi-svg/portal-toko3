-- Data Switch (menu Data Teknis NOC > Data Switch)
-- Tidak boleh dobel: nama_switch, ip_switch & sn unik (dijaga juga oleh aplikasi)
-- type: Cisco / Arista / Juniper / TP-Link / Extreme
CREATE TABLE IF NOT EXISTS `d_switch` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_switch` varchar(150) NOT NULL,
  `ip_switch` varchar(45) NOT NULL,
  `type` varchar(30) NOT NULL,
  `seri_switch` varchar(150) DEFAULT NULL,
  `sn` varchar(150) NOT NULL,
  `versi_switch` varchar(100) DEFAULT NULL,
  `lokasi_pemasangan` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_nama_switch` (`nama_switch`),
  UNIQUE KEY `uniq_ip_switch` (`ip_switch`),
  UNIQUE KEY `uniq_sn_switch` (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
