-- Tabel untuk halaman Rekap Data Toko (RekapDataMidi)
-- Jalankan di database hosting (dev.jwi.id) bila tabel belum ada.

CREATE TABLE IF NOT EXISTS `rekap_data_toko` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_pengisi` varchar(100) NOT NULL,
  `perusahaan` enum('PT Genezis Mitra Technology','PT Rtiga Global Media') NOT NULL,
  `tanggal` date NOT NULL,
  `toko` enum('Lawson','Midi') NOT NULL,
  `branch` varchar(100) NOT NULL,
  `kode_toko` varchar(50) NOT NULL,
  `nama_toko` varchar(150) NOT NULL,
  `foto_sn_fortigate` varchar(255) DEFAULT NULL,
  `id_pelanggan_fo` varchar(100) DEFAULT NULL,
  `sn_ont_fo` varchar(255) DEFAULT NULL,
  `tipe_modem_gsm_1` varchar(100) NOT NULL,
  `foto_imei_modem_1` varchar(255) DEFAULT NULL,
  `msisdn_modem_1` varchar(150) DEFAULT NULL,
  `iccid_modem_1` varchar(150) DEFAULT NULL,
  `tipe_modem_gsm_2` varchar(100) DEFAULT NULL,
  `foto_imei_modem_2` varchar(255) DEFAULT NULL,
  `msisdn_modem_2` varchar(150) DEFAULT NULL,
  `iccid_modem_2` varchar(150) DEFAULT NULL,
  `sharelock_tikor_alamat` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_kode_toko` (`kode_toko`),
  KEY `idx_branch` (`branch`),
  KEY `idx_toko` (`toko`),
  KEY `idx_perusahaan` (`perusahaan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jika tabel SUDAH ADA di hosting tapi belum punya kolom `perusahaan`
-- (kolom ini ditambahkan belakangan), jalankan juga baris di bawah:
-- ALTER TABLE `rekap_data_toko`
--   ADD COLUMN `perusahaan` enum('PT Genezis Mitra Technology','PT Rtiga Global Media') NOT NULL AFTER `nama_pengisi`,
--   ADD KEY `idx_perusahaan` (`perusahaan`);

-- Jika tabel SUDAH ADA di hosting, jalankan ini untuk kolom baru Foto IMEI Modem 2 (2026-11-18):
-- ALTER TABLE `rekap_data_toko`
--   ADD COLUMN `foto_imei_modem_2` varchar(255) DEFAULT NULL AFTER `tipe_modem_gsm_2`;

-- Jika tabel SUDAH ADA di hosting, jalankan ini untuk fitur Import Excel (2026-11-18):
-- isian MSISDN/ICCID di file Google Form bisa berupa keterangan panjang (sampai ~110 karakter)
-- ALTER TABLE `rekap_data_toko`
--   MODIFY `msisdn_modem_1` varchar(150) DEFAULT NULL,
--   MODIFY `iccid_modem_1` varchar(150) DEFAULT NULL,
--   MODIFY `msisdn_modem_2` varchar(150) DEFAULT NULL,
--   MODIFY `iccid_modem_2` varchar(150) DEFAULT NULL;
