<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LokasiPresensiModel;
use App\Models\PegawaiModel;
use App\Models\PresensiModel;
use App\Models\NotifikasiModel;


class Home extends BaseController
{
    public function index()
    {
        $id_pegawai = session()->get('id_pegawai');

        $lokasi_presensi = new LokasiPresensiModel();
        $pegawai_model = new PegawaiModel();
        $presensi_model = new PresensiModel();
        $notifikasiModel = new NotifikasiModel();
        $pegawai = $pegawai_model->where('id', $id_pegawai)->first();

        if (!$pegawai) {
            return redirect()->to('/login')->with('gagal', 'Data pegawai tidak ditemukan.');
        }

        $lokasi = $lokasi_presensi->where('id', $pegawai['lokasi_presensi'])->first();

        $data = [
            'title' => 'Dashboard Pegawai',
            'lokasi_presensi' => $lokasi ?? [
                'latitude' => 0,
                'longitude' => 0,
                'radius' => 100,
                'zona_waktu' => 'WIB'
            ],
            'cek_presensi' => $presensi_model
                ->where([
                    'id_pegawai' => $id_pegawai,
                    'tanggal_masuk' => date('Y-m-d')
                ])
                ->countAllResults(),

            'cek_presensi_keluar' => $presensi_model
                ->where('id_pegawai', $id_pegawai)
                ->where('tanggal_masuk', date('Y-m-d'))
                ->where('tanggal_keluar !=', '0000-00-00')
                ->countAllResults(),

            'ambil_presensi_masuk' => $presensi_model
                ->where('id_pegawai', $id_pegawai)
                ->where('tanggal_masuk', date('Y-m-d'))
                ->first()
        ];

        $data['lokasiPegawai'] = [
            'latitude' => 0,
            'longitude' => 0
        ];

        return view('pegawai/home', $data);
    }
    public function presensi_masuk()
    {
        $latitude_pegawai = (float) $this->request->getPost('latitude_pegawai');
        $longitude_pegawai = (float) $this->request->getPost('longitude_pegawai');
        $latitude_kantor = (float) $this->request->getPost('latitude_kantor');
        $longitude_kantor = (float) $this->request->getPost('longitude_kantor');
        $radius = (float) $this->request->getPost('radius');

        // Hitung jarak dengan Haversine Formula
        $earthRadius = 6371000; // dalam meter
        $dLat = deg2rad($latitude_kantor - $latitude_pegawai);
        $dLon = deg2rad($longitude_kantor - $longitude_pegawai);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($latitude_pegawai)) * cos(deg2rad($latitude_kantor)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $jarakmeter = $earthRadius * $c;

        if ($jarakmeter > $radius) {
            // notifikasi error
            session()->setFlashdata('gagal', '❌ Absensi gagal! Kamu berada di luar jangkauan lokasi.');
            return redirect()->to(base_url('pegawai/home'));
        } else {
            // Lanjut presensi
            session()->set('presensi_data', [
                'id_pegawai' => $this->request->getPost('id_pegawai'),
                'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
                'jam_masuk' => $this->request->getPost('jam_masuk'),
                'latitude_masuk' => $this->request->getPost('latitude_pegawai'),
                'longitude_masuk' => $this->request->getPost('longitude_pegawai'),
                'foto_masuk' => $this->request->getPost('foto_masuk', FILTER_SANITIZE_STRING)
            ]);
            return redirect()->to(base_url('pegawai/ambil_foto'));
        }
    }

    public function ambil_foto()
    {
        $data_presensi = session()->get('presensi_data');

        if (!$data_presensi) {
            // Handle ketika data gak ada
            return redirect()->to(base_url('pegawai/home'))->with('gagal', 'Data Absensi tidak ditemukan.');
        }

        $data = [
            'title' => "Ambil Foto Selfie",
            'id_pegawai' => $data_presensi['id_pegawai'],
            'tanggal_masuk' => $data_presensi['tanggal_masuk'],
            'jam_masuk' => $data_presensi['jam_masuk'],
            'latitude_masuk' => $data_presensi['latitude_masuk'],
            'longitude_masuk' => $data_presensi['longitude_masuk'],
        ];
        return view('pegawai/ambil_foto', $data);
    }

