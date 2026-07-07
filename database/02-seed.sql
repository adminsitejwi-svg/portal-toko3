-- =====================================================================
--  Portal Toko - Data Awal (seed)
--  Akun login default:  username = admin   password = admin123
--  Plus beberapa baris master agar dropdown form tidak kosong.
-- =====================================================================

USE `jwiid_dev`;

INSERT INTO `login` (`username`, `password`, `created_at`) VALUES
('admin', '$2y$10$63DKK2YCjjqur.ZF64IAuu1kpMIwM5Wsf/N1V8VuqftuxC2naeZpm', NOW());

INSERT INTO `md_dc` (`nama_dc`, `alamat_dc`, `status`, `keterangan`, `created_at`) VALUES
('DC Jakarta', 'Jl. Sudirman No. 1, Jakarta', 'Aktif', 'DC pusat', NOW()),
('DC Bandung',  'Jl. Asia Afrika No. 10, Bandung', 'Aktif', '-', NOW());

INSERT INTO `md_media_koneksi` (`media_koneksi`, `status`, `keterangan`, `created_at`) VALUES
('Fiber Optik', 'Aktif', '-', NOW()),
('Wireless',    'Aktif', '-', NOW());

INSERT INTO `md_layanan_jwi_group` (`nama_layanan`, `status`, `keterangan`, `created_at`) VALUES
('Internet Dedicated', 'Aktif', '-', NOW()),
('VPN IP',             'Aktif', '-', NOW());

INSERT INTO `md_pemilik_projek` (`nama_pemilik`, `alamat_lengkap`, `pic_projek`, `nomor_hp_pic`, `status`, `keterangan`, `created_at`) VALUES
('PT Sumber Alfaria',  'Jl. MH Thamrin, Tangerang', 'Budi',  '081200000001', 'Aktif', '-', NOW()),
('PT Lawson Indonesia','Jl. Gatot Subroto, Jakarta', 'Andi', '081200000002', 'Aktif', '-', NOW());

INSERT INTO `md_vendor` (`nama_vendor`, `alamat_vendor`, `status`, `keterangan`, `created_at`) VALUES
('Telkom',      'Jl. Japati No. 1, Bandung', 'Aktif', '-', NOW()),
('Indosat',     'Jl. Medan Merdeka, Jakarta', 'Aktif', '-', NOW());

INSERT INTO `md_layanan_vendor` (`nama_layanan`, `status`, `keterangan`, `created_at`) VALUES
('Astinet',  'Aktif', '-', NOW()),
('Metro-E',  'Aktif', '-', NOW());

INSERT INTO `md_merek_perangkat` (`merk_perangkat`, `status`, `keterangan`, `created_at`) VALUES
('Cisco',    'Aktif', '-', NOW()),
('Mikrotik', 'Aktif', '-', NOW());

INSERT INTO `md_jenis_perangkat` (`jenis_perangkat`, `merk_perangkat_id`, `type_perangkat`, `status`, `keterangan`, `created_at`) VALUES
('Router',  1, 'ISR 1100', 'Aktif', '-', NOW()),
('Switch',  2, 'CRS328',   'Aktif', '-', NOW());

-- Contoh 1 baris data toko agar uji "data sudah ada" bisa langsung dicoba
USE `D`;
INSERT INTO `midi`
 (`pemilik_projek_id`,`nama_dc_id`,`media_koneksi_id`,`vendor_id`,`layanan_vendor_id`,`jenis_perangkat_id`,`merk_perangkat_id`,
  `nama_alfamidi`,`kode_toko`,`alamat_alfamidi`,`pic_toko`,`nomor_hp_pic`,`status`,`created_at`)
VALUES
 (1,1,1,1,1,1,1,'Alfamidi Tebet','MIDI-001','Jl. Tebet Raya, Jakarta','Siti','081300000001','Aktif',NOW());
