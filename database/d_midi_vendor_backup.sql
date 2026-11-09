-- Data Toko Alfamidi: Vendor Link Backup (opsional), fitur sama dengan Vendor Main Link
-- media_koneksi_backup: NULL = tanpa link backup, 0 = Non Cellular (pakai nomor_inet_id_backup),
--                       1 = Cellular (pakai simcard_id_backup)
-- nomor_inet_id_backup: id dari d_nomor_inet (Data Penggunaan Nomor INET)
-- simcard_id_backup   : id dari d_simcard (Data Penggunaan Simcard)
-- Kolom Main Link tetap: media_koneksi, nomor_inet_id, simcard_id
ALTER TABLE `d_midi`
  ADD COLUMN `media_koneksi_backup` tinyint(1) DEFAULT NULL AFTER `media_koneksi`,
  ADD COLUMN `nomor_inet_id_backup` int(11) DEFAULT NULL AFTER `nomor_inet_id`,
  ADD COLUMN `simcard_id_backup` int(11) DEFAULT NULL AFTER `simcard_id`,
  ADD KEY `idx_nomor_inet_id_backup` (`nomor_inet_id_backup`),
  ADD KEY `idx_simcard_id_backup` (`simcard_id_backup`);