    public function presensi_masuk_aksi()
    {
        $data_presensi = session()->get('presensi_data');

        if (!$data_presensi) {
            return redirect()->to(base_url('pegawai/home'))->with('gagal', 'Data Absensi tidak ditemukan.');
        }

        $foto_base64 = $this->request->getPost('foto_masuk');

        try {
            $foto_parts = explode(";base64,", $foto_base64);
            $image_type_aux = explode("image/", $foto_parts[0]);
            $image_type = $image_type_aux[1];
            $foto_binary = base64_decode($foto_parts[1]);

            $nama_file = uniqid() . '.' . $image_type;
            file_put_contents(FCPATH . 'foto_presensi/' . $nama_file, $foto_binary);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }

        $presensi_model = new PresensiModel();

        $cek = $presensi_model
            ->where('id_pegawai', $data_presensi['id_pegawai'])
            ->where('tanggal_masuk', $data_presensi['tanggal_masuk'])
            ->first();

        if ($cek) {
            return $this->response->setJSON(['status' => 'already']);
        }

        $data = [
            'id_pegawai'      => $data_presensi['id_pegawai'],
            'tanggal_masuk'   => $data_presensi['tanggal_masuk'],
            'jam_masuk'       => $data_presensi['jam_masuk'],
            'latitude_masuk'  => $data_presensi['latitude_masuk'],
            'longitude_masuk' => $data_presensi['longitude_masuk'],
            'foto_masuk'      => $nama_file,
        ];

        $sukses = $presensi_model->insert($data);

        if ($sukses) {
            session()->remove('presensi_data');
            return redirect()->to(base_url('pegawai/home'))->with('success', 'Absensi masuk berhasil!');
        }
    }

    public function presensi_keluar($id)
    {
        $latitude_pegawai = (float) $this->request->getPost('latitude_pegawai');
        $longitude_pegawai = (float) $this->request->getPost('longitude_pegawai');
        $latitude_kantor = (float) $this->request->getPost('latitude_kantor');
        $longitude_kantor = (float) $this->request->getPost('longitude_kantor');
        $radius = (float) $this->request->getPost('radius');

        // Hitung jarak dengan Haversine Formula
        $earthRadius = 6371000; // dalam meter
        $dLat = deg2rad($latitude_kantor - $latitude_pegawai);
        $dLon = deg2rad($longitude_kantor - $longitude_pegawai);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($latitude_pegawai)) * cos(deg2rad($latitude_kantor)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $jarakmeter = $earthRadius * $c;

        if ($jarakmeter > $radius) {
            // notifikasi error
            session()->setFlashdata('gagal', '❌ Absensi gagal! Kamu berada di luar jangkauan lokasi.');
            return redirect()->to(base_url('pegawai/home'));
        } else {
            // Lanjut presensi
            session()->set('presensi_data', [
                'id_presensi' => $id,
                'tanggal_keluar' => $this->request->getPost('tanggal_keluar'),
                'jam_keluar' => $this->request->getPost('jam_keluar'),
            ]);
            return redirect()->to(base_url('pegawai/ambil_foto_keluar'));
        }
    }

    public function presensi_keluar_aksi($id)
    {
        $request = \Config\Services::request();

        $tanggal_keluar = $request->getPost('tanggal_keluar');
        $jam_keluar = $request->getPost('jam_keluar');
        $latitude_keluar = $request->getPost('latitude'); // ambil dari input
        $longitude_keluar = $request->getPost('longitude'); // ambil dari input
        $foto_keluar = $request->getPost('foto_keluar');

        // Proses simpan foto keluar dari base64
        $foto_keluar = str_replace('data:image/jpeg;base64,', '', $foto_keluar);
        $foto_keluar = base64_decode($foto_keluar);

        $folder = 'uploads/' . $id;
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $filename = time() . '.jpg';
        $foto_dir = $folder . '/' . $filename;
        $nama_foto = $id . '/' . $filename;

        file_put_contents($foto_dir, $foto_keluar);

        // Update data presensi keluar ke DB
        $presensi_model = new PresensiModel();
        $presensi_model->update($id, [
            'tanggal_keluar' => $tanggal_keluar,
            'jam_keluar' => $jam_keluar,
            'latitude_keluar' => $latitude_keluar,
            'longitude_keluar' => $longitude_keluar,
            'foto_keluar' => $nama_foto,
        ]);

        return redirect()->to(base_url('pegawai/home'))->with('success', 'Absensi keluar berhasil!');
    }


    public function ambil_foto_keluar()
    {
        $data_presensi = session()->get('presensi_data');

        if (!$data_presensi) {
            return redirect()->to(base_url('pegawai/home'))->with('gagal', 'Data Absensi tidak ditemukan.');
        }

        $data = [
            'title' => "Ambil Foto Selfie Keluar",
            'id_presensi' => $data_presensi['id_presensi'],
            'tanggal_keluar' => $data_presensi['tanggal_keluar'],
            'jam_keluar' => $data_presensi['jam_keluar'],
        ];

        return view('pegawai/ambil_foto_keluar', $data);
    }
}
