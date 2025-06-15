<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Presensi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pegawai' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'tanggal_masuk' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jam_masuk' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'foto_masuk' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'tanggal_keluar' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jam_keluar' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'foto_keluar' => [
                'constraint' => 255,
                'null' => false,
            ],
         ]);
        $this->forge->addKey('id');
        $this->forge->addForeignKey('id_pegawai', 'pegawai', 'id','CASCADE', 'CASCADE');
        $this->forge->createTable('presensi');
     
    } 
    public function down()
    {
        $this->forge->dropTable('presensi');
    }
}