-- ACS JWI Group (TR-069): Settings koneksi ONT, ONT yang terhubung, data WAN, antrean baca & log koneksi.
-- Mengikuti proyek speednet_acs (devices, parameters, tasks, events). Password disimpan terenkripsi
-- (encryption.key di .env). Aman dijalankan ulang.

CREATE TABLE IF NOT EXISTS `d_acs_setting` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `aktif` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'tidak dipakai lagi, ACS selalu menerima ONT yang username & password-nya sama',
  `acs_url` varchar(255) NOT NULL DEFAULT 'dev.jwi.id',
  `acs_username` varchar(64) NOT NULL DEFAULT 'acsjwi',
  `acs_password` text DEFAULT NULL COMMENT 'terenkripsi; kosong = ACS belum menerima ONT',
  `inform_interval` int(10) unsigned NOT NULL DEFAULT 300 COMMENT 'tidak dipakai lagi, interval mengikuti ONT',
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `d_acs_setting` (`id`) VALUES (1);

-- Satu baris per ONT, dibuat otomatis saat Inform pertama
CREATE TABLE IF NOT EXISTS `d_acs_device` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `serial_number` varchar(128) NOT NULL,
  `oui` varchar(16) DEFAULT NULL,
  `manufacturer` varchar(128) DEFAULT NULL,
  `product_class` varchar(128) DEFAULT NULL,
  `software_version` varchar(255) DEFAULT NULL,
  `hardware_version` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP sumber Inform',
  `connection_request_url` varchar(255) DEFAULT NULL,
  `inform_interval` int(10) unsigned DEFAULT NULL COMMENT 'PeriodicInformInterval ONT',
  `inform_count` int(10) unsigned NOT NULL DEFAULT 0,
  `last_event` varchar(255) DEFAULT NULL,
  `last_read` datetime DEFAULT NULL COMMENT 'data WAN terakhir dibaca',
  `first_seen` datetime NOT NULL,
  `last_inform` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_serial` (`serial_number`),
  KEY `idx_last_inform` (`last_inform`),
  KEY `idx_ip` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data WAN per ONT (satu ONT bisa punya beberapa WAN: INTERNET, TR069, VOIP, ...)
CREATE TABLE IF NOT EXISTS `d_acs_wan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL COMMENT 'd_acs_device.id',
  `path` varchar(190) NOT NULL,
  `jenis` varchar(5) NOT NULL COMMENT 'PPP / IP',
  `deskripsi` varchar(255) DEFAULT NULL COMMENT 'Name',
  `layanan` varchar(100) DEFAULT NULL COMMENT 'service list: INTERNET, TR069, VOIP, ...',
  `vlan` varchar(20) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL COMMENT 'ConnectionStatus',
  `mode` varchar(50) DEFAULT NULL COMMENT 'ConnectionType',
  `username` varchar(190) DEFAULT NULL,
  `password` text DEFAULT NULL COMMENT 'terenkripsi; biasanya kosong (TR-069 tidak mengirim password)',
  `mac_address` varchar(50) DEFAULT NULL,
  `ip_address` varchar(64) DEFAULT NULL,
  `gateway` varchar(64) DEFAULT NULL,
  `dns` varchar(255) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_device_path` (`device_id`, `path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data detail per ONT (satu baris per grup: info, optik, wifi, host, acs), dibaca halaman Devices
CREATE TABLE IF NOT EXISTS `d_acs_data` (
  `device_id` int(11) unsigned NOT NULL COMMENT 'd_acs_device.id',
  `grup` varchar(20) NOT NULL,
  `isi` mediumtext NOT NULL COMMENT 'JSON: p = parameter, r = parameter rahasia (terenkripsi)',
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`device_id`, `grup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Antrean perintah baca (GetParameterValues) yang diambil ONT saat sesi TR-069
CREATE TABLE IF NOT EXISTS `d_acs_task` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL,
  `perintah` varchar(64) NOT NULL,
  `payload` text DEFAULT NULL COMMENT 'JSON',
  `kunci` varchar(64) NOT NULL COMMENT 'cwmp:ID',
  `status` enum('Pending','Sent','Completed','Failed') NOT NULL DEFAULT 'Pending',
  `percobaan` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `hasil` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kunci` (`kunci`),
  KEY `idx_antrean` (`device_id`, `status`, `id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Log koneksi (Inform, login gagal, error ONT), disimpan 30 hari
CREATE TABLE IF NOT EXISTS `d_acs_event` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned DEFAULT NULL,
  `jenis` enum('ok','gagal') NOT NULL DEFAULT 'ok',
  `kejadian` varchar(128) NOT NULL,
  `detail` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_device` (`device_id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
