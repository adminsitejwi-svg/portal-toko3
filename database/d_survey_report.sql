-- Survey Report (menu Report NOC > Survey Report), struktur mengikuti form "Survey Report Form Yesnet.docx":
-- 1. Data Calon Pelanggan, 2. Keterangan Survey (+ tabel Sisi Internal/Teknisi vs Sisi Klien),
-- 3. Resume/Rekomendasi, lampiran foto (dengan keterangan), Dilaksanakan Oleh (NOC)
-- perusahaan dipilih dari dropdown (sama dengan RFO); logo = logo bawaan perusahaan (public/Logo)
-- survey_id dibuat otomatis oleh aplikasi: SVR-dd/mm/yyyy-NNN, contoh SVR-10/11/2026-001 (urutan reset tiap bulan)
-- tipe_bisnis  : Corporate / Personal / Government / teks lain (pilihan "Dll")
-- jenis_layanan: Jenis Survey = FO / Wireless / VSAT / teks lain (pilihan "Dll")
-- catatan      : satu poin per baris (tampil sebagai daftar 1), 2), ...)
-- rekomendasi  : JSON [[sisi internal/teknisi, sisi klien], ...]
-- lampiran     : JSON [{"file": nama file di writable/uploads/survey-report/, "ket": keterangan foto}, ...]
CREATE TABLE IF NOT EXISTS `d_survey_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` varchar(30) NOT NULL,
  `perusahaan` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL COMMENT 'path logo di public/, mis. Logo/Sprintnet.png',
  `nama_pelanggan` varchar(200) NOT NULL COMMENT 'Nama Calon Pelanggan',
  `tipe_bisnis` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `email_pelanggan` varchar(255) DEFAULT NULL,
  `tgl_spk` date DEFAULT NULL,
  `no_spk` varchar(100) DEFAULT NULL,
  `tanggal_pelaksanaan` date NOT NULL,
  `jenis_layanan` varchar(100) NOT NULL COMMENT 'Jenis Survey',
  `layanan` varchar(200) NOT NULL,
  `catatan` text DEFAULT NULL,
  `rekomendasi` text DEFAULT NULL COMMENT 'JSON tabel Sisi Internal/Teknisi vs Sisi Klien',
  `resume` text DEFAULT NULL COMMENT 'isi 3. Resume/Rekomendasi',
  `pelaksana` varchar(255) DEFAULT NULL COMMENT 'Dilaksanakan Oleh',
  `lampiran` text DEFAULT NULL COMMENT 'JSON foto + keterangan',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_survey_id` (`survey_id`),
  KEY `idx_tanggal_pelaksanaan` (`tanggal_pelaksanaan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Riwayat pengiriman Survey Report lewat email (akun pengirim dipilih di form, sama dengan RFO: app/Config/RfoEmail.php)
-- Satu baris per Survey Report yang dikirim; status gagal menyimpan pesan error SMTP
CREATE TABLE IF NOT EXISTS `d_survey_report_email_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `survey_report_id` int(11) unsigned NOT NULL COMMENT 'd_survey_report.id',
  `dari` varchar(100) DEFAULT NULL COMMENT 'akun pengirim',
  `kepada` text NOT NULL,
  `cc` text DEFAULT NULL,
  `subjek` varchar(255) NOT NULL,
  `lampiran` text DEFAULT NULL COMMENT 'nama file lampiran',
  `body` mediumtext DEFAULT NULL COMMENT 'isi body email (HTML) yang dikirim',
  `status` enum('Terkirim','Gagal') NOT NULL,
  `pesan_error` text DEFAULT NULL,
  `dikirim_oleh` varchar(100) DEFAULT NULL COMMENT 'username yang mengirim',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_survey_report_id` (`survey_report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Upgrade tabel lama (bila d_survey_report sudah dibuat dengan struktur Maintenance Report sebelumnya):
-- ALTER TABLE `d_survey_report`
--   DROP COLUMN `nama_perangkat`, DROP COLUMN `mac_address`, DROP COLUMN `type_perangkat`, DROP COLUMN `tx`, DROP COLUMN `rx`,
--   DROP COLUMN `dibuat_oleh`, DROP COLUMN `diketahui_oleh`,
--   MODIFY `pelaksana` varchar(255) DEFAULT NULL COMMENT 'Dilaksanakan Oleh',
--   ADD COLUMN `rekomendasi` text DEFAULT NULL COMMENT 'JSON tabel Sisi Internal/Teknisi vs Sisi Klien' AFTER `catatan`;
-- (bila kolom `resume` belum ada: ALTER TABLE `d_survey_report` ADD COLUMN `resume` text DEFAULT NULL AFTER `rekomendasi`;)
