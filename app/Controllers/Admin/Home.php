<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;
use App\Models\PresensiModel;
use App\Models\LokasiPresensiModel;
use App\Models\JabatanModel;
use App\Models\NotifikasiModel;

class Home extends BaseController
{
    public function index()
    {
        $pegawaiModel = new PegawaiModel();
        $presensiModel = new PresensiModel();
        $lokasipresensiModel = new LokasiPresensiModel();
        $jabatanModel = new JabatanModel();
        $notifikasiModel = new NotifikasiModel();

        $totalPegawai = $pegawaiModel->countAll();
        $presensiHariIni = $presensiModel->where('tanggal_masuk', date('Y-m-d'))->countAllResults();
        $lokasiPresensi = $lokasipresensiModel->countAll();
        $jabatan = $jabatanModel->countAll();

        // Ambil data lokasi kantor
        $lokasiRaw = $lokasipresensiModel->first();
        $lokasi = $lokasiRaw ? [
            'latitude' => floatval(trim($lokasiRaw['latitude'])),
            'longitude' => floatval(trim($lokasiRaw['longitude'])),
            'radius' => floatval(trim($lokasiRaw['radius'])),
            'zona_waktu' => $lokasiRaw['zona_waktu'],
        ] : [
            'latitude' => 0,
            'longitude' => 0,
            'radius' => 0,
            'zona_waktu' => 'WIB'
        ];

        // Ambil data presensi harian
        $rekapHarian = $presensiModel->rekap_harian();

        // Pisahkan pegawai dalam & luar radius
        $pegawaiDalamRadius = [];
        $pegawaiLuarRadius = [];

        foreach ($rekapHarian as $pegawai) {
            $lat = isset($pegawai['latitude_masuk']) ? floatval($pegawai['latitude_masuk']) : 0;
            $lng = isset($pegawai['longitude_masuk']) ? floatval($pegawai['longitude_masuk']) : 0;

            if ($lat !== 0 && $lng !== 0) {
                $pegawai['latitude'] = $lat;
                $pegawai['longitude'] = $lng;

                $jarak = $this->hitungJarak($lokasi['latitude'], $lokasi['longitude'], $lat, $lng);
                if ($jarak <= $lokasi['radius']) {
                    $pegawaiDalamRadius[] = $pegawai;
                } else {
                    $pegawaiLuarRadius[] = $pegawai;
                }
            }
        }

        $bulanIni = date('m');
        $tahunIni = date('Y');

        $presensiBulanIni = $presensiModel
            ->where('MONTH(tanggal_masuk)', $bulanIni)
            ->where('YEAR(tanggal_masuk)', $tahunIni)
            ->countAllResults();
        $data = [
            'title' => 'Home',
            'totalPegawai' => $totalPegawai,
            'presensiHariIni' => $presensiHariIni,
            'presensiBulanIni' => $presensiBulanIni,
            'lokasiPresensi' => $lokasiPresensi,
            'jabatan' => $jabatan,
            'presensi' => $rekapHarian,
            'lokasi_kantor' => $lokasi,
            'pegawaiDalamRadius' => $pegawaiDalamRadius,
            'pegawaiLuarRadius' => $pegawaiLuarRadius,
            'notifikasi' => $notifikasiModel->where('tipe', 'admin')->orderBy('created_at', 'DESC')->findAll() ?? []
        ];
        return view('admin/home', $data);
    }

    public function clear()
    {
        $notifikasiModel = new NotifikasiModel();
        $notifikasiModel->truncate();
        return redirect()->back()->with('success', 'Semua notifikasi telah dihapus.');
    }

    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
