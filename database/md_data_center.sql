-- Tabel Data Center (menu Data Center di sidebar)
-- alamat_dc: lokasi data center (alamat), lokasi_rak: posisi rak di data center
CREATE TABLE IF NOT EXISTS `md_data_center` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_dc` varchar(255) NOT NULL,
  `alamat_dc` text NOT NULL,
  `lokasi_rak` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Untuk database yang tabelnya sudah dibuat dengan kolom lama (kode_dc, deskripsi):
-- ALTER TABLE `md_data_center`
--   DROP INDEX `uniq_kode_dc`,
--   DROP COLUMN `kode_dc`,
--   DROP COLUMN `deskripsi`,
--   ADD COLUMN `alamat_dc` text NOT NULL AFTER `nama_dc`,
--   ADD COLUMN `lokasi_rak` varchar(255) NOT NULL AFTER `alamat_dc`;
