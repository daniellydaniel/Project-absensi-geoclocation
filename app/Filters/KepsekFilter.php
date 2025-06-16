<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class KepsekFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $currentRoute = trim($request->getUri()->getPath(), '/');

        // Jika ingin skip route tertentu (misalnya AJAX notifikasi), tinggal tambahkan di sini
        $exceptPrefixes = [
            // 'kepsek/notifikasi',
        ];

        foreach ($exceptPrefixes as $prefix) {
            if (str_starts_with($currentRoute, $prefix)) {
                log_message('debug', 'KepsekFilter skipped for: ' . $currentRoute);
                return;
            }
        }

        // Logging info
        log_message('debug', 'KepsekFilter applied to: ' . $currentRoute);
        log_message('debug', 'Session role: ' . $session->get('role'));

        // Cek login
        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('pesan', 'Silakan login terlebih dahulu!');
            return redirect()->to('/login');
        }

        // Cek role: harus "kepsek"
        if (strcasecmp($session->get('role'), 'kepsek') !== 0) {
            $session->setFlashdata('pesan', 'Akses ditolak: Anda bukan kepala sekolah!');
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('X-Filtered-By', 'KepsekFilter');
    }
}
