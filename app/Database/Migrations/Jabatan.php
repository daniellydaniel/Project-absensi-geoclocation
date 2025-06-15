<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Jabatan extends Migration
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
            'jabatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
         ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('jabatan');
     
    } 
    public function down()
    {
        $this->forge->dropTable('jabatan');
    }
}
