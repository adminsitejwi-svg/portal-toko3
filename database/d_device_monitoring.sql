-- Device Monitoring (menu Data Teknis NETMON > Device Monitoring)
-- Data perangkat + konfigurasi SNMP; status Up/Down diisi otomatis oleh polling SNMP (v2c) aplikasi.
-- vendor       : Cisco / Juniper / Mikrotik / teks lain (pilihan "Other" diisi manual)
-- device_group : Backbone / Firewall / OLT / Router / Server / Switch / teks lain (pilihan "Other" diisi manual)
-- snmp_version : v2c / v3 (v3: setup credentials belum tersedia, perangkat tidak di-polling)
-- snmp_community disimpan terenkripsi (encryption.key di .env, sama dengan FTP Client)
-- status       : Up / Down / Unknown (belum pernah di-polling)
CREATE TABLE IF NOT EXISTS `d_device_monitoring` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hostname` varchar(150) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `vendor` varchar(100) NOT NULL,
  `model` varchar(150) DEFAULT NULL,
  `device_group` varchar(100) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `snmp_version` varchar(5) NOT NULL DEFAULT 'v2c',
  `snmp_port` smallint(5) unsigned NOT NULL DEFAULT 161,
  `snmp_community` text DEFAULT NULL COMMENT 'terenkripsi',
  `polling_interval` int(10) unsigned NOT NULL DEFAULT 60 COMMENT 'detik',
  `snmp_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `monitoring_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `status` varchar(10) NOT NULL DEFAULT 'Unknown',
  `response_ms` int(10) unsigned DEFAULT NULL,
  `sys_name` varchar(255) DEFAULT NULL,
  `sys_descr` text DEFAULT NULL,
  `sys_uptime` bigint(20) unsigned DEFAULT NULL COMMENT 'timeticks (1/100 detik)',
  `sys_contact` varchar(255) DEFAULT NULL,
  `sys_location` varchar(255) DEFAULT NULL,
  `if_number` int(10) unsigned DEFAULT NULL COMMENT 'jumlah interface',
  `last_poll` datetime DEFAULT NULL,
  `last_up` datetime DEFAULT NULL,
  `last_error` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_hostname` (`hostname`),
  UNIQUE KEY `uniq_ip_address` (`ip_address`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Riwayat perubahan status perangkat (Up <-> Down), tampil di halaman View
CREATE TABLE IF NOT EXISTS `d_device_monitoring_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL COMMENT 'd_device_monitoring.id',
  `status` varchar(10) NOT NULL,
  `pesan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
