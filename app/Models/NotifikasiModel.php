<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'nama_pengirim', 'tipe', 'jenis', 'deskripsi', 'foto', 'created_at', 'is_read'];
}
