<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;
use App\Models\PresensiModel;
use CodeIgniter\HTTP\ResponseInterface;

class RekapPresensi extends BaseController
{
    public function index()
    {
        $presensiModel = new PresensiModel();
        $filterTanggal = $this->request->getGet('filter_tanggal');
    
        if ($filterTanggal) {
            $rekap = $presensiModel->rekap_harian_filter($filterTanggal);
        } else {
            $filterTanggal = date('Y-m-d');
            $rekap = $presensiModel->rekap_presensi_pegawai();
        }
    
        $data = [
            'title' => 'Riwayat Absensi',
            'rekap_presensi' => $rekap,
            'tanggal' => $filterTanggal
            
        ];
        return view('pegawai/rekap_presensi', $data );
    }
}
