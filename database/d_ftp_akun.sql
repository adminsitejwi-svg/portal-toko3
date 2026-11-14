-- Akun FTP tersimpan (menu Data Teknis NOC > FTP Client)
-- protokol: ftp | ftps | sftp
-- password: terenkripsi oleh aplikasi (encryption.key di .env); bila encryption.key hosting
--           berbeda dengan lokal, password akun lama tidak bisa dibaca dan harus disimpan ulang
-- File/folder FTP-nya sendiri tidak disimpan di database
CREATE TABLE IF NOT EXISTS `d_ftp_akun` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL COMMENT 'nama akun yang tampil di daftar',
  `protokol` varchar(10) NOT NULL DEFAULT 'ftp',
  `host` varchar(255) NOT NULL,
  `port` int(11) unsigned NOT NULL DEFAULT 21,
  `username` varchar(150) NOT NULL,
  `password` text DEFAULT NULL,
  `folder_awal` varchar(255) DEFAULT NULL COMMENT 'folder yang dibuka setelah connect, kosong = folder home',
  `pasif` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'mode pasif FTP',
  `dibuat_oleh` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_nama` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
