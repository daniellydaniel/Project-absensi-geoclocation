<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PresensiModel;
use App\Models\PegawaiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class RekapPresensi extends BaseController
{
    protected $rekapPresensiModel;
    protected $pegawaiModel;

    public function __construct()
    {
        $this->rekapPresensiModel = new PresensiModel();
        $this->pegawaiModel = new PegawaiModel();
    }

    public function rekap_harian()
    {

        $filter_tanggal = $this->request->getVar('filter_tanggal'); // Ambil tanggal filter

        // Ambil data rekap harian berdasarkan tanggal filter
        if ($filter_tanggal) {
            $rekap_harian = $this->rekapPresensiModel->rekap_harian_filter($filter_tanggal);
        } else {
            $rekap_harian = $this->rekapPresensiModel->rekap_harian();
        }

        // Pastikan data dikirim dengan benar ke view
        $data = [
            'title' => 'Rekap Harian',
            'tanggal' => $filter_tanggal,
            'rekap_harian' => $rekap_harian // Data rekap harian dikirim ke view

        ];

        log_message('debug', 'Data Harian: ' . json_encode($rekap_harian));
        return view('admin/rekap_presensi/rekap_harian', $data);
    }

    public function rekap_bulanan()
    {
        $filter_bulan = $this->request->getVar('filter_bulan');
        $filter_tahun = $this->request->getVar('filter_tahun');

        // Buat tanggal default
        if ($filter_bulan && $filter_tahun) {
            $tanggal = $filter_tahun . '-' . $filter_bulan . '-01';
        } else {
            $tanggal = date('Y-m-d');
        }

        // Ambil data rekap bulanan berdasarkan filter
        if ($filter_bulan && $filter_tahun) {
            $rekap_bulanan = $this->rekapPresensiModel->rekap_bulanan_filter($filter_bulan, $filter_tahun);
        } else {
            $rekap_bulanan = $this->rekapPresensiModel->rekap_bulanan();
        }

        $pegawai = $this->pegawaiModel->findAll();  // Ambil semua data pegawai

        $data = [
            'title' => 'Rekap Bulanan',
            'bulan' => $filter_bulan,
            'tahun' => $filter_tahun,
            'rekap_bulanan' => $rekap_bulanan,
            'tanggal' => $tanggal,
            'pegawai' => $pegawai,
        ];

        return view('admin/rekap_presensi/rekap_bulanan', $data);
    }


    public function export_excel()
    {
        $tanggal = $this->request->getGet('tanggal');
        $rekap_harian = $tanggal
            ? $this->rekapPresensiModel->rekap_harian_filter($tanggal)
            : $this->rekapPresensiModel->rekap_harian();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Pegawai');
        $sheet->setCellValue('C1', 'Tanggal');
        $sheet->setCellValue('D1', 'Jam Masuk');
        $sheet->setCellValue('E1', 'Jam Keluar');

        $row = 2;
        $no = 1;
        foreach ($rekap_harian as $data) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $data['nama']);
            $sheet->setCellValue('C' . $row, $data['tanggal_masuk']);
            $sheet->setCellValue('D' . $row, $data['jam_masuk']);
            $sheet->setCellValue('E' . $row, $data['jam_keluar']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Rekap-Harian-' . ($tanggal ?? date('Y-m-d')) . '.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit(); // <-- Tambahan biar gak lanjut eksekusi script di bawahnya
    }
    public function export_pdf_all()
    {
        $filter_bulan = $this->request->getVar('filter_bulan');
        $filter_tahun = $this->request->getVar('filter_tahun');

        // If the filters are not set, default to the current month and year
        if (!$filter_bulan) {
            $filter_bulan = date('m');
        }
        if (!$filter_tahun) {
            $filter_tahun = date('Y');
        }

        if ($filter_bulan && $filter_tahun) {
            $rekap_bulanan = $this->rekapPresensiModel->rekap_bulanan_filter($filter_bulan, $filter_tahun);
        } else {
            $rekap_bulanan = $this->rekapPresensiModel->rekap_bulanan();
        }


        $rekap_copy = [];

        foreach ($rekap_bulanan as $item) {
            $item['total_jam_kerja'] = $this->hitung_jam_kerja($item['jam_masuk'], $item['jam_keluar']);
            $item['total_keterlambatan'] = $this->hitung_keterlambatan($item['jam_masuk']);
            $rekap_copy[] = $item;
        }

        $data = [
            'rekap_bulanan' => $rekap_copy,
            'filter_bulan' => $filter_bulan,
            'filter_tahun' => $filter_tahun,
        ];

        // Load the PDF template view
        $html = view('admin/rekap_presensi/pdf_template_all', $data);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->render();

        $pdf_output = $dompdf->output();
        $file_path = WRITEPATH . 'uploads/rekap_bulanan_' . $filter_bulan . '_' . $filter_tahun . '.pdf';
        file_put_contents($file_path, $pdf_output);

        return $this->response->download($file_path, null);
    }

    public function hitung_jam_kerja($jam_masuk, $jam_keluar)
    {
        try {
            if (empty($jam_masuk) || empty($jam_keluar)) {
                return 'Tidak Ditemukan';
            }

            $masuk = new \DateTime($jam_masuk);
            $keluar = new \DateTime($jam_keluar);
            $interval = $masuk->diff($keluar);

            $jam = (int)$interval->format('%H');
            $menit = (int)$interval->format('%i');
            return "$jam Jam $menit Menit";
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function hitung_keterlambatan($jam_masuk)
    {
        try {
            if (empty($jam_masuk)) {
                return 'Tidak Ditemukan';
            }

            $jam_sekolah = new \DateTime('07:00:00');
            $masuk = new \DateTime($jam_masuk);

            // Validasi jika jam masuk lebih dari jam 20:00 (misal shift malam)
            if ($masuk->format('H') > 20) {
                return 'Shift Malam / Tidak Dihitung';
            }

            if ($masuk <= $jam_sekolah) {
                return 'Tepat Waktu';
            }

            $interval = $jam_sekolah->diff($masuk);
            $jam = (int)$interval->format('%H');
            $menit = (int)$interval->format('%i');
            return "$jam Jam $menit Menit";
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function export_pdf()
    {
        $tanggal = $this->request->getVar('tanggal') ?? date('Y-m-d');


        // Debug: cek tanggal
        log_message('debug', 'Tanggal yang dikirim ke export_pdf: ' . $tanggal);

        $rekap_harian = $this->rekapPresensiModel->rekap_harian_filter($tanggal);

        // Debug: cek isi rekap_harian
        log_message('debug', 'Data Rekap Harian: ' . json_encode($rekap_harian));

        if (empty($rekap_harian)) {
            log_message('error', 'Tidak ada data rekap harian untuk tanggal: ' . $tanggal);
        }

        log_message('debug', 'Jumlah data: ' . count($rekap_harian));
        log_message('debug', 'Data: ' . json_encode($rekap_harian));


        $data = [
            'rekap_harian' => $rekap_harian,
            'tanggal' => $tanggal,
        ];

        $html = view('admin/rekap_presensi/pdf_template_harian', $data);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        $pdf_output = $dompdf->output();
        $file_path = WRITEPATH . 'uploads/rekap_harian_' . $tanggal . '.pdf';
        file_put_contents($file_path, $pdf_output);

        return $this->response->download($file_path, null);
    }
}
