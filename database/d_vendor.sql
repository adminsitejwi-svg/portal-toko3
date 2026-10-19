-- Tabel Data Vendor (menu All NOC > Data Vendor)
-- nama_pic, telp_pic, email_pic: kontak PIC (person in charge) vendor
CREATE TABLE IF NOT EXISTS `d_vendor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_vendor` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `nama_pic` varchar(255) NOT NULL,
  `telp_pic` varchar(50) NOT NULL,
  `email_pic` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
