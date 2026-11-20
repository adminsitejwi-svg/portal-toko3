-- =====================================================================
--  REQUEST (menu Informasi > Request, di bawah Hak Akses)
--  Jalankan SEKALI di database (lokal jwiid_dev & hosting).
--
--  Administrator membuat form seperti Google Form, memilih akun yang boleh mengisi
--  dan (opsional) halaman sidebar tempat request itu diingatkan.
--
--  - d_request          : judul, deskripsi, halaman, batas waktu, status, susunan isian (JSON)
--  - d_request_target   : akun yang boleh / wajib mengisi
--  - d_request_jawaban  : jawaban tiap akun (JSON) + status review admin
--
--  File upload jawaban disimpan di writable/uploads/request/<id request>/
-- =====================================================================

CREATE TABLE IF NOT EXISTS `d_request` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `judul`       VARCHAR(200) NOT NULL,
  `deskripsi`   TEXT NULL,
  `halaman`     VARCHAR(100) NULL COMMENT 'kunci halaman di app/Config/HakAkses.php, NULL = umum',
  `batas_waktu` DATETIME NULL,
  `status`      ENUM('aktif','ditutup') NOT NULL DEFAULT 'aktif',
  `boleh_ubah`  TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = pengisi boleh mengubah jawaban setelah dikirim',
  `fields`      LONGTEXT NULL COMMENT 'JSON susunan isian',
  `created_by`  INT UNSIGNED NULL,
  `created_at`  DATETIME NULL,
  `updated_at`  DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_request_status` (`status`),
  KEY `idx_request_halaman` (`halaman`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `d_request_target` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `request_id` INT UNSIGNED NOT NULL,
  `user_id`    INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_request_target` (`request_id`, `user_id`),
  KEY `idx_request_target_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `d_request_jawaban` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `request_id`    INT UNSIGNED NOT NULL,
  `user_id`       INT UNSIGNED NOT NULL,
  `jawaban`       LONGTEXT NULL COMMENT 'JSON {id isian: nilai}',
  `status`        ENUM('terkirim','disetujui','revisi') NOT NULL DEFAULT 'terkirim',
  `catatan_admin` TEXT NULL,
  `dikirim_at`    DATETIME NULL,
  `diubah_at`     DATETIME NULL,
  `direview_at`   DATETIME NULL,
  `direview_oleh` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_request_jawaban` (`request_id`, `user_id`),
  KEY `idx_request_jawaban_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
