-- Data Toko Alfamart: kontak toko (Pejabat Toko, IT Cabang, PIC Aktivasi) & MAC Address perangkat — semuanya opsional
ALTER TABLE `d_alfamart`
  ADD COLUMN `nama_pejabat_toko` varchar(150) DEFAULT NULL AFTER `nomor_hp_pic`,
  ADD COLUMN `telp_pejabat_toko` varchar(20) DEFAULT NULL AFTER `nama_pejabat_toko`,
  ADD COLUMN `nama_it_cabang` varchar(150) DEFAULT NULL AFTER `telp_pejabat_toko`,
  ADD COLUMN `telp_it_cabang` varchar(20) DEFAULT NULL AFTER `nama_it_cabang`,
  ADD COLUMN `pic_aktivasi` varchar(150) DEFAULT NULL AFTER `telp_it_cabang`,
  ADD COLUMN `mac_address` varchar(17) DEFAULT NULL AFTER `serial_number_perangkat`;
