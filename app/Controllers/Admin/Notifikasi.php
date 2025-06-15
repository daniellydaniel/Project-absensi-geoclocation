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

    public function clear()
    {
        // Contoh penghapusan semua notifikasi
        $this->notifikasiModel->truncate();

        return redirect()->back()->with('success', 'Semua notifikasi telah dihapus.');
    }
}
