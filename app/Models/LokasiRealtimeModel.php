<?php

namespace App\Models;

use CodeIgniter\Model;

class LokasiRealtimeModel extends Model
{
    protected $table = 'lokasi_realtime';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_pegawai', 'latitude', 'longitude', 'updated_at'];
}
