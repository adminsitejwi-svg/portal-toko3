<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Remove VLAN and Used fields from the IP Public subnet table.
 * Existing values in these columns are permanently discarded by this migration.
 */
class RemoveIpPublicVlanAndUsed extends Migration
{
    private string $table = 'd_ip_public_subnet';

    public function up(): void
    {
        if (! $this->db->tableExists($this->table)) {
            return;
        }

        foreach (['vlan', 'ip_terpakai'] as $column) {
            if ($this->db->fieldExists($column, $this->table)) {
                $this->forge->dropColumn($this->table, $column);
            }
        }
    }

    public function down(): void
    {
        if (! $this->db->tableExists($this->table)) {
            return;
        }

        $fields = [];
        if (! $this->db->fieldExists('vlan', $this->table)) {
            $fields['vlan'] = [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'VLAN (saran dari Data VLAN)',
            ];
        }
        if (! $this->db->fieldExists('ip_terpakai', $this->table)) {
            $fields['ip_terpakai'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Used: jumlah IP terpakai',
            ];
        }

        if ($fields !== []) {
            $this->forge->addColumn($this->table, $fields);
        }
    }
}
