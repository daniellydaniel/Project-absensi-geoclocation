<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LokasiPresensi extends Migration
{
    public function up()
    {
        // Menentukan field untuk tabel 'pegawai'
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'alamat_lokasi' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tipe_lokasi' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'latitude' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'longitude' => [
                'type' => 'varchar',
                'constraint' => 50,
            ],
            'radius' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'zona_waktu' => [
                'type' => 'VARCHAR',
                'constraint' => '4',
            ],
            'jam_masuk' => [
                'type' => 'TIME',
            ],
            'jam_pulang' => [
                'type' => 'TIME',
            ],
         ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('lokasi_presensi');
     
    } 
    public function down()
    {
        $this->forge->dropTable('lokasi_presensi');
    }
}
