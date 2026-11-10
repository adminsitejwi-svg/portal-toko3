-- Preventive Maintenance (menu Report NOC > Preventive Maintenance), struktur mengikuti "Maintenance Report Form Yesnet.docx":
-- 1. Data Pelanggan, 2. Keterangan Instalasi, 3. Interface Parameter (Nama Perangkat, Mac Address, Type, Tx, Rx),
-- 4. Resume (isi + Dibuat Oleh (Teknis) / Diketahui Oleh (Spv/Mng.Teknis)), 5. Lampiran (foto + keterangan)
-- Tampilan & fitur sama dengan Survey / Installation / Troubleshoot Report; perusahaan & logo sama dengan RFO (public/Logo)
-- pm_id dibuat otomatis oleh aplikasi: PM-dd/mm/yyyy-NNN, contoh PM-10/11/2026-001 (urutan reset tiap bulan)
-- tipe_bisnis  : Personal / Corporate / Government / teks lain (pilihan "Dll")
-- jenis_layanan: FO / Wireless / VSAT / teks lain (pilihan "Dll")
-- lampiran     : JSON [{"file": nama file di writable/uploads/preventive-maintenance/, "ket": keterangan foto}, ...]
CREATE TABLE IF NOT EXISTS `d_preventive_maintenance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pm_id` varchar(30) NOT NULL,
  `perusahaan` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL COMMENT 'path logo di public/, mis. Logo/Sprintnet.png',
  `nama_pelanggan` varchar(200) NOT NULL,
  `tipe_bisnis` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `email_pelanggan` varchar(255) DEFAULT NULL,
  `tgl_spk` date DEFAULT NULL,
  `no_spk` varchar(100) DEFAULT NULL,
  `tanggal_pelaksanaan` date NOT NULL,
  `pelaksana` varchar(255) NOT NULL,
  `jenis_layanan` varchar(100) NOT NULL,
  `layanan` varchar(200) NOT NULL,
  `catatan` text DEFAULT NULL,
  `nama_perangkat` varchar(150) DEFAULT NULL,
  `mac_address` varchar(50) DEFAULT NULL,
  `type_perangkat` varchar(150) DEFAULT NULL,
  `tx` varchar(50) DEFAULT NULL,
  `rx` varchar(50) DEFAULT NULL,
  `resume` text DEFAULT NULL COMMENT 'isi bagian 4. Resume',
  `dibuat_oleh` varchar(150) DEFAULT NULL COMMENT 'Teknis',
  `diketahui_oleh` varchar(150) DEFAULT NULL COMMENT 'Spv/Mng.Teknis',
  `lampiran` text DEFAULT NULL COMMENT 'JSON foto + keterangan',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_pm_id` (`pm_id`),
  KEY `idx_tanggal_pelaksanaan` (`tanggal_pelaksanaan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Riwayat pengiriman Preventive Maintenance lewat email (akun pengirim sama dengan RFO: app/Config/RfoEmail.php)
-- Satu baris per Preventive Maintenance yang dikirim; status gagal menyimpan pesan error SMTP
CREATE TABLE IF NOT EXISTS `d_preventive_maintenance_email_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `preventive_maintenance_id` int(11) unsigned NOT NULL COMMENT 'd_preventive_maintenance.id',
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
  KEY `idx_preventive_maintenance_id` (`preventive_maintenance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
