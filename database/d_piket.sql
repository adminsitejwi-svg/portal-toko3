-- Tabel jadwal piket NOC (nama diambil dari data shift / jadwal_noc)
-- piket: 1 = PIKET 1 (oranye), 2 = PIKET 2 (kuning), 3 = PIKET 3 (hijau)
CREATE TABLE IF NOT EXISTS `d_piket` (
  `id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `tanggal`    DATE             NOT NULL,
  `nama`       VARCHAR(100)     NOT NULL,
  `piket`      TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `keterangan` TEXT             NULL,
  `created_at` DATETIME         NULL,
  `updated_at` DATETIME         NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tanggal` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
