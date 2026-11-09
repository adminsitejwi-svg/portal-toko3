-- PKS & Contract (menu Activation > PKS & Contract): dokumen Perjanjian Kerja Sama & kontrak pelanggan
-- no_pks (unik), tanggal_pks, perusahaan, kepada (kolom "To", mis. SMA NEGERI 1 WALED) dibaca OTOMATIS dari isi dokumen;
-- yang tidak terbaca bisa diisi manual (boleh kosong).
-- Dokumen hanya PDF / Word (.doc, .docx) / PNG / JPG, maks. 30 MB, disimpan di writable/uploads/pks_contract (nama acak).
-- format dibaca dari isi file; hash_sha1 mencegah dokumen yang sama diupload dua kali; diupload_oleh & created_at otomatis.
CREATE TABLE IF NOT EXISTS `d_pks_contract` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `no_pks` varchar(100) DEFAULT NULL,
  `tanggal_pks` date DEFAULT NULL,
  `perusahaan` varchar(255) DEFAULT NULL,
  `kepada` varchar(255) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `file_simpan` varchar(100) NOT NULL,
  `format` varchar(5) NOT NULL,
  `ukuran` bigint(20) unsigned NOT NULL,
  `hash_sha1` char(40) NOT NULL,
  `diupload_oleh` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_no_pks` (`no_pks`),
  UNIQUE KEY `uniq_hash_pks` (`hash_sha1`),
  UNIQUE KEY `uniq_file_pks` (`file_simpan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
