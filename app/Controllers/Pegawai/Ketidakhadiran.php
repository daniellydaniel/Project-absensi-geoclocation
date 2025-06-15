<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\KetidakhadiranModel;
use App\Models\NotifikasiModel;

class Ketidakhadiran extends BaseController
{
    function __construct()
    {
        helper(['url', 'form']);
    }

    public function index()
    {
        $ketidakhadiranModel = new KetidakhadiranModel();
        $id_pegawai = session()->get('id_pegawai');
        $data = [
            'title' => 'Ketidakhadiran',
            'ketidakhadiran' => $ketidakhadiranModel->where('id_pegawai', $id_pegawai)->findAll()
        ];
        return view('pegawai/ketidakhadiran', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Ajukan Ketidakhadiran',
            'validation' => \Config\Services::validation()
        ];
        return view('pegawai/create_ketidakhadiran', $data);
    }

    public function store()
    {
        $rules = [
            'nip' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "NIP wajib diisi!!"
                ],
            ],

            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama wajib diisi!!"
                ],
            ],

            'keterangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Keterangan wajib diisi!!"
                ],
            ],
            'tanggal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal wajib diisi!!"
                ],
            ],
            'deskripsi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Deskripsi wajib diisi!!"
                ],
            ],

            'file' => [
                'rules' => 'max_size[file,10240]|mime_in[file,image/png,image/jpeg,image/jpg,application/pdf]',
                'errors' => [
                    'max_size' => "Ukuran file melebihi 10MB",
                    'mime_in' => "Jenis file yang diizinkan hanya PNG, JPG, JPEG atau PDF"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Ajukan Ketidakhadiran',
                'validation' => \Config\Services::validation()
            ];
            return view('pegawai/create_ketidakhadiran', $data);
        } else {
            $ketidakhadiranModel = new KetidakhadiranModel();
            $notifikasiModel = new NotifikasiModel();

            $file = $this->request->getFile('file');

            if ($file->getError() == 4) {
                $nama_file = '';
            } else {
                $nama_file = $file->getRandomName();
                // Pindahkan file ke folder 'profile'
                $file->move('profile', $nama_file);
            }
        }

        $ketidakhadiranModel->insert([
            'nip' => $this->request->getPost('nip'),
            'nama' => $this->request->getPost('nama'),
            'keterangan' => $this->request->getPost('keterangan'),
            'tanggal' => $this->request->getPost('tanggal'),
            'id_pegawai' => $this->request->getPost('id_pegawai'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'status' => 'Menunggu',
            'file' => $nama_file,
        ]);

        // Simpan notifikasi untuk admin
        $notifikasiModel->insert([
            'user_id'       => 1, // ID admin, sesuaikan dengan ID admin di database
            'nama_pengirim' => $this->request->getPost('nama'),
            'tipe'          => 'admin',
            'jenis'         => 'pengajuan',
            'deskripsi'     => "Pegawai {$this->request->getPost('nama')} telah mengajukan ketidakhadiran pada tanggal {$this->request->getPost('tanggal')}.",
            'foto'          => 'pegawai.png', // Ganti dengan foto pegawai jika ada
        ]);

        return redirect()->to(base_url('pegawai/ketidakhadiran'))->with('success', 'Ketidakhadiran berhasil diajukan');
    }

    public function edit($id)
    {
        $ketidakhadiranModel = new KetidakhadiranModel();
        $data = [
            'title' => 'Edit Ketidakhadiran',
            'ketidakhadiran' => $ketidakhadiranModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('pegawai/edit_ketidakhadiran', $data);
    }

    public function update($id)
    {
        $ketidakhadiranModel = new KetidakhadiranModel();

        $rules = [
            'nip' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "NIP wajib diisi!!"
                ],
            ],

            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama wajib diisi!!"
                ],
            ],

            'keterangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Keterangan wajib diisi!!"
                ],
            ],
            'tanggal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal wajib diisi!!"
                ],
            ],
            'deskripsi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Deskripsi wajib diisi!!"
                ],
            ],

        ];

        if (!$this->validate($rules)) {
            $ketidakhadiranModel = new KetidakhadiranModel();
            $data = [
                'title' => 'Edit Ketidakhadiran',
                'ketidakhadiran' => $ketidakhadiranModel->find($id),
                'validation' => \Config\Services::validation()
            ];
            return view('pegawai/edit_ketidakhadiran', $data);
        }
        $ketidakhadiranModel = new KetidakhadiranModel();

            $file = $this->request->getFile('file');

            if ($file->getError() == 4) {
                $nama_file = $this->request->getPost('file_lama');
            } else {
                $nama_file = $file->getRandomName();
                // Pindahkan file ke folder 'profile'
                $file->move('file_ketidakhadiran', $nama_file);
            }
            $ketidakhadiranModel->update($id, [
            'nip' => $this->request->getPost('nip'),
            'nama' => $this->request->getPost('nama'),
            'keterangan' => $this->request->getPost('keterangan'),
            'tanggal' => $this->request->getPost('tanggal'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'status' => 'Menunggu',
            'file' => $nama_file,
        ]);

        
        return redirect()->to(base_url('pegawai/ketidakhadiran'))->with('success', 'data ketidakhadiran berhasil diubah');
    }

    public function delete($id)
    {
        $ketidakhadiranModel = new KetidakhadiranModel();
        $ketidakhadiran = $ketidakhadiranModel->find($id);
        if ($ketidakhadiran) {
            $ketidakhadiranModel->where('id_pegawai', $id)->delete();
            $ketidakhadiranModel->delete($id);

            return redirect()->to(base_url('pegawai/dketidakhadiran'))->with('success', 'Data Ketidakhadiran berhasil dihapus!');
        }
    }
}
