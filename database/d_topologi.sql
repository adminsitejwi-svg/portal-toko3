-- Tabel untuk halaman Data Topologi (NOC Operation > Data Topologi), editor draw.io.
-- Jalankan di database hosting (dev.jwi.id).
-- xml    = isi diagram draw.io (untuk dibuka & diedit lagi)
-- gambar = file PNG hasil simpan di public/uploads/topologi/ (PNG ini juga bisa dibuka lagi di draw.io)

CREATE TABLE IF NOT EXISTS `d_topologi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `xml` longtext DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
