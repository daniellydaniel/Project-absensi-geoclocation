<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotifikasiModel;

class Notifikasi extends BaseController
{
    protected $notifikasiModel;

    public function __construct()
    {
        $this->notifikasiModel = new NotifikasiModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Notifikasi Presensi',
            'notifikasi' => $this->notifikasiModel
                ->orderBy('is_read', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->findAll()
        ];

        return view('admin/notifikasi/index', $data);
    }

    public function tandai_sudah_dibaca($id)
    {
        $this->notifikasiModel->update($id, ['is_read' => 1]);
        return redirect()->back()->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
    }

    public function clear()
    {
        // Hapus semua notifikasi
        $this->notifikasiModel->truncate();
        return redirect()->back()->with('success', 'Semua notifikasi telah dihapus.');
    }
}
