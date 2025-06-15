<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PegawaiFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $currentRoute = trim($request->getUri()->getPath(), '/');

        // Contoh skip route khusus (misal API atau AJAX tertentu)
        $exceptPrefixes = [
            'pegawai/notifikasi',
            // tambahin route lain kalau perlu
        ];

        foreach ($exceptPrefixes as $prefix) {
            if (str_starts_with($currentRoute, $prefix)) {
                log_message('debug', 'PegawaiFilter skipped for: ' . $currentRoute);
                return;
            }
        }

        // Logging untuk ngecek session & route
        log_message('debug', 'PegawaiFilter applied to: ' . $currentRoute);
        log_message('debug', 'Session role: ' . $session->get('role'));

        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('pesan', 'Silakan login terlebih dahulu!');
            return redirect()->to('/login');
        }

        if (strcasecmp($session->get('role'), 'pegawai') !== 0) {
            $session->destroy();
            return redirect()->to('/login')->with('pesan', 'Akses ditolak, silakan login ulang.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Optional: tambah header atau logging setelah request
        $response->setHeader('X-Filtered-By', 'PegawaiFilter');
    }
}
