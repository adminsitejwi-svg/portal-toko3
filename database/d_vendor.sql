-- Tabel Data Vendor (menu All NOC > Data Vendor)
-- note: catatan vendor (diisi manual), ikut tampil otomatis di form Data BTS & Data Pelanggan
CREATE TABLE IF NOT EXISTS `d_vendor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_vendor` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Untuk tabel lama (yang masih punya kolom alamat & PIC):
-- ALTER TABLE d_vendor ADD COLUMN note TEXT NULL AFTER nama_vendor;
-- ALTER TABLE d_vendor DROP COLUMN alamat, DROP COLUMN nama_pic, DROP COLUMN telp_pic, DROP COLUMN email_pic;
