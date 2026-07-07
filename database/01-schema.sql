-- =====================================================================
--  Portal Toko - Skema Database
--  Direkonstruksi dari Model CodeIgniter 4 (proyek tidak menyertakan dump SQL)
--
--  Catatan penting tentang penamaan tabel:
--  Permintaan "tabel midi, lawson, alfamart dijadikan D.midi, D.lawson,
--  D.alfamart" diterapkan dengan membuat database terpisah bernama `D`
--  yang menampung ketiga tabel toko. Dengan begitu, di MySQL notasi
--  `D.midi` berarti database `D`, tabel `midi` (valid & rapi).
--  Master data (md_*) dan login tetap berada di database `jwiid_dev`.
-- =====================================================================

SET NAMES utf8mb4;
SET time_zone = '+07:00';

-- ---------------------------------------------------------------------
-- DATABASE UTAMA: jwiid_dev  (master data + login)
-- ---------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `jwiid_dev`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- DATABASE KHUSUS DATA TOKO: D  (D.midi, D.lawson, D.alfamart)
CREATE DATABASE IF NOT EXISTS `D`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `jwiid_dev`;

-- ============================ LOGIN ==================================
CREATE TABLE IF NOT EXISTS `login` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(100) NOT NULL,
  `password`   VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ===================== MASTER: DC (md_dc) ============================
CREATE TABLE IF NOT EXISTS `md_dc` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_dc`    VARCHAR(150) NULL,
  `alamat_dc`  TEXT NULL,
  `status`     VARCHAR(50) NULL,
  `keterangan` TEXT NULL,
  `created_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================= MASTER: Media Koneksi =============================
CREATE TABLE IF NOT EXISTS `md_media_koneksi` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `media_koneksi` VARCHAR(150) NULL,
  `status`        VARCHAR(50) NULL,
  `keterangan`    TEXT NULL,
  `created_at`    DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================= MASTER: Layanan JWI Group ========================
CREATE TABLE IF NOT EXISTS `md_layanan_jwi_group` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_layanan` VARCHAR(150) NULL,
  `status`       VARCHAR(50) NULL,
  `keterangan`   TEXT NULL,
  `created_at`   DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================= MASTER: Pemilik Projek ===========================
CREATE TABLE IF NOT EXISTS `md_pemilik_projek` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pemilik`   VARCHAR(150) NULL,
  `alamat_lengkap` TEXT NULL,
  `pic_projek`     VARCHAR(150) NULL,
  `nomor_hp_pic`   VARCHAR(50) NULL,
  `status`         VARCHAR(50) NULL,
  `keterangan`     TEXT NULL,
  `created_at`     DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ===================== MASTER: Vendor ===============================
CREATE TABLE IF NOT EXISTS `md_vendor` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_vendor`  VARCHAR(150) NULL,
  `alamat_vendor` TEXT NULL,
  `status`       VARCHAR(50) NULL,
  `keterangan`   TEXT NULL,
  `created_at`   DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================= MASTER: Layanan Vendor ===========================
CREATE TABLE IF NOT EXISTS `md_layanan_vendor` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_layanan` VARCHAR(150) NULL,
  `status`       VARCHAR(50) NULL,
  `keterangan`   TEXT NULL,
  `created_at`   DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================= MASTER: Jenis Perangkat ==========================
