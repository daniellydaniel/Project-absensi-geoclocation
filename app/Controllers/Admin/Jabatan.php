<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\JabatanModel;

class Jabatan extends BaseController
{
    public function index()
    {
        $jabatanModel = new JabatanModel();
        $data = [
            'title' => 'Jabatan',
            'jabatan' => $jabatanModel->findAll()
        ];

        return view('admin/jabatan/jabatan', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Jabatan',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/jabatan/create', $data);
    }

    public function store()
    {
        $rules = [
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Jabatan wajib diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return view('admin/jabatan/create', [
                'title' => 'Tambah Jabatan',
                'validation' => $this->validator // Menggunakan $this->validator untuk mengambil pesan error
            ]);
        }

        // Jika validasi berhasil, simpan ke database
        $jabatanModel = new JabatanModel();
        $jabatanModel->insert([
            'jabatan' => $this->request->getPost('jabatan')
        ]);

        return redirect()->to(base_url('admin/jabatan'))->with('success', 'Data berhasil ditambahkan!');
    }
    public function edit($id)
    {
        $jabatanModel = new JabatanModel();
        $data = [
            'title' => 'Edit Jabatan',
            'jabatan' => $jabatanModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/jabatan/edit', $data);
    }

    public function update($id)
    {
        $jabatanModel = new JabatanModel();

        $rules = [
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Jabatan wajib diisi"
                ],
            ],
        ];

        // Cek validasi dulu, kalau gagal langsung return
        if (!$this->validate($rules)) {
            return view('admin/jabatan/edit', [
                'title' => 'Edit Jabatan',
                'jabatan' => $jabatanModel->find($id),
                'validation' => \Config\Services::validation()
            ]);
        }

        // Jika validasi berhasil, update data
        $jabatanModel->update($id, [
            'jabatan' => $this->request->getPost('jabatan')
        ]);

        return redirect()->to(base_url('admin/jabatan'))->with('success', 'Data berhasil diupdate!');
    }

    function delete($id)
    {
        $jabatanModel = new JabatanModel();

        $jabatan = $jabatanModel->find($id);
        if($jabatan){
            $jabatanModel->delete($id);

            return redirect()->to(base_url('admin/jabatan'))->with('success', 'Data berhasil dihapus!');
        }
    }
}
