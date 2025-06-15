<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiModel extends Model
{
    protected $table            = 'presensi';
    protected $allowedFields    = [
        'id_pegawai',
        'nip',
        'tanggal_masuk',
        'jam_masuk',
        'foto_masuk',
        'latitude_masuk',
        'longitude_masuk',
        'tanggal_keluar',
        'jam_keluar',
        'foto_keluar',
        'latitude_keluar',
        'longitude_keluar',
    ];

    public function rekap_harian()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('presensi');
        $builder->select('
        presensi.*,
        pegawai.nama,
        pegawai.nip,
        presensi.latitude_masuk as latitude,
        presensi.longitude_masuk as longitude,
        lokasi_presensi.jam_masuk as jam_masuk_sekolah
    ');
        $builder->join('pegawai', 'pegawai.id = presensi.id_pegawai', 'left');
        $builder->join('lokasi_presensi', 'lokasi_presensi.id = pegawai.lokasi_presensi', 'left');
        $builder->where('tanggal_masuk', date('Y-m-d'));

        return $builder->get()->getResultArray();
    }


    public function rekap_harian_filter($filter_tanggal)
    {
        return $this->db->table('presensi')
            ->select('
            presensi.*, 
            pegawai.nama, 
            pegawai.nip,
            lokasi_presensi.jam_masuk AS jam_masuk_sekolah,
            lokasi_presensi.latitude,
            lokasi_presensi.longitude,
            IF(presensi.jam_keluar != "00:00:00", 
                TIMEDIFF(presensi.jam_keluar, presensi.jam_masuk), 
                NULL
            ) AS total_jam_kerja,
            IF(TIME(presensi.jam_masuk) > TIME(lokasi_presensi.jam_masuk), 
                TIMEDIFF(presensi.jam_masuk, lokasi_presensi.jam_masuk), 
                "00:00:00"
            ) AS total_keterlambatan
        ')
            ->join('pegawai', 'pegawai.id = presensi.id_pegawai', 'left')
            ->join('lokasi_presensi', 'lokasi_presensi.id = pegawai.lokasi_presensi', 'left')
            ->where('DATE(presensi.tanggal_masuk)', $filter_tanggal)
            ->get()
            ->getResultArray();
    }

    public function rekap_bulanan()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('presensi');
        $builder->select('presensi.*, pegawai.nama, pegawai.nip, lokasi_presensi.jam_masuk as jam_masuk_sekolah');
        $builder->join('pegawai', 'pegawai.id = presensi.id_pegawai');
        $builder->join('lokasi_presensi', 'lokasi_presensi.id = pegawai.lokasi_presensi');
        $builder->where('MONTH(tanggal_masuk)', date('m'));
        $builder->where('YEAR(tanggal_masuk)', date('Y'));
        return $builder->get()->getResultArray();
    }
    public function rekap_bulanan_filter($filter_bulan, $filter_tahun)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('presensi');
        $builder->select('presensi.*, pegawai.nama, pegawai.nip, lokasi_presensi.jam_masuk as jam_masuk_sekolah');
        $builder->join('pegawai', 'pegawai.id = presensi.id_pegawai', 'left');
        $builder->join('lokasi_presensi', 'lokasi_presensi.id = pegawai.lokasi_presensi', 'left');
        $builder->where('MONTH(tanggal_masuk)', $filter_bulan);
        $builder->where('YEAR(tanggal_masuk)', $filter_tahun);
        return $builder->get()->getResultArray();
    }

    public function getRekapHarian()
    {
        return $this->select('presensi.*, pegawai.nama')
            ->join('pegawai', 'pegawai.id = presensi.id_pegawai')
            ->where('tanggal_masuk', date('Y-m-d'))
            ->findAll();
    }

    public function rekap_presensi_pegawai()
    {
        $id_pegawai = session()->get('id_pegawai');
        $db      = \Config\Database::connect();
        $builder = $db->table('presensi');
        $builder->select('presensi.*, pegawai.nama, pegawai.nip, lokasi_presensi.jam_masuk as jam_masuk_sekolah');
        $builder->join('pegawai', 'pegawai.id = presensi.id_pegawai', 'left');
        $builder->join('lokasi_presensi', 'lokasi_presensi.id = pegawai.lokasi_presensi', 'left');
        $builder->where('id_pegawai', $id_pegawai);
        return $builder->get()->getResultArray();
    }

    public function getLokasiRealtime()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('presensi');
        $builder->select('
        presensi.id_pegawai,
        pegawai.nama,
        lokasi_presensi.latitude,
        lokasi_presensi.longitude,
        presensi.updated_at
    ');
        $builder->join('pegawai', 'pegawai.id = presensi.id_pegawai', 'left');
        $builder->join('lokasi_presensi', 'lokasi_presensi.id = pegawai.lokasi_presensi', 'left');
        $builder->where('tanggal_masuk', date('Y-m-d'));
        $builder->groupBy('presensi.id_pegawai');
        return $builder->get()->getResultArray();
    }
}
