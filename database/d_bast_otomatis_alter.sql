-- BAST: No BAST, Tanggal, Perusahaan & Layanan kini dibaca otomatis dari isi dokumen, jadi kolomnya boleh kosong.
-- Jalankan file ini HANYA bila tabel d_bast_* sudah terlanjur dibuat dengan SQL versi lama (kolom NOT NULL).
-- Tabel yang belum ada cukup dibuat dengan d_bast_corporate.sql / d_bast_toko.sql versi baru.

ALTER TABLE `d_bast_corporate`
  MODIFY `no_bast` varchar(100) DEFAULT NULL,
  MODIFY `tanggal_bast` date DEFAULT NULL,
  MODIFY `perusahaan` varchar(150) DEFAULT NULL,
  MODIFY `layanan` varchar(150) DEFAULT NULL;

ALTER TABLE `d_bast_alfamidi`
  MODIFY `no_bast` varchar(100) DEFAULT NULL,
  MODIFY `tanggal_bast` date DEFAULT NULL,
  MODIFY `perusahaan` varchar(150) DEFAULT NULL,
  MODIFY `layanan` varchar(150) DEFAULT NULL;

ALTER TABLE `d_bast_lawson`
  MODIFY `no_bast` varchar(100) DEFAULT NULL,
  MODIFY `tanggal_bast` date DEFAULT NULL,
  MODIFY `perusahaan` varchar(150) DEFAULT NULL,
  MODIFY `layanan` varchar(150) DEFAULT NULL;

ALTER TABLE `d_bast_alfamart`
  MODIFY `no_bast` varchar(100) DEFAULT NULL,
  MODIFY `tanggal_bast` date DEFAULT NULL,
  MODIFY `perusahaan` varchar(150) DEFAULT NULL,
  MODIFY `layanan` varchar(150) DEFAULT NULL;

ALTER TABLE `d_bast_dandan`
  MODIFY `no_bast` varchar(100) DEFAULT NULL,
  MODIFY `tanggal_bast` date DEFAULT NULL,
  MODIFY `perusahaan` varchar(150) DEFAULT NULL,
  MODIFY `layanan` varchar(150) DEFAULT NULL;
