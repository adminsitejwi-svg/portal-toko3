-- Traffic Monitoring (menu Data Teknis NETMON > Traffic Monitoring)
-- Interface & traffic RX/TX per device dari d_device_monitoring, diisi otomatis oleh polling SNMP v2c
-- (IF-MIB walk: ifName, ifDescr, ifAlias, ifHighSpeed, ifAdminStatus, ifOperStatus, ifHCIn/OutOctets).
-- Rate = selisih counter octets x 8 / selisih detik antar polling. Counter turun (reboot/reset) = tidak dicatat.
-- Riwayat traffic disimpan 7 hari (dihapus otomatis saat polling).
-- Jalankan setelah database/d_device_monitoring.sql.

-- Satu baris per interface per device (counter terakhir disimpan di sini untuk menghitung rate)
CREATE TABLE IF NOT EXISTS `d_device_interface` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL COMMENT 'd_device_monitoring.id',
  `if_index` int(10) unsigned NOT NULL,
  `if_name` varchar(255) DEFAULT NULL,
  `if_descr` varchar(255) DEFAULT NULL,
  `if_alias` varchar(255) DEFAULT NULL,
  `speed_mbps` int(10) unsigned DEFAULT NULL COMMENT 'ifHighSpeed',
  `admin_status` tinyint(3) unsigned DEFAULT NULL COMMENT '1 up, 2 down, 3 testing',
  `oper_status` tinyint(3) unsigned DEFAULT NULL COMMENT '1 up, 2 down, 3 testing, 5 dormant, 6 notPresent, 7 lowerLayerDown',
  `counter_bit` tinyint(3) unsigned NOT NULL DEFAULT 64 COMMENT '64 = ifHC*Octets, 32 = ifIn/OutOctets',
  `in_octets` bigint(20) unsigned DEFAULT NULL,
  `out_octets` bigint(20) unsigned DEFAULT NULL,
  `counter_at` datetime DEFAULT NULL COMMENT 'waktu counter di atas dibaca',
  `rx_bps` bigint(20) unsigned DEFAULT NULL COMMENT 'rate terakhir',
  `tx_bps` bigint(20) unsigned DEFAULT NULL COMMENT 'rate terakhir',
  `traffic_at` datetime DEFAULT NULL COMMENT 'waktu sampel rate terakhir',
  `last_seen` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_device_if` (`device_id`, `if_index`),
  KEY `idx_device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Riwayat traffic per polling (untuk grafik)
CREATE TABLE IF NOT EXISTS `d_device_interface_traffic` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL,
  `interface_id` int(11) unsigned NOT NULL COMMENT 'd_device_interface.id',
  `rx_bps` bigint(20) unsigned NOT NULL DEFAULT 0,
  `tx_bps` bigint(20) unsigned NOT NULL DEFAULT 0,
  `checked_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_interface_checked` (`interface_id`, `checked_at`),
  KEY `idx_checked` (`checked_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
