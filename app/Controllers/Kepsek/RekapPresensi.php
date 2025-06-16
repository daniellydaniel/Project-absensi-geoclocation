<?php

namespace App\Controllers\Kepsek;

use App\Controllers\BaseController;
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
        $filter_tanggal = $this->request->getVar('filter_tanggal');
        $rekap_harian = $filter_tanggal
            ? $this->rekapPresensiModel->rekap_harian_filter($filter_tanggal)
            : $this->rekapPresensiModel->rekap_harian();

        $data = [
            'title' => 'Rekap Harian',
            'tanggal' => $filter_tanggal,
            'rekap_harian' => $rekap_harian
        ];

        return view('kepsek/rekap_presensi/rekap_harian', $data);
    }

    public function rekap_bulanan()
    {
        $filter_bulan = $this->request->getVar('filter_bulan');
        $filter_tahun = $this->request->getVar('filter_tahun');

        $rekap_bulanan = ($filter_bulan && $filter_tahun)
            ? $this->rekapPresensiModel->rekap_bulanan_filter($filter_bulan, $filter_tahun)
            : $this->rekapPresensiModel->rekap_bulanan();

        $pegawai = $this->pegawaiModel->findAll();

        $data = [
            'title' => 'Rekap Bulanan',
            'bulan' => $filter_bulan,
            'tahun' => $filter_tahun,
            'rekap_bulanan' => $rekap_bulanan,
            'pegawai' => $pegawai
        ];

        return view('kepsek/rekap_presensi/rekap_bulanan', $data);
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
        exit();
    }

    public function export_pdf()
    {
        $tanggal = $this->request->getVar('tanggal') ?? date('Y-m-d');
        $rekap_harian = $this->rekapPresensiModel->rekap_harian_filter($tanggal);

        $data = [
            'rekap_harian' => $rekap_harian,
            'tanggal' => $tanggal,
        ];

        $html = view('kepsek/rekap_presensi/pdf_template_harian', $data);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->render();

        $pdf_output = $dompdf->output();
        $file_path = WRITEPATH . 'uploads/rekap_harian_' . $tanggal . '.pdf';
        file_put_contents($file_path, $pdf_output);

        return $this->response->download($file_path, null);
    }
}
