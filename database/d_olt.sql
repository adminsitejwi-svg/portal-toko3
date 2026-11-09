-- Data OLT (menu Data Teknis NOC > Data OLT)
-- Tidak boleh dobel: nama_olt, ip_olt & sn unik (dijaga juga oleh aplikasi)
-- type: ZTE / HSGQ
CREATE TABLE IF NOT EXISTS `d_olt` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_olt` varchar(150) NOT NULL,
  `ip_olt` varchar(45) NOT NULL,
  `type` varchar(30) NOT NULL,
  `seri_olt` varchar(150) DEFAULT NULL,
  `sn` varchar(150) NOT NULL,
  `versi_olt` varchar(100) DEFAULT NULL,
  `lokasi_pemasangan` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_nama_olt` (`nama_olt`),
  UNIQUE KEY `uniq_ip_olt` (`ip_olt`),
  UNIQUE KEY `uniq_sn_olt` (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
