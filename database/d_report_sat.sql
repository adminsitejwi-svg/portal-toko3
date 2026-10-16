-- Tabel Daily Report SAT Group (menu Report NOC > Daily Report SAT Group)
-- noc_on_duty: nama petugas, dipilih dari data shift (jadwal_noc)
-- shift: 1 / 2 / 3 (ditampilkan "Shift 1" / "Shift 2" / "Shift 3")
-- kategori_toko: Alfamidi / Alfamart / Lawson
-- kolom lain diisi manual
CREATE TABLE IF NOT EXISTS `d_report_sat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `noc_on_duty` varchar(255) NOT NULL,
  `shift` tinyint(1) unsigned NOT NULL,
  `kategori_toko` enum('Alfamidi','Alfamart','Lawson') NOT NULL,
  `kode_toko` varchar(100) NOT NULL,
  `nama_toko` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `deskripsi_pekerjaan` text NOT NULL,
  `resi_pengiriman` varchar(255) DEFAULT NULL,
  `kendala` text NOT NULL,
  `action` text NOT NULL,
  `tanggal_report` date NOT NULL,
  `status` varchar(100) NOT NULL,
  `noted` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_tanggal_shift` (`tanggal_report`, `shift`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
