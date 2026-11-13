-- =====================================================================
--  HAK AKSES (menu Informasi > Hak Akses)
--  Jalankan SEKALI di database (lokal jwiid_dev & hosting).
--
--  - m_role        : daftar role. is_admin = 1 -> bisa semua halaman + menu Hak Akses
--  - m_role_akses  : halaman yang boleh dibuka tiap role (kunci = segmen URL, lihat app/Config/HakAkses.php)
--  - m_user_akses  : halaman khusus per akun (dipakai bila login.akses_khusus = 1)
--  - login.role_id / login.akses_khusus : role & mode akses tiap akun
--
--  Semua akun yang sudah ada dijadikan Administrator supaya tidak ada yang terkunci;
--  setelah itu atur role tiap akun lewat menu Hak Akses.
-- =====================================================================

CREATE TABLE IF NOT EXISTS `m_role` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama`       VARCHAR(100) NOT NULL,
  `keterangan` VARCHAR(255) NULL,
  `is_admin`   TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_nama` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `m_role_akses` (
  `id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` INT UNSIGNED NOT NULL,
  `halaman` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_halaman` (`role_id`, `halaman`),
  KEY `idx_role_akses_role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `m_user_akses` (
  `id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `halaman` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_halaman` (`user_id`, `halaman`),
  KEY `idx_user_akses_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- kolom baru di tabel login (kalau error "Duplicate column", berarti sudah pernah dijalankan -> lewati)
ALTER TABLE `login`
  ADD COLUMN `role_id`      INT UNSIGNED NULL AFTER `password`,
  ADD COLUMN `akses_khusus` TINYINT(1) NOT NULL DEFAULT 0 AFTER `role_id`;

-- role bawaan
INSERT IGNORE INTO `m_role` (`nama`, `keterangan`, `is_admin`, `created_at`) VALUES
  ('Administrator', 'Bisa membuka semua halaman dan mengatur Hak Akses', 1, NOW()),
  ('Support',       'Default: Rekap Data Toko + Informasi',              0, NOW()),
  ('User',          'Default: Rekap Data Toko + Informasi (role akun baru)', 0, NOW());

-- halaman default Support & User: Rekap Data Toko + Informasi (Profile, Pengguna, Change Log)
-- (semuanya bisa diubah Administrator lewat menu Hak Akses)
INSERT IGNORE INTO `m_role_akses` (`role_id`, `halaman`)
  SELECT r.`id`, h.`halaman`
    FROM `m_role` r
    JOIN (SELECT 'RekapDataMidi' AS `halaman` UNION ALL SELECT 'Profile' UNION ALL SELECT 'settings' UNION ALL SELECT 'Logs') h
   WHERE r.`nama` IN ('Support', 'User');

-- akun lama -> Administrator
UPDATE `login`
   SET `role_id` = (SELECT `id` FROM `m_role` WHERE `nama` = 'Administrator')
 WHERE `role_id` IS NULL;
