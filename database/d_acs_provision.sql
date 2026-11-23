-- ACS JWI Group > Provisioning (butuh tabel dari d_acs.sql). Aman dijalankan berulang.

-- Profil: daftar parameter TR-069 yang diterapkan ke ONT
CREATE TABLE IF NOT EXISTS `d_acs_profile` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(128) NOT NULL,
  `merek` varchar(128) DEFAULT NULL COMMENT 'kosong = semua merek',
  `model` varchar(128) DEFAULT NULL COMMENT 'kosong = semua model',
  `aturan` mediumtext NOT NULL COMMENT 'JSON: n = parameter, v = nilai, t = tipe, enk = nilai terenkripsi',
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_nama` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ONT yang didaftarkan: profilnya diterapkan sekali, otomatis saat ONT mengirim Inform
CREATE TABLE IF NOT EXISTS `d_acs_provision` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `serial_number` varchar(128) NOT NULL,
  `kunci_sn` varchar(128) NOT NULL COMMENT 'SN huruf besar; bentuk hex (48575443...) disamakan menjadi HWTC...',
  `profil_id` int(11) unsigned NOT NULL,
  `status` enum('Menunggu','Tidak Cocok','Antre','Berhasil','Gagal') NOT NULL DEFAULT 'Menunggu',
  `hasil` varchar(255) DEFAULT NULL,
  `task_id` bigint(20) unsigned DEFAULT NULL,
  `device_id` int(11) unsigned DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `provisioned_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kunci_sn` (`kunci_sn`),
  KEY `idx_profil` (`profil_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
