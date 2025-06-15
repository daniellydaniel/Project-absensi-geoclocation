<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotifikasiModel;

class Notifikasi extends BaseController
{
    protected $notifikasiModel;

    public function __construct()
    {
        // Inisialisasi model notifikasi
        $this->notifikasiModel = new NotifikasiModel();
    }

    public function index()
    {
        helper('time'); // pastikan helper di-load

        // Ambil data user dari session
        $userId = session()->get('id');
        $role = session()->get('role');

        // Ambil notifikasi berdasarkan user dan role
        $notifikasi = $this->notifikasiModel
            ->where('user_id', $userId)
            ->where('role', $role)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Passing data ke view
        return view('pegawai/notifikasi', ['notifikasi' => $notifikasi]);
    }
}
