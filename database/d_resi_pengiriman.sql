-- Tabel Resi Pengiriman SAT Group (menu Daily Report SAT Group > tab Resi Pengiriman)
-- noc_on_duty: nama petugas, dipilih dari data shift (jadwal_noc)
-- shift: 1 / 2 / 3 (ditampilkan "Shift 1" / "Shift 2" / "Shift 3")
-- kategori_toko: Alfamidi / Alfamart / Lawson
-- resi_pengiriman: nomor resi pengiriman atau nomor telepon
-- kolom lain diisi manual
CREATE TABLE IF NOT EXISTS `d_resi_pengiriman` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `noc_on_duty` varchar(255) NOT NULL,
  `shift` tinyint(1) unsigned NOT NULL,
  `kategori_toko` enum('Alfamidi','Alfamart','Lawson') NOT NULL,
  `kode_toko` varchar(100) NOT NULL,
  `nama_toko` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `deskripsi_pekerjaan` text NOT NULL,
  `resi_pengiriman` varchar(255) NOT NULL,
  `kendala` text NOT NULL,
  `tanggal_report` date NOT NULL,
  `status` varchar(100) NOT NULL,
  `noted` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_tanggal_shift` (`tanggal_report`, `shift`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
