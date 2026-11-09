-- Data ODC & ODP (menu Data Teknis NOC > Data ODC & ODP)
--
-- md_wilayah : area jaringan (Kode & Nama Wilayah, keduanya unik)
-- d_odc      : ODC per wilayah. kode_odc = ODC-{kode_wilayah}-{nomor 3 digit}, nama_odc = ODC-{nama_wilayah}-{nomor}
--              nomor = urutan per wilayah (001, 002, ...), diisi otomatis oleh aplikasi
-- d_odp      : ODP per wilayah dengan ODC induk. kode_odp = ODP-{kode_wilayah}-{nomor}, nama_odp = ODP-{nama_wilayah}-{nomor}
-- status ODC / ODP: Aktif / Rencana / Maintenance / Tidak Aktif
-- Wilayah tidak bisa dihapus selama masih punya ODC / ODP; ODC tidak bisa dihapus selama menjadi induk ODP.

CREATE TABLE IF NOT EXISTS `md_wilayah` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kode_wilayah` varchar(30) NOT NULL,
  `nama_wilayah` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kode_wilayah` (`kode_wilayah`),
  UNIQUE KEY `uniq_nama_wilayah` (`nama_wilayah`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `d_odc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wilayah_id` int(11) unsigned NOT NULL,
  `nomor` int(11) unsigned NOT NULL,
  `kode_odc` varchar(60) NOT NULL,
  `nama_odc` varchar(150) NOT NULL,
  `kapasitas_core` int(11) unsigned NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `lokasi_area` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Aktif',
  `alamat` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kode_odc` (`kode_odc`),
  UNIQUE KEY `uniq_odc_wilayah_nomor` (`wilayah_id`, `nomor`),
  CONSTRAINT `fk_odc_wilayah` FOREIGN KEY (`wilayah_id`) REFERENCES `md_wilayah` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `d_odp` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wilayah_id` int(11) unsigned NOT NULL,
  `odc_id` int(11) unsigned NOT NULL,
  `nomor` int(11) unsigned NOT NULL,
  `kode_odp` varchar(60) NOT NULL,
  `nama_odp` varchar(150) NOT NULL,
  `kapasitas_port` int(11) unsigned NOT NULL,
  `port_terpakai` int(11) unsigned NOT NULL DEFAULT 0,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `lokasi_area` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Aktif',
  `alamat` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kode_odp` (`kode_odp`),
  UNIQUE KEY `uniq_odp_wilayah_nomor` (`wilayah_id`, `nomor`),
  KEY `idx_odp_odc` (`odc_id`),
  CONSTRAINT `fk_odp_wilayah` FOREIGN KEY (`wilayah_id`) REFERENCES `md_wilayah` (`id`),
  CONSTRAINT `fk_odp_odc` FOREIGN KEY (`odc_id`) REFERENCES `d_odc` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
