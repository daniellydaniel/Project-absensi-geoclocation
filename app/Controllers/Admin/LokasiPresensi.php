<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LokasiPresensiModel;

class LokasiPresensi extends BaseController
{
    public function index()
    {
        $lokasiPresensiModel = new LokasiPresensiModel();
        $data = [
            'title' => 'Lokasi Absensi',
            'lokasi_presensi' => $lokasiPresensiModel->findAll()
        ];

        return view('admin/lokasi_presensi/lokasi_presensi', $data);
    }

    public function detail($id)
    {
        $lokasiPresensiModel = new LokasiPresensiModel();
        $data = [
            'title' => 'Detail Lokasi Absensi',
            'lokasi_presensi' => (new LokasiPresensiModel())->find($id),
        ];

        return view('admin/lokasi_presensi/detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Lokasi Absensi',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/lokasi_presensi/create', $data);
    }

    public function store()
    {
        dd($this->request->getPost());
        $rules = [
            'nama_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Lokasi wajib diisi!!"
                ],
            ],
            'alamat_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Alamat Lokasi wajib diisi!!"
                ],
            ],
            'tipe_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tipe Lokasi wajib diisi!!"
                ],
            ],
            'latitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Latitude wajib diisi!!"
                ],
            ],
            'longitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Longitude wajib diisi!!"
                ],
            ],
            'radius' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Radius wajib diisi!!"
                ],
            ],
            'zona_waktu' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Zona waktu wajib diisi!!"
                ],
            ],
            'jam_masuk' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jam masuk wajib diisi!!"
                ],
            ],
            'jam_pulang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jam pulang wajib diisi!!"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return view('admin/lokasi_presensi/create', [
                'title' => 'Tambah Lokasi Absensi',
                'validation' => $this->validator
            ]);
        }

        $lokasiPresensiModel = new LokasiPresensiModel();
        $lokasiPresensiModel->insert([
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'alamat_lokasi' => $this->request->getPost('alamat_lokasi'),
            'tipe_lokasi' => $this->request->getPost('tipe_lokasi'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'radius' => $this->request->getPost('radius'),
            'zona_waktu' => $this->request->getPost('zona_waktu'),
            'jam_masuk' => $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang'),

        ]);

        return redirect()->to(base_url('admin/lokasi_presensi'))->with('success', 'Data lokasi Absensi berhasil disimpan!');
    }

    public function edit($id)
    {
        $lokasiPresensiModel = new LokasiPresensiModel();
        $lokasi_presensi = $lokasiPresensiModel->find($id);
    
        if (!$lokasi_presensi) {
            return redirect()->to('/admin/lokasi_presensi')->with('error', 'Data tidak ditemukan!');
        }
    
        $data = [
            'title' => 'Edit Lokasi Absensi',
            'lokasi_presensi' => $lokasi_presensi,
            'validation' => \Config\Services::validation()
        ];
        return view('admin/lokasi_presensi/edit', $data);
        
    }
    

    public function update($id)
    {
        $lokasiPresensiModel = new LokasiPresensiModel();

        $rules = [
            'nama_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Lokasi wajib diisi!!"
                ],
            ],
            'alamat_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Alamat Lokasi wajib diisi!!"
                ],
            ],
            'tipe_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tipe Lokasi wajib diisi!!"
                ],
            ],
            'latitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Latitude wajib diisi!!"
                ],
            ],
            'longitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Longitude wajib diisi!!"
                ],
            ],
            'radius' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Radius wajib diisi!!"
                ],
            ],
            'zona_waktu' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Zona waktu wajib diisi!!"
                ],
            ],
            'jam_masuk' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jam masuk wajib diisi!!"
                ],
            ],
            'jam_pulang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jam pulang wajib diisi!!"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return view('admin/lokasi_presensi/edit', $data = [
                'title' => 'Edit Lokasi Absensi',
                'lokasi_presensi' => $lokasiPresensiModel->find($id),
                'validation' => \Config\Services::validation()
            ]);
        }

        $lokasiPresensiModel->update($id, [
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'alamat_lokasi' => $this->request->getPost('alamat_lokasi'),
            'tipe_lokasi' => $this->request->getPost('tipe_lokasi'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'radius' => $this->request->getPost('radius'),
            'zona_waktu' => $this->request->getPost('zona_waktu'),
            'jam_masuk' => $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang'),
        ]);

        return redirect()->to(base_url('admin/lokasi_presensi'))->with('success', 'Data lokasi presensi berhasil diperbarui!');
    }

    public function delete($id)
    {
        $lokasiPresensiModel = new LokasiPresensiModel();

        $lokasipresensi = $lokasiPresensiModel->find($id);
        if ($lokasipresensi) {
            $lokasiPresensiModel->delete($id);

            return redirect()->to(base_url('admin/lokasi_presensi'))->with('success', 'Data lokasi presensi berhasil dihapus!');
        }
    }
}
