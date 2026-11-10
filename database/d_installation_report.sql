-- Installation Report (menu Report NOC > Installation Report), struktur mengikuti "Installation Report Form Yesnet (1).docx":
-- 1. Data Pelanggan, 2. Keterangan Instalasi, 3. Interface Parameter (Nama Perangkat),
-- Dibuat Oleh (Teknis) / Diketahui Oleh, 6. Lampiran Testlink (foto + keterangan)
-- Tampilan & fitur sama dengan Survey Report; perusahaan & logo sama dengan RFO (public/Logo)
-- install_id dibuat otomatis oleh aplikasi: INS-dd/mm/yyyy-NNN, contoh INS-10/11/2026-001 (urutan reset tiap bulan)
-- tipe_bisnis  : Corporate / Personal / Government / teks lain (pilihan "Dll")
-- jenis_layanan: FO / Wireless / VSAT / teks lain (pilihan "Dll")
-- lampiran     : JSON [{"file": nama file di writable/uploads/installation-report/, "ket": keterangan foto}, ...]
CREATE TABLE IF NOT EXISTS `d_installation_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `install_id` varchar(30) NOT NULL,
  `perusahaan` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL COMMENT 'path logo di public/, mis. Logo/Sprintnet.png',
  `nama_pelanggan` varchar(200) NOT NULL,
  `tipe_bisnis` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `pic_hp` varchar(150) DEFAULT NULL COMMENT 'PIC/HP',
  `email_pelanggan` varchar(255) DEFAULT NULL,
  `tgl_spk` date DEFAULT NULL,
  `no_spk` varchar(100) DEFAULT NULL,
  `tanggal_pelaksanaan` date NOT NULL,
  `pelaksana` varchar(255) NOT NULL,
  `jenis_layanan` varchar(100) NOT NULL,
  `layanan` varchar(200) NOT NULL,
  `catatan` text DEFAULT NULL,
  `nama_perangkat` varchar(150) DEFAULT NULL,
  `dibuat_oleh` varchar(150) DEFAULT NULL COMMENT 'Teknis',
  `diketahui_oleh` varchar(150) DEFAULT NULL,
  `lampiran` text DEFAULT NULL COMMENT 'JSON foto testlink + keterangan',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_install_id` (`install_id`),
  KEY `idx_tanggal_pelaksanaan` (`tanggal_pelaksanaan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Riwayat pengiriman Installation Report lewat email (akun pengirim sama dengan RFO: app/Config/RfoEmail.php)
-- Satu baris per Installation Report yang dikirim; status gagal menyimpan pesan error SMTP
CREATE TABLE IF NOT EXISTS `d_installation_report_email_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `installation_report_id` int(11) unsigned NOT NULL COMMENT 'd_installation_report.id',
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
  KEY `idx_installation_report_id` (`installation_report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
