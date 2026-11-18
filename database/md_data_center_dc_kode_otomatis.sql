-- Data Rak DC > Data Center: Kode DC sekarang otomatis (DC001, DC002, ...) dan tidak bisa diisi manual.
-- Script ini mengubah kode lama (yang dulu diisi manual, mis. "CB") menjadi DC001, DC002, ...
-- berurutan sesuai urutan data dibuat (id). Jalankan SEKALI di database hosting (dev.jwi.id).
-- Rak, perangkat & interkoneksi tidak terpengaruh karena terhubung lewat data_center_id, bukan kode.

-- 1) kode sementara dulu supaya tidak bentrok dengan UNIQUE KEY uniq_kode_dc
UPDATE `md_data_center_dc` SET `kode_dc` = CONCAT('TMP-', `id`);

-- 2) beri nomor urut DC001, DC002, ... sesuai id
SET @n := 0;
UPDATE `md_data_center_dc`
SET `kode_dc` = CONCAT('DC', LPAD((@n := @n + 1), 3, '0'))
ORDER BY `id`;
