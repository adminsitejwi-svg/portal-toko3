-- BAST Corporate (menu Activation > BAST > BAST Corporate): dokumen Berita Acara Serah Terima pelanggan corporate
-- no_bast (unik), tanggal_bast, perusahaan, layanan dibaca OTOMATIS dari isi dokumen (boleh kosong bila tidak terbaca)
-- Dokumen hanya PDF / Word (.doc, .docx) / PNG / JPG, maks. 30 MB. Kolom berikut diisi otomatis saat upload:
--   nama_file   : nama file asli dari komputer pengguna
--   file_simpan : nama file di writable/uploads/bast_corporate (acak, diakses lewat BastCorporate/download/{id})
--   format      : PDF / DOC / DOCX / PNG / JPG (dibaca dari isi file, bukan hanya dari ekstensi)
--   ukuran      : ukuran file dalam byte
--   hash_sha1   : sidik file, mencegah file yang sama persis diupload dua kali
--   diupload_oleh, created_at : pengguna & tanggal-jam upload
CREATE TABLE IF NOT EXISTS `d_bast_corporate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `no_bast` varchar(100) DEFAULT NULL,
  `tanggal_bast` date DEFAULT NULL,
  `perusahaan` varchar(150) DEFAULT NULL,
  `layanan` varchar(150) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `file_simpan` varchar(100) NOT NULL,
  `format` varchar(5) NOT NULL,
  `ukuran` bigint(20) unsigned NOT NULL,
  `hash_sha1` char(40) NOT NULL,
  `diupload_oleh` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_no_bast_corporate` (`no_bast`),
  UNIQUE KEY `uniq_hash_bast_corporate` (`hash_sha1`),
  UNIQUE KEY `uniq_file_bast_corporate` (`file_simpan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
