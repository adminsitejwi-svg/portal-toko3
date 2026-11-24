-- Menghapus fitur ACS JWI Group dari database (permanen, tidak bisa dibatalkan).
-- Jalankan di hosting SETELAH file portal yang baru diunggah. Cadangkan database dulu kalau datanya masih diperlukan.
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `d_acs_data`, `d_acs_device`, `d_acs_event`, `d_acs_parameter`, `d_acs_profile`,
                     `d_acs_provision`, `d_acs_setting`, `d_acs_task`, `d_acs_wan`;
SET FOREIGN_KEY_CHECKS = 1;

-- hak akses halaman ACS
DELETE FROM `m_role_akses` WHERE `halaman` IN ('DashboardAcs', 'AcsDevice', 'AcsParameter', 'AcsProvisioning', 'AcsSetting', 'AcsLog', 'AcsKontrol');
DELETE FROM `m_user_akses` WHERE `halaman` IN ('DashboardAcs', 'AcsDevice', 'AcsParameter', 'AcsProvisioning', 'AcsSetting', 'AcsLog', 'AcsKontrol');
