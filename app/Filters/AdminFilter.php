<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $currentRoute = trim($request->getUri()->getPath(), '/');

        // 👇 Kalau lo mau skip route tertentu (misalnya untuk AJAX / API)
        $exceptPrefixes = [
            'admin/notifikasi',
        ];

        foreach ($exceptPrefixes as $prefix) {
            if (str_starts_with($currentRoute, $prefix)) {
                log_message('debug', 'AdminFilter skipped for: ' . $currentRoute);
                return;
            }
        }

        // ✅ Logging buat ngecek role & route
        log_message('debug', 'AdminFilter applied to: ' . $currentRoute);
        log_message('debug', 'Session role: ' . $session->get('role'));

        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('pesan', 'Silakan login terlebih dahulu!');
            return redirect()->to('/login');
        }

        if (strcasecmp($session->get('role'), 'admin') !== 0) {
            $session->setFlashdata('pesan', 'Akses ditolak: Anda bukan admin!');
            return redirect()->to('/login'); // Bisa diarahkan ke home/403 juga
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Optional: tambahin header info pas develop
        $response->setHeader('X-Filtered-By', 'AdminFilter');
    }
}
