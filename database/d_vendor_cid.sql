-- Data Vendor (menu All NOC > Data Vendor): tambah kolom CID (opsional, diisi manual)
ALTER TABLE `d_vendor`
  ADD COLUMN `cid` varchar(100) DEFAULT NULL AFTER `nama_vendor`;
