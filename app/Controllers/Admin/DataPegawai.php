<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PegawaiModel;
use App\Models\UserModel;
use App\Models\LokasiPresensiModel;
use App\Models\JabatanModel;

class DataPegawai extends BaseController
{

    function __construct()
    {
        helper(['url', 'form']);
    }
    public function index()
    {
        $pegawaiModel = new PegawaiModel();
        $data = [
            'title' => 'Data Guru',
            'pegawai' => $pegawaiModel->findAll()
        ];
        return view('admin/data_pegawai/data_pegawai', $data);
    }

    public function detail($id)
    {
        $pegawaiModel = new PegawaiModel();
        $pegawai = $pegawaiModel->detailPegawai($id);

        if (!$pegawai) {
            return redirect()->to(base_url('admin/data_pegawai'))->with('error', 'Pegawai tidak ditemukan!');
        }

        $data = [
            'title' => 'Detail Guru',
            'pegawai' => $pegawai,
        ];

        return view('admin/data_pegawai/detail', $data);
    }

    public function create()
    {
        $lokasi_presensi = new LokasiPresensiModel();
        $jabatan_model = new JabatanModel();
        $data = [
            'title' => 'Tambah Guru dan  Tata Usaha',
            'lokasi_presensi' => $lokasi_presensi->findAll(),
            'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/data_pegawai/create', $data);
    }

    public function generateNIP()
    {
        // Generate tanggal lahir (random antara 1960-2000)
        $tahunLahir = rand(1960, 2000);
        $bulanLahir = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
        $hariLahir = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT); // aman sampe 28 aja
        $tanggalLahir = $tahunLahir . $bulanLahir . $hariLahir;

        // Generate TMT (tahun pengangkatan, antara 2005-2022)
        $tahunTMT = rand(2005, 2022);
        $bulanTMT = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
        $tmt = $tahunTMT . $bulanTMT;

        // Angka tetap '2'
        $angkaTengah = '2';

        // Nomor urut dari database
        $pegawaiModel = new PegawaiModel();
        $pegawaiTerakhir = $pegawaiModel->select('nip')->orderBy('ID', 'DESC')->first();

        if ($pegawaiTerakhir && preg_match('/\d{8}\s\d{6}\s2\s(\d+)/', $pegawaiTerakhir['nip'], $match)) {
            $urutan = (int)$match[1] + 1;
        } else {
            $urutan = 1;
        }

        $nomorUrut = str_pad($urutan, 3, '0', STR_PAD_LEFT);

