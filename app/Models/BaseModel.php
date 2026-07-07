<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================================
 *  BASE MODEL — pencatat history otomatis
 *
 *  Setiap model data yang "extends BaseModel" akan otomatis mencatat
 *  aktivitas create / update / delete ke tabel activity_logs,
 *  lengkap dengan: username, tanggal, jam, aksi, nama tabel,
 *  ID baris, dan snapshot data lama/baru.
 *
 *  Cara pakai: ubah model Anda dari
 *      class VendorModel extends \CodeIgniter\Model
 *  menjadi
 *      class VendorModel extends \App\Models\BaseModel
 * =====================================================================
 */
class BaseModel extends Model
{
    // Daftar event CI4 yang kita pakai untuk audit.
    protected $afterInsert  = ['auditAfterInsert'];
    protected $beforeUpdate = ['auditBeforeUpdate'];
    protected $afterUpdate  = ['auditAfterUpdate'];
    protected $beforeDelete = ['auditBeforeDelete'];
    protected $afterDelete  = ['auditAfterDelete'];

    // Penampung snapshot data lama sebelum update/delete.
    protected $auditOldSnapshot = [];

    // ------------------------------------------------------------------
    //  CREATE
    // ------------------------------------------------------------------
    protected function auditAfterInsert(array $eventData)
    {
        helper('activity');

        $id      = $eventData['id'] ?? null;
        $newData = (array) ($eventData['data'] ?? []);

        log_activity('create', $this->table, $id, null, null, $newData);

        return $eventData;
    }

    // ------------------------------------------------------------------
    //  UPDATE
    // ------------------------------------------------------------------
    protected function auditBeforeUpdate(array $eventData)
    {
        $this->captureOld($eventData['id'] ?? null);
        return $eventData;
    }

    protected function auditAfterUpdate(array $eventData)
    {
        helper('activity');

        $ids     = $this->normalizeIds($eventData['id'] ?? null);
        $newData = (array) ($eventData['data'] ?? []);

        // Jika tidak ada id eksplisit, catat satu baris ringkas saja.
        if (empty($ids)) {
            log_activity('update', $this->table, null, null, null, $newData);
            return $eventData;
        }

        foreach ($ids as $id) {
            $old = $this->auditOldSnapshot[$id] ?? null;
            log_activity('update', $this->table, $id, null, $old, $newData);
        }

        $this->auditOldSnapshot = [];
        return $eventData;
    }

    // ------------------------------------------------------------------
    //  DELETE
    // ------------------------------------------------------------------
    protected function auditBeforeDelete(array $eventData)
    {
        $this->captureOld($eventData['id'] ?? null);
        return $eventData;
    }

    protected function auditAfterDelete(array $eventData)
    {
        helper('activity');

        $ids = $this->normalizeIds($eventData['id'] ?? null);

        // Bila id tidak tersedia di eventData, ambil dari snapshot.
        if (empty($ids)) {
            $ids = array_keys($this->auditOldSnapshot);
        }

        if (empty($ids)) {
            log_activity('delete', $this->table, null);
            return $eventData;
        }

        foreach ($ids as $id) {
            $old = $this->auditOldSnapshot[$id] ?? null;
            log_activity('delete', $this->table, $id, null, $old, null);
        }

        $this->auditOldSnapshot = [];
        return $eventData;
    }

    // ------------------------------------------------------------------
    //  HELPER INTERNAL
    // ------------------------------------------------------------------

    /**
     * Ambil snapshot baris lama sebelum diubah/dihapus.
     * Memakai query builder mentah agar tidak mengganggu builder model.
     */
    protected function captureOld($rawIds): void
    {
        $ids = $this->normalizeIds($rawIds);
        if (empty($ids)) {
            return;
        }

        try {
            $rows = $this->db->table($this->table)
                ->whereIn($this->primaryKey, $ids)
                ->get()
                ->getResultArray();

            foreach ($rows as $row) {
                if (isset($row[$this->primaryKey])) {
                    $this->auditOldSnapshot[$row[$this->primaryKey]] = $row;
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Audit captureOld gagal: ' . $e->getMessage());
        }
    }

    /**
     * Normalisasi nilai id menjadi array of scalar.
     */
    protected function normalizeIds($ids): array
    {
        if ($ids === null || $ids === '') {
            return [];
        }

        if (is_array($ids)) {
            return array_values(array_filter($ids, static fn($v) => $v !== null && $v !== ''));
        }

        return [$ids];
    }
}
