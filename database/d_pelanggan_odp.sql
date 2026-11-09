-- Pelanggan ODP (menu Data Teknis NOC > Pelanggan ODP): pelanggan yang tersambung ke ODP
-- odp_id: ODP dari Data ODC & ODP (d_odp); ODP yang masih punya pelanggan tidak bisa dihapus
-- kode_pelanggan unik (tidak boleh dobel); port = nomor port ODP (1 s/d kapasitas), satu port satu pelanggan
-- koordinat boleh kosong, bila diisi harus lengkap
CREATE TABLE IF NOT EXISTS `d_pelanggan_odp` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `odp_id` int(11) unsigned NOT NULL,
  `port` smallint(5) unsigned NOT NULL,
  `kode_pelanggan` varchar(50) NOT NULL,
  `nama_pelanggan` varchar(150) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kode_pelanggan_odp` (`kode_pelanggan`),
  UNIQUE KEY `uniq_port_pelanggan_odp` (`odp_id`, `port`),
  KEY `idx_pelanggan_odp` (`odp_id`),
  CONSTRAINT `fk_pelanggan_odp` FOREIGN KEY (`odp_id`) REFERENCES `d_odp` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bila tabel d_pelanggan_odp SUDAH dibuat sebelumnya (tanpa kolom port), jalankan ini saja:
-- ALTER TABLE `d_pelanggan_odp`
--   ADD COLUMN `port` smallint(5) unsigned NOT NULL AFTER `odp_id`,
--   ADD UNIQUE KEY `uniq_port_pelanggan_odp` (`odp_id`, `port`);
