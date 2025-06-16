    <?php

    use CodeIgniter\Router\RouteCollection;

    $routes->get('/', 'Login::index');
    $routes->get('login', 'Login::index');
    $routes->post('login', 'Login::login_action');
    $routes->get('logout', 'Login::logout');

    $routes->get('dashboard', 'Dashboard::index');
    $routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('notifikasi', 'Notifikasi::index');
        $routes->get('notifikasi/tandai_sudah_dibaca/(:num)', 'Notifikasi::tandai_sudah_dibaca/$1');
        $routes->get('notifikasi/clear', 'Notifikasi::clear');
    });

    $routes->get('admin/home', 'Admin\Home::index', ['filter' => 'AdminFilter']);
    $routes->get('admin/home/getLokasiPegawai', 'Admin\Home::getLokasiPegawai', ['filter' => 'AdminFilter']);
    $routes->get('admin/jabatan', 'Admin\Jabatan::index', ['filter' => 'AdminFilter']);
    $routes->get('admin/jabatan/create', 'Admin\Jabatan::create', ['filter' => 'AdminFilter']);
    $routes->post('admin/jabatan/store', 'Admin\Jabatan::store', ['filter' => 'AdminFilter']);
    $routes->get('admin/jabatan/edit/(:segment)', 'Admin\Jabatan::edit/$1', ['filter' => 'AdminFilter']);
    $routes->post('admin/jabatan/update/(:segment)', 'Admin\Jabatan::update/$1', ['filter' => 'AdminFilter']);
    $routes->get('admin/jabatan/delete/(:segment)', 'Admin\Jabatan::delete/$1', ['filter' => 'AdminFilter']);

    $routes->get('admin/lokasi_presensi', 'Admin\LokasiPresensi::index', ['filter' => 'AdminFilter']);
    $routes->get('admin/lokasi_presensi/create', 'Admin\LokasiPresensi::create', ['filter' => 'AdminFilter']);
    $routes->post('admin/lokasi_presensi/store', 'Admin\LokasiPresensi::store', ['filter' => 'AdminFilter']);
    $routes->get('admin/lokasi_presensi/edit/(:segment)', 'Admin\LokasiPresensi::edit/$1', ['filter' => 'AdminFilter']);
    $routes->post('admin/lokasi_presensi/update/(:segment)', 'Admin\LokasiPresensi::update/$1', ['filter' => 'AdminFilter']);
    $routes->get('admin/lokasi_presensi/delete/(:segment)', 'Admin\LokasiPresensi::delete/$1', ['filter' => 'AdminFilter']);
    $routes->get('admin/lokasi_presensi/detail/(:segment)', 'Admin\LokasiPresensi::detail/$1', ['filter' => 'AdminFilter']);

    $routes->get('admin/data_pegawai', 'Admin\DataPegawai::index', ['filter' => 'AdminFilter']);
    $routes->get('admin/data_pegawai/create', 'Admin\DataPegawai::create', ['filter' => 'AdminFilter']);
    $routes->post('admin/data_pegawai/store', 'Admin\DataPegawai::store', ['filter' => 'AdminFilter']);
    $routes->get('admin/data_pegawai/edit/(:segment)', 'Admin\DataPegawai::edit/$1', ['filter' => 'AdminFilter']);
    $routes->post('admin/data_pegawai/update/(:segment)', 'Admin\DataPegawai::update/$1', ['filter' => 'AdminFilter']);
    $routes->get('admin/data_pegawai/delete/(:segment)', 'Admin\DataPegawai::delete/$1', ['filter' => 'AdminFilter']);
    $routes->get('admin/data_pegawai/detail/(:segment)', 'Admin\DataPegawai::detail/$1', ['filter' => 'AdminFilter']);

    $routes->get('admin/rekap_harian', 'Admin\RekapPresensi::rekap_harian', ['filter' => 'AdminFilter']);
    $routes->get('admin/rekap_bulanan', 'Admin\RekapPresensi::rekap_bulanan', ['filter' => 'AdminFilter']);

    $routes->get('admin/ketidakhadiran', 'Admin\Ketidakhadiran::index', ['filter' => 'AdminFilter']);
    $routes->get('admin/approved_ketidakhadiran/(:segment)', 'Admin\Ketidakhadiran::approved/$1', ['filter' => 'AdminFilter']);
    $routes->get('admin/rejected_ketidakhadiran/(:segment)', 'Admin\Ketidakhadiran::rejected/$1', ['filter' => 'AdminFilter']);


    $routes->get('admin/rekap/export-excel', 'Admin\RekapPresensi::export_excel');
    $routes->get('admin/rekappresensi/export_pdf/(:num)', 'Admin\RekapPresensi::export_pdf/$1');
    $routes->get('admin/rekappresensi/export_pdf', 'Admin\RekapPresensi::export_pdf');
    $routes->get('admin/rekap/export-pdf-harian', 'Admin\RekapPresensi::export_pdf');
    $routes->get('admin/rekappresensi/export_pdf_all', 'Admin\RekapPresensi::export_pdf_all');
    $routes->get('admin/rekap/export-pdf', 'Admin\RekapPresensi::export_pdf', ['filter' => 'AdminFilter']);

    $routes->get('pegawai/home', 'Pegawai\Home::index', ['filter' => 'PegawaiFilter']);

    $routes->post('pegawai/presensi_masuk', 'Pegawai\Home::presensi_masuk', ['filter' => 'PegawaiFilter']);
    $routes->get('pegawai/ambil_foto', 'Pegawai\Home::ambil_foto');
    $routes->post('pegawai/presensi_masuk_aksi', 'Pegawai\Home::presensi_masuk_aksi', ['filter' => 'PegawaiFilter']);

    $routes->post('pegawai/presensi_keluar/(:segment)', 'Pegawai\Home::presensi_keluar/$1', ['filter' => 'PegawaiFilter']);
    $routes->post('pegawai/presensi_keluar_aksi/(:segment)', 'Pegawai\Home::presensi_keluar_aksi/$1', ['filter' => 'PegawaiFilter']);
    $routes->get('pegawai/ambil_foto_keluar', 'Pegawai\Home::ambil_foto_keluar');
    $routes->get('/pegawai/lokasi-realtime', 'Pegawai\PresensiRealtime::getLokasiPegawaiRealtime', ['filter' => 'PegawaiFilter']);

    $routes->get('pegawai/rekap_presensi', 'Pegawai\RekapPresensi::index', ['filter' => 'PegawaiFilter']);


    $routes->get('pegawai/ketidakhadiran', 'Pegawai\Ketidakhadiran::index', ['filter' => 'PegawaiFilter']);
    $routes->get('pegawai/ketidakhadiran/create', 'Pegawai\Ketidakhadiran::create', ['filter' => 'PegawaiFilter']);
    $routes->post('pegawai/ketidakhadiran/store', 'Pegawai\Ketidakhadiran::store', ['filter' => 'PegawaiFilter']);
    $routes->get('pegawai/ketidakhadiran/edit/(:segment)', 'Pegawai\Ketidakhadiran::edit/$1', ['filter' => 'PegawaiFilter']);
    $routes->post('pegawai/ketidakhadiran/update/(:segment)', 'Pegawai\Ketidakhadiran::update/$1', ['filter' => 'PegawaiFilter']);
    $routes->get('pegawai/ketidakhadiran/delete/(:segment)', 'Pegawai\Ketidakhadiran::delete/$1', ['filter' => 'PegawaiFilter']);
    $routes->get('pegawai/ketidakhadiran/detail/(:segment)', 'Pegawai\Ketidakhadiran::detail/$1', ['filter' => 'PegawaiFilter']);

    // Rute untuk Kepala Sekolah
    // =========================
    $routes->group('kepsek', ['filter' => 'KepsekFilter'], function ($routes) {
        $routes->get('home', 'Kepsek\Home::index');

        // Panggil method dari controller Home
        $routes->get('rekap_harian', 'Kepsek\Home::rekap_harian');
        $routes->get('rekap_bulanan', 'Kepsek\Home::rekap_bulanan');

        // Export
        $routes->get('rekap/export-excel', 'Kepsek\RekapPresensi::export_excel');
        $routes->get('rekap/export-pdf', 'Kepsek\RekapPresensi::export_pdf');
    });
