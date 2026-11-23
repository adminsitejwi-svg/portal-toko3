-- Alert & Notifikasi (menu Data Teknis NETMON > Alert + lonceng notifikasi di navbar), mengikuti netmon/alerts.php
-- Diisi otomatis setiap device Device Monitoring di-poll (App\Models\DeviceAlertModel).
-- Jalankan setelah d_device_monitoring.sql, d_device_traffic.sql dan d_device_nms.sql. Aman dijalankan ulang.

-- Satu baris per kejadian. alert_key yang masih open/acknowledged tidak dibuat ganda, cukup last_seen diperbarui.
-- alert_type : device_offline / high_latency / packet_loss / high_cpu / high_memory / interface_down
CREATE TABLE IF NOT EXISTS `d_device_alert` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL COMMENT 'd_device_monitoring.id',
  `interface_id` int(11) unsigned DEFAULT NULL COMMENT 'd_device_interface.id',
  `alert_key` varchar(150) NOT NULL COMMENT 'mis. device:1:offline / interface:9:down',
  `alert_type` varchar(50) NOT NULL,
  `severity` enum('critical','warning','info') NOT NULL DEFAULT 'warning',
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `metric_value` decimal(16,4) DEFAULT NULL,
  `threshold_value` decimal(16,4) DEFAULT NULL,
  `status` enum('open','acknowledged','resolved') NOT NULL DEFAULT 'open',
  `first_seen` datetime NOT NULL,
  `last_seen` datetime NOT NULL,
  `resolved_at` datetime DEFAULT NULL,
  `acknowledged_at` datetime DEFAULT NULL,
  `acknowledged_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`, `severity`),
  KEY `idx_device` (`device_id`),
  KEY `idx_interface` (`interface_id`),
  KEY `idx_key_status` (`alert_key`, `status`),
  KEY `idx_last_seen` (`last_seen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Isi lonceng notifikasi: 'alert' saat gangguan baru, 'recovery' saat gangguan pulih
CREATE TABLE IF NOT EXISTS `d_device_notifikasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `alert_id` bigint(20) unsigned NOT NULL COMMENT 'd_device_alert.id',
  `device_id` int(11) unsigned NOT NULL,
  `event_type` enum('alert','recovery') NOT NULL,
  `severity` enum('critical','warning','info') NOT NULL DEFAULT 'warning',
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_alert_event` (`alert_id`, `event_type`),
  KEY `idx_device` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Status dibaca per akun: notifikasi dengan id <= notifikasi_id sudah dibaca ("Tandai semua dibaca")
CREATE TABLE IF NOT EXISTS `d_device_notifikasi_baca` (
  `user_id` int(11) NOT NULL COMMENT 'login.id',
  `notifikasi_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Interface yang dikecualikan (Exclude) dari alert interface down; monitoring-nya tetap jalan
CREATE TABLE IF NOT EXISTS `d_device_alert_exclude` (
  `interface_id` int(11) unsigned NOT NULL COMMENT 'd_device_interface.id',
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`interface_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