        return "$tanggalLahir $tmt $angkaTengah $nomorUrut";
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
            'jenis_kelamin' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jenis Kelamin wajib diisi!!"
                ],
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Alamat wajib diisi!!"
                ],
            ],
            'no_handphone' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "No. Handphone wajib diisi!!"
                ],
            ],
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jabatan wajib diisi!!"
                ],
            ],
            'lokasi_presensi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Lokasi Presensi wajib diisi!!"
                ],
            ],
            'foto' => [
                'rules' => 'uploaded[foto]|max_size[foto,10240]|mime_in[foto,image/png,image/jpeg,image/jpg]',
                'errors' => [
                    'uploaded' => "FIle foto wajib diupload!!",
                    'max_size' => "Ukuran foto melebihi 10mb",
                    'mime_in' => "Jenis file yang diizinkan hanya PNG, JPG, JPEG"
                ],
            ],
            'username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Username wajib diisi!!"
                ],
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Password wajib diisi!!"
                ],
            ],
            'konfirmasi_password' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => "konfirmasi Password wajib diisi!!",
                    'matches' => "konfirmasi Password tidak sesuai!!"
                ],
            ],
            'role' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Role wajib diisi!!"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $lokasi_presensi = new LokasiPresensiModel();
            $jabatan_model = new JabatanModel();
            $data = [
                'title' => 'Tambah Guru',
                'lokasi_presensi' => $lokasi_presensi->findAll(),
                'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
                'validation' => \Config\Services::validation()
            ];
            return view('admin/data_pegawai/create', $data);
        }

        $pegawaiModel = new PegawaiModel();
        $nipBaru = $this->generateNIP();

        $foto = $this->request->getFile('foto');
        if (!$foto || !$foto->isValid()) {
            $nama_Foto = '';
        } else {
            $nama_Foto = $foto->getRandomName();
            $foto->move('profile', $nama_Foto);
        }

        $pegawaiModel->insert([
            'nip' => $nipBaru,
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'alamat' => $this->request->getPost('alamat'),
            'no_handphone' => $this->request->getPost('no_handphone'),
            'jabatan' => $this->request->getPost('jabatan'),
            'lokasi_presensi' => $this->request->getPost('lokasi_presensi'),
            'foto' => $nama_Foto,
        ]);

        // Ambil ID pegawai yang baru saja ditambahkan
        $id_pegawai = $pegawaiModel->insertID();

        // Simpan data user
        $userModel = new UserModel();
        $userModel->insert([
            'id_pegawai' => $id_pegawai,
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status' => 'Aktif',
            'role' => $this->request->getPost('role'),
        ]);


        return redirect()->to(base_url('admin/data_pegawai'))->with('success', 'Data Pegawai berhasil disimpan!');
    }

    public function edit($id)
    {
        $lokasi_presensi = new LokasiPresensiModel();
        $jabatan_model = new JabatanModel();
        $pegawaiModel = new PegawaiModel();
        $data = [
            'title' => 'Edit Guru',
            'pegawai' => $pegawaiModel->editPegawai($id),
            'lokasi_presensi' => $lokasi_presensi->findAll(),
            'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/data_pegawai/edit', $data);
    }

    public function update($id)
    {
        $pegawaiModel = new PegawaiModel();

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
            'jenis_kelamin' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jenis Kelamin wajib diisi!!"
                ],
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Alamat wajib diisi!!"
                ],
            ],
            'no_handphone' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "No. Handphone wajib diisi!!"
                ],
            ],
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jabatan wajib diisi!!"
                ],
            ],
            'lokasi_presensi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Lokasi Presensi wajib diisi!!"
                ],
            ],
            'foto' => [
                'rules' => 'max_size[foto,10240]|mime_in[foto,image/png,image/jpeg,image/jpg]',
                'errors' => [
                    'max_size' => "Ukuran foto melebihi 10mb",
                    'mime_in' => "Jenis file yang diizinkan hanya PNG, JPG, JPEG"
                ],
            ],
            'username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Username wajib diisi!!"
                ],
            ],
            'role' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Role wajib diisi!!"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $lokasi_presensi = new LokasiPresensiModel();
            $jabatan_model = new JabatanModel();
            $pegawaiModel = new PegawaiModel();
            $data = [
                'title' => 'Tambah Guru',
                'pegawai' => $pegawaiModel->editPegawai($id),
                'lokasi_presensi' => $lokasi_presensi->findAll(),
                'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
                'validation' => \Config\Services::validation()
            ];
            return view('admin/data_pegawai/edit', $data);
        }
        $userModel = new UserModel();
        $foto = $this->request->getFile('foto');
        if (!$foto || !$foto->isValid()) {
            $nama_Foto = $this->request->getPost('foto_lama');
        } else {
            $nama_Foto = $foto->getRandomName();
            $foto->move('profile', $nama_Foto);
        }
        $pegawaiModel->update($id, [
            'nip' => $this->request->getPost('nip'),
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'alamat' => $this->request->getPost('alamat'),
            'no_handphone' => $this->request->getPost('no_handphone'),
            'jabatan' => $this->request->getPost('jabatan'),
            'lokasi_presensi' => $this->request->getPost('lokasi_presensi'),
            'foto' => $nama_Foto,
        ]);

        if ($this->request->getPost('password') == '') {
            $password = $this->request->getPost('password_lama');
        } else {
            $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }
        $userModel
            ->where('id_pegawai', $id)
            ->set([
                'username' => $this->request->getPost('username'),
                'password' => $password,
                'status' => $this->request->getPost('status'),
                'role' => $this->request->getPost('role'),
            ])
            ->update();

        return redirect()->to(base_url('admin/data_pegawai'))->with('success', 'Data Pegawai berhasil diperbarui!');
    }

    public function delete($id)
    {
        $pegawaiModel = new PegawaiModel();
        $userModel = new UserModel();
        $pegawai = $pegawaiModel->find($id);
        if ($pegawai) {
            $userModel->where('id_pegawai', $id)->delete();
            $pegawaiModel->delete($id);

            return redirect()->to(base_url('admin/data_pegawai'))->with('success', 'Data Pegawai berhasil dihapus!');
        }
    }
}
