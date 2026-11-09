-- Data KMZ (menu Data Teknis NOC > KMZ): hanya file peta .kmz / .kml, maks. 30 MB
-- Semua kolom diisi otomatis saat upload:
--   nama_file   : nama file asli dari komputer pengguna
--   file_simpan : nama file di writable/uploads/kmz (acak, diakses lewat Kmz/download/{id})
--   format      : KMZ / KML (dibaca dari isi file, bukan hanya dari ekstensi)
--   ukuran      : ukuran file dalam byte
--   hash_sha1   : sidik file, mencegah file yang sama persis diupload dua kali
--   diupload_oleh, created_at : pengguna & tanggal-jam upload
CREATE TABLE IF NOT EXISTS `d_kmz` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_file` varchar(255) NOT NULL,
  `file_simpan` varchar(100) NOT NULL,
  `format` varchar(5) NOT NULL,
  `ukuran` bigint(20) unsigned NOT NULL,
  `hash_sha1` char(40) NOT NULL,
  `diupload_oleh` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_hash_kmz` (`hash_sha1`),
  UNIQUE KEY `uniq_file_simpan_kmz` (`file_simpan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
