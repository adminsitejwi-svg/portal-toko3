-- Data BTS: note per vendor diisi manual di form BTS (sebelumnya otomatis dari Note di Data Vendor)
-- Data Pelanggan (d_pelanggan_noc_vendor) sudah punya kolom note sendiri, tidak perlu diubah
ALTER TABLE `d_bts_vendor`
  ADD COLUMN `note` text DEFAULT NULL AFTER `vendor_id`;
