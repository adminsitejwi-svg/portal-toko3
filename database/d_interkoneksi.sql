-- Tabel Data Interkoneksi (menu All NOC > Data Interkoneksi), semua kolom diisi manual
-- kode_interkoneksi: kode interkoneksi, unik
-- data_center_id: id dari md_data_center (nama & kode data center diambil dari tabel itu)
-- file_dokumen: nama file di writable/uploads/interkoneksi (PDF/PNG/JPG), file_dokumen_asli: nama file asli saat upload
CREATE TABLE IF NOT EXISTS `d_interkoneksi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kode_interkoneksi` varchar(100) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `data_center_id` int(11) unsigned DEFAULT NULL,
  `sumber` varchar(255) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `label_kabel` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `file_dokumen` varchar(255) DEFAULT NULL,
  `file_dokumen_asli` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kode_interkoneksi` (`kode_interkoneksi`),
  KEY `idx_data_center_id` (`data_center_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Untuk database yang tabelnya sudah dibuat sebelum kolom data_center_id ada:
-- ALTER TABLE `d_interkoneksi`
--   ADD COLUMN `data_center_id` int(11) unsigned DEFAULT NULL AFTER `customer`,
--   ADD KEY `idx_data_center_id` (`data_center_id`);

-- Untuk database yang tabelnya sudah dibuat sebelum kolom file dokumen ada:
-- ALTER TABLE `d_interkoneksi`
--   ADD COLUMN `file_dokumen` varchar(255) DEFAULT NULL AFTER `note`,
--   ADD COLUMN `file_dokumen_asli` varchar(255) DEFAULT NULL AFTER `file_dokumen`;
