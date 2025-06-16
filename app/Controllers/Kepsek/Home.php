<?php

namespace App\Controllers\Kepsek;

use App\Controllers\BaseController;
use App\Models\PresensiModel;
use App\Models\PegawaiModel;

class Home extends BaseController
{
    protected $rekapPresensiModel;
    protected $pegawaiModel;

    public function __construct()
    {
        $this->rekapPresensiModel = new PresensiModel();
        $this->pegawaiModel = new PegawaiModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Kepala Sekolah'
        ];
        return view('kepsek/home', $data);
    }

    public function rekap_harian()
    {
        $filter_tanggal = $this->request->getVar('filter_tanggal');

        if ($filter_tanggal) {
            $rekap_harian = $this->rekapPresensiModel->rekap_harian_filter($filter_tanggal);
        } else {
            $rekap_harian = $this->rekapPresensiModel->rekap_harian();
        }

        $data = [
            'title' => 'Rekap Harian',
            'tanggal' => $filter_tanggal,
            'rekap_harian' => $rekap_harian
        ];

        return view('kepsek/rekap_presensi/rekap_harian', $data);
    }

    public function rekap_bulanan()
    {
        $filter_bulan = $this->request->getVar('filter_bulan');
        $filter_tahun = $this->request->getVar('filter_tahun');

        if ($filter_bulan && $filter_tahun) {
            $rekap_bulanan = $this->rekapPresensiModel->rekap_bulanan_filter($filter_bulan, $filter_tahun);
        } else {
            $rekap_bulanan = $this->rekapPresensiModel->rekap_bulanan();
        }

        $pegawai = $this->pegawaiModel->findAll();

        $data = [
            'title' => 'Rekap Bulanan',
            'bulan' => $filter_bulan,
            'tahun' => $filter_tahun,
            'rekap_bulanan' => $rekap_bulanan,
            'pegawai' => $pegawai,
        ];

        return view('kepsek/rekap_presensi/rekap_bulanan', $data);
    }
}
