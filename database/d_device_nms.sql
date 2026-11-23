-- NMS / Network Management System (menu Data Teknis NETMON > NMS), mengikuti netmon/nms.php
-- Satu baris per polling device Device Monitoring: ping ICMP (latency & packet loss) + CPU/RAM lewat SNMP.
-- Diisi otomatis oleh polling (halaman Device Monitoring / Traffic Monitoring / NMS terbuka,
-- atau cron: php spark devicemonitoring:poll). Riwayat disimpan 30 hari (dihapus otomatis saat polling).
-- ping_source : icmp = ping dari server, snmp = server tidak bisa ping (exec mati), latency = waktu respon SNMP
-- Jalankan setelah database/d_device_monitoring.sql.
CREATE TABLE IF NOT EXISTS `d_device_nms_metric` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL COMMENT 'd_device_monitoring.id',
  `ping_status` varchar(10) NOT NULL DEFAULT 'Down' COMMENT 'Up / Down',
  `ping_source` varchar(5) NOT NULL DEFAULT 'icmp' COMMENT 'icmp / snmp',
  `latency_ms` decimal(10,2) DEFAULT NULL,
  `packet_loss` decimal(5,2) DEFAULT NULL COMMENT 'persen',
  `snmp_ok` tinyint(1) NOT NULL DEFAULT 0,
  `cpu_usage` decimal(5,2) DEFAULT NULL COMMENT 'persen',
  `memory_usage` decimal(5,2) DEFAULT NULL COMMENT 'persen',
  `memory_used` bigint(20) unsigned DEFAULT NULL COMMENT 'byte',
  `memory_total` bigint(20) unsigned DEFAULT NULL COMMENT 'byte',
  `uptime_ticks` bigint(20) unsigned DEFAULT NULL COMMENT 'sysUpTime (1/100 detik)',
  `checked_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_device_checked` (`device_id`, `checked_at`),
  KEY `idx_checked` (`checked_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
