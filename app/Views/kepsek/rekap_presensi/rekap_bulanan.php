<?php

/** @var \CodeIgniter\View\View $this */ ?>
<?= $this->extend('kepsek/layout.php') ?>
<?= $this->section('content') ?>

<div class="container my-4 px-2 px-md-4">
    <a href="<?= base_url('kepsek/home') ?>" class="btn btn-secondary btn-sm mb-3">
        🔙 Kembali
    </a>


    <form class="row g-2 align-items-center mb-3" method="get" action="<?= base_url('kepsek/rekap_bulanan') ?>">
        <div class="col-auto">
            <select name="filter_bulan" class="form-select form-select-sm fw-semibold">
                <option value="" class="fw-semibold">📅 Pilih Bulan</option>
                <option value="01" <?= ($bulan == '01') ? 'selected' : '' ?>>Januari</option>
                <option value="02" <?= ($bulan == '02') ? 'selected' : '' ?>>Februari</option>
                <option value="03" <?= ($bulan == '03') ? 'selected' : '' ?>>Maret</option>
                <option value="04" <?= ($bulan == '04') ? 'selected' : '' ?>>April</option>
                <option value="05" <?= ($bulan == '05') ? 'selected' : '' ?>>Mei</option>
                <option value="06" <?= ($bulan == '06') ? 'selected' : '' ?>>Juni</option>
                <option value="07" <?= ($bulan == '07') ? 'selected' : '' ?>>Juli</option>
                <option value="08" <?= ($bulan == '08') ? 'selected' : '' ?>>Agustus</option>
                <option value="09" <?= ($bulan == '09') ? 'selected' : '' ?>>September</option>
                <option value="10" <?= ($bulan == '10') ? 'selected' : '' ?>>Oktober</option>
                <option value="11" <?= ($bulan == '11') ? 'selected' : '' ?>>November</option>
                <option value="12" <?= ($bulan == '12') ? 'selected' : '' ?>>Desember</option>
            </select>
        </div>

        <div class="col-auto">
            <select name="filter_tahun" class="form-select form-select-sm fw-semibold">
                <option value="" class="fw-semibold">📆 Pilih Tahun</option>
                <option value="2025" <?= ($tahun == '2025') ? 'selected' : '' ?>>2025</option>
                <option value="2026" <?= ($tahun == '2026') ? 'selected' : '' ?>>2026</option>
                <option value="2027" <?= ($tahun == '2027') ? 'selected' : '' ?>>2027</option>
            </select>
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm px-3 py-1 fw-semibold">
                🔍 Tampilkan
            </button>
        </div>
    </form>

    <div class="bg-light p-2 rounded shadow-sm mb-3 border-start border-3 border-primary" style="font-size: 0.9rem;">
        <h5 class="mb-1 text-primary fw-semibold">📋 Rekap Bulanan Guru</h5>
        <small class="text-muted">Menampilkan data :
            <?= ($bulan) ? date('F Y', strtotime($tahun . '-' . $bulan)) : date('F Y') ?>
        </small>
    </div>

    <?php $tanggal = ($bulan && $tahun) ? $tahun . '-' . $bulan : date('Y-m'); ?>
    <div class="mb-3 d-flex flex-wrap gap-2">
        <a href="<?= base_url('kepsek/rekap/export-excel?tanggal=' . $tanggal) ?>" class="btn btn-success btn-sm">
            📥 Export Excel
        </a>
        <a href="<?= base_url('kepsek/rekappresensi/export_pdf_all?filter_bulan=' . $bulan . '&filter_tahun=' . $tahun) ?>"
            class="btn btn-danger" target="_blank">📥 Export PDF</a>

    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center table-sm">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Total Jam Kerja</th>
                    <th>Total Keterlambatan</th>
                </tr>
            </thead>
            <tbody style="background-color: rgba(255, 255, 153, 0.5);">
                <?php $no = 1; ?>
                <?php foreach ($rekap_bulanan as $data): ?>
                    <?php
                    // Set default value
                    $jam_masuk = $data['jam_masuk'] ?? '-';
                    $jam_keluar = $data['jam_keluar'] ?? '-';
                    $tanggal_masuk = $data['tanggal_masuk'] ?? '-';
                    $tanggal_keluar = $data['tanggal_keluar'] ?? '-';
                    $total_jam_kerja = '0 Jam 0 Menit';
                    $total_terlambat = 'Tepat Waktu';

                    // Calculate total jam kerja
                    if (!empty($jam_masuk) && !empty($jam_keluar)) {
                        // Convert string time to timestamp
                        $masuk_time = strtotime($tanggal_masuk . ' ' . $jam_masuk);
                        $keluar_time = strtotime($tanggal_keluar . ' ' . $jam_keluar);

                        // If keluar time is before masuk time, add 1 day to keluar time
                        if ($keluar_time < $masuk_time) {
                            $keluar_time = strtotime('+1 day', $keluar_time);
                        }

                        // Calculate difference in seconds
                        $selisih_detik = $keluar_time - $masuk_time;
                        $total_jam = floor($selisih_detik / 3600);
                        $total_menit = floor(($selisih_detik % 3600) / 60);
                        $total_jam_kerja = $total_jam . ' Jam ' . $total_menit . ' Menit';
                    }

                    // Calculate keterlambatan
                    if (!empty($data['jam_masuk_sekolah'])) {
                        $jam_masuk_sekolah = $data['jam_masuk_sekolah'];
                        $jam_masuk_real = strtotime($tanggal_masuk . ' ' . $jam_masuk);
                        $jam_masuk_sekolah_time = strtotime($tanggal_masuk . ' ' . $jam_masuk_sekolah);

                        if ($jam_masuk_real > $jam_masuk_sekolah_time) {
                            // Calculate the late time
                            $terlambat_detik = $jam_masuk_real - $jam_masuk_sekolah_time;
                            $jam_terlambat = floor($terlambat_detik / 3600);
                            $menit_terlambat = floor(($terlambat_detik % 3600) / 60);
                            $total_terlambat = $jam_terlambat . ' Jam ' . $menit_terlambat . ' Menit';
                        }
                    }
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data['nip'] ?? '-' ?></td>
                        <td><?= $data['nama'] ?? '-' ?></td>
                        <td><?= !empty($tanggal_masuk) ? date('d F Y', strtotime($tanggal_masuk)) : '-' ?></td>
                        <td><?= $jam_masuk ?></td>
                        <td><?= $jam_keluar ?></td>
                        <td><?= $total_jam_kerja ?></td>
                        <td><?= $total_terlambat ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($rekap_bulanan)) : ?>
                    <tr>
                        <td colspan="8">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>