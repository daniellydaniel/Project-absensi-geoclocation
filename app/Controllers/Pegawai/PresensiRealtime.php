<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;
use App\Models\PresensiModel;

class PresensiRealtime extends BaseController
{
    public function getLokasiPegawaiRealtime()
    {
        $model = new PresensiModel();
        $data = $model->getLokasiRealtime();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
