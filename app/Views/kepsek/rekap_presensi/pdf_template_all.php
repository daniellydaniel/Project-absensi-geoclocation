<!-- app/Views/admin/rekap_presensi/pdf_template_all.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Pegawai</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 11px;
            padding: 20px;
        }
        h1, h2, h3 {
            text-align: center;
            margin: 0;
        }
        h1 { font-size: 20px; margin-bottom: 5px; }
        h2 { font-size: 16px; margin-bottom: 3px; }
        h3 { font-size: 14px; margin-bottom: 20px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }

        thead {
            background-color: #f0f0f0;
        }

        tfoot td {
            border: none;
            text-align: right;
            padding-top: 30px;
        }

        .signature {
            text-align: right;
            margin-top: 60px;
        }

        .footer-note {
            font-size: 10px;
            text-align: center;
            margin-top: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <h2>SD Bethany School Indonesia Papua</h2>
    <h1>Rekapitulasi Absensi Pegawai</h1>
    <h3>Bulan: <?= $filter_bulan ?>, Tahun: <?= $filter_tahun ?></h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Total Jam Kerja</th>
                <th>Keterlambatan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($rekap_bulanan as $data): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $data['nip'] ?? '-' ?></td>
                    <td><?= $data['nama'] ?? '-' ?></td>
                    <td><?= $data['tanggal_masuk'] ?? '-' ?></td>
                    <td><?= $data['jam_masuk'] ?? '-' ?></td>
                    <td><?= $data['jam_keluar'] ?? '-' ?></td>
                    <td><?= $data['total_jam_kerja'] ?? '-' ?></td>
                    <td><?= $data['total_keterlambatan'] ?? '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="signature">
        <p>Jayapura, <?= date('d M Y') ?></p>
        <br><br><br>
        <p><strong>Kepala Sekolah</strong></p>
        <p style="margin-top: 60px;"><u>(Nama Kepala Sekolah)</u></p>
    </div>

    <div class="footer-note">
        * Laporan ini dihasilkan otomatis oleh sistem aplikasi presensi SD Bethany School
    </div>
</body>
</html>
