-- Tabel Surat Tugas (menu Activation > Surat Tugas)
-- Struktur dari Downloads/17 SPK NCIX KARET_OCT.pdf; tampilan & fitur seperti RFO (tanpa kirim email)
-- nomor: diisi otomatis {tanggal}/{kode}.{bulan romawi}/{tahun} dari tanggal surat (contoh 14/JWI.XI/2026), bisa diubah; tidak unik
-- perusahaan dipilih dari dropdown; logo = logo bawaan perusahaan (public/Logo)
-- hari & tanggal pada surat dibuat otomatis dari tanggal_mulai s/d tanggal_selesai
-- teknisi & perangkat disimpan sebagai JSON
-- ttd: file gambar tanda tangan/stempel (opsional) di writable/uploads/surat_tugas
CREATE TABLE IF NOT EXISTS `d_surat_tugas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nomor` varchar(50) NOT NULL,
  `perusahaan` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL COMMENT 'path logo di public/, mis. Logo/SPEEDNET--.png',
  `tanggal_surat` date NOT NULL,
  `mitra` varchar(150) DEFAULT NULL COMMENT 'selaku Client colocation ..., mis. NeuCentrix',
  `kegiatan` varchar(255) NOT NULL COMMENT 'mis. Maintenance Server',
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `keperluan` varchar(255) NOT NULL,
  `teknisi` text NOT NULL COMMENT 'JSON array nama teknisi',
  `lokasi` varchar(255) NOT NULL,
  `perangkat` text DEFAULT NULL COMMENT 'JSON array {merk, jenis, sn}',
  `penutup` varchar(255) DEFAULT NULL COMMENT 'keperluan surat, mis. keperluan perizinan',
  `ttd_nama` varchar(100) NOT NULL,
  `ttd_jabatan` varchar(100) NOT NULL,
  `ttd` varchar(255) DEFAULT NULL COMMENT 'nama file tanda tangan/stempel',
  `dibuat_oleh` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_nomor` (`nomor`),
  KEY `idx_tanggal_mulai` (`tanggal_mulai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bila tabel sudah terlanjur dibuat dengan UNIQUE nomor (versi lama), jalankan:
-- ALTER TABLE `d_surat_tugas` DROP INDEX `uniq_nomor`, ADD INDEX `idx_nomor` (`nomor`);