CREATE TABLE IF NOT EXISTS `md_jenis_perangkat` (
  `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `jenis_perangkat`   VARCHAR(150) NULL,
  `merk_perangkat_id` INT UNSIGNED NULL,
  `type_perangkat`    VARCHAR(150) NULL,
  `status`            VARCHAR(50) NULL,
  `keterangan`        TEXT NULL,
  `created_at`        DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================= MASTER: Merek Perangkat ==========================
CREATE TABLE IF NOT EXISTS `md_merek_perangkat` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `merk_perangkat` VARCHAR(150) NULL,
  `status`         VARCHAR(50) NULL,
  `keterangan`     TEXT NULL,
  `created_at`     DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ---------------------------------------------------------------------
-- DATABASE D : TABEL DATA TOKO
-- ---------------------------------------------------------------------
USE `D`;

-- =========================== D.midi =================================
CREATE TABLE IF NOT EXISTS `midi` (
  `id`                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pemilik_projek_id`       INT UNSIGNED NULL,
  `nama_dc_id`              INT UNSIGNED NULL,
  `media_koneksi_id`        INT UNSIGNED NULL,
  `vendor_id`               INT UNSIGNED NULL,
  `layanan_vendor_id`       INT UNSIGNED NULL,
  `jenis_perangkat_id`      INT UNSIGNED NULL,
  `merk_perangkat_id`       INT UNSIGNED NULL,
  `nama_alfamidi`           VARCHAR(200) NULL,
  `kode_toko`               VARCHAR(100) NULL,
  `alamat_alfamidi`         TEXT NULL,
  `titik_koor_toko`         VARCHAR(150) NULL,
  `map_toko`                TEXT NULL,
  `pic_toko`                VARCHAR(150) NULL,
  `nomor_hp_pic`            VARCHAR(50) NULL,
  `tanggal_installasi`      DATE NULL,
  `tanggal_aktivasi`        DATE NULL,
  `kapasitas_bandwidth`     VARCHAR(100) NULL,
  `ip_address`              VARCHAR(100) NULL,
  `type_perangkat`          VARCHAR(150) NULL,
  `serial_number_perangkat` VARCHAR(150) NULL,
  `status`                  VARCHAR(50) NULL,
  `keterangan`              TEXT NULL,
  `upload_lampiran`         TEXT NULL,
  `created_at`              DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_midi_kode_toko` (`kode_toko`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================== D.lawson ===============================
CREATE TABLE IF NOT EXISTS `lawson` (
  `id`                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pemilik_projek_id`       INT UNSIGNED NULL,
  `nama_dc_id`              INT UNSIGNED NULL,
  `media_koneksi_id`        INT UNSIGNED NULL,
  `vendor_id`               INT UNSIGNED NULL,
  `layanan_vendor_id`       INT UNSIGNED NULL,
  `jenis_perangkat_id`      INT UNSIGNED NULL,
  `merk_perangkat_id`       INT UNSIGNED NULL,
  `nama_lawson`             VARCHAR(200) NULL,
  `kode_toko`               VARCHAR(100) NULL,
  `alamat_lawson`           TEXT NULL,
  `titik_koor_toko`         VARCHAR(150) NULL,
  `map_toko`                TEXT NULL,
  `pic_toko`                VARCHAR(150) NULL,
  `nomor_hp_pic`            VARCHAR(50) NULL,
  `tanggal_installasi`      DATE NULL,
  `tanggal_aktivasi`        DATE NULL,
  `kapasitas_bandwidth`     VARCHAR(100) NULL,
  `ip_address`              VARCHAR(100) NULL,
  `type_perangkat`          VARCHAR(150) NULL,
  `serial_number_perangkat` VARCHAR(150) NULL,
  `status`                  VARCHAR(50) NULL,
  `keterangan`              TEXT NULL,
  `upload_lampiran`         TEXT NULL,
  `created_at`              DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lawson_kode_toko` (`kode_toko`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================= D.alfamart ==============================
CREATE TABLE IF NOT EXISTS `alfamart` (
  `id`                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pemilik_projek_id`       INT UNSIGNED NULL,
  `nama_dc_id`              INT UNSIGNED NULL,
  `media_koneksi_id`        INT UNSIGNED NULL,
  `vendor_id`               INT UNSIGNED NULL,
  `layanan_vendor_id`       INT UNSIGNED NULL,
  `jenis_perangkat_id`      INT UNSIGNED NULL,
  `merk_perangkat_id`       INT UNSIGNED NULL,
  `nama_alfamart`           VARCHAR(200) NULL,
  `kode_toko`               VARCHAR(100) NULL,
  `alamat_alfamart`         TEXT NULL,
  `titik_koor_toko`         VARCHAR(150) NULL,
  `map_toko`                TEXT NULL,
  `pic_toko`                VARCHAR(150) NULL,
  `nomor_hp_pic`            VARCHAR(50) NULL,
  `tanggal_installasi`      DATE NULL,
  `tanggal_aktivasi`        DATE NULL,
  `kapasitas_bandwidth`     VARCHAR(100) NULL,
  `ip_address`              VARCHAR(100) NULL,
  `type_perangkat`          VARCHAR(150) NULL,
  `serial_number_perangkat` VARCHAR(150) NULL,
  `status`                  VARCHAR(50) NULL,
  `keterangan`              TEXT NULL,
  `upload_lampiran`         TEXT NULL,
  `created_at`              DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_alfamart_kode_toko` (`kode_toko`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
