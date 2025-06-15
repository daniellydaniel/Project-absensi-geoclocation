<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotifikasiModel;
use App\Models\PegawaiModel;
use App\Models\UserModel;
use App\Models\KetidakhadiranModel;
use CodeIgniter\HTTP\ResponseInterface;

class Ketidakhadiran extends BaseController
{
    public function index()
    {
        $ketidakhadiranModel = new KetidakhadiranModel();
        $notifikasiModel = new NotifikasiModel();
        
        $data = [
            'title' => 'Ketidakhadiran',
            'ketidakhadiran' => $ketidakhadiranModel->findAll(),
            'notifikasi' => $notifikasiModel->where('tipe', 'admin')->orderBy('created_at', 'DESC')->findAll() ?? [] 
            
        ];
        return view('admin/ketidakhadiran', $data);
    }

    public function approved($id)
    {
        $ketidakhadiranModel = new KetidakhadiranModel();
        $notifikasiModel = new NotifikasiModel();
        $pegawaiModel = new PegawaiModel();
        $userModel = new UserModel();

        // Update status izin
        $ketidakhadiranModel->update($id, ['status' => 'Diterima']);

        // Ambil data izin
        $izin = $ketidakhadiranModel->find($id);
        $pegawai = $pegawaiModel->find($izin['id_pegawai']);
        $pegawaiId = is_array($pegawai) ? $pegawai['id'] : $pegawai->id;
        $userPegawai = $userModel->where('id_pegawai', $pegawaiId)->first();

        // Simpan notifikasi ke pegawai
        $notifikasiModel->insert([
            'user_id'       => $userPegawai['id'],
            'nama_pengirim' => 'Admin',
            'tipe'          => 'pegawai',
            'jenis'         => 'status',
            'deskripsi'     => "Status izin tanggal {$izin['tanggal']} telah <b>Diterima</b>.",
            'foto'          => 'admin.png',
        ]);

        return redirect()->to(base_url('admin/ketidakhadiran'))->with('success', 'Status ketidakhadiran berhasil diterima');
    }

    public function rejected($id)
    {
        $ketidakhadiranModel = new KetidakhadiranModel();
        $notifikasiModel = new NotifikasiModel();
        $pegawaiModel = new PegawaiModel();
        $userModel = new UserModel();

        $ketidakhadiranModel->update($id, ['status' => 'Ditolak']);

        $izin = $ketidakhadiranModel->find($id);
        $pegawai = $pegawaiModel->find($izin['id_pegawai']);
        $pegawaiId = is_array($pegawai) ? $pegawai['id'] : $pegawai->id;
        $userPegawai = $userModel->where('id_pegawai', $pegawaiId)->first();

        $notifikasiModel->insert([
            'user_id'       => $userPegawai->id,
            'nama_pengirim' => 'Admin',
            'tipe'          => 'pegawai',
            'jenis'         => 'status',
            'deskripsi'     => "Status izin tanggal {$izin['tanggal']} telah <b>Ditolak</b>.",
            'foto'          => 'admin.png',
        ]);

        return redirect()->to(base_url('admin/ketidakhadiran'))->with('success', 'Status ketidakhadiran berhasil ditolak');
    }
}
