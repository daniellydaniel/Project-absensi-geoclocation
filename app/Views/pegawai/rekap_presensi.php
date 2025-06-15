<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('pegawai/layout.php') ?>

<?= $this->section('content') ?>

<div style="margin-left: 50px; margin-top: 20px; margin-right: 50px;">
    <form class="row g-2 align-items-center mb-3">
        <div class="col-auto">
            <input type="date" class="form-control form-control-sm" name="filter_tanggal">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm px-3 py-1" style="height: 32px;">Tampilkan</button>
        </div>
    </form>

    <div class="bg-light p-2 rounded shadow-sm mb-3 border-start border-3 border-primary" style="font-size: 0.9rem;">
        <h5 class="mb-1 text-primary fw-semibold">📋 Riwayat Bulanan Guru</h5>
    </div>


    <div style="padding-right: 0px">
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center" style="min-width: 1000px;">
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
                    <?php if ($rekap_presensi) : ?>
                        <?php $no = 1; ?>
                        <?php $previous_data = ''; // Variable untuk mencegah duplikasi 
                        ?>
                        <?php foreach ($rekap_presensi as $rekap) : ?>
                            <?php
                            // Pastikan tidak menampilkan data yang sama dua kali
                            if ($rekap['nama'] . $rekap['tanggal_masuk'] == $previous_data) {
                                continue; // Skip jika data sudah pernah diproses
                            }
                            $previous_data = $rekap['nama'] . $rekap['tanggal_masuk'];

                            $jam = 0;
                            $menit = 0;
                            $jam_terlambat = 0;
                            $menit_terlambat = 0;

                            // Validasi tanggal_keluar (tanggal keluar tidak boleh lebih kecil dari tanggal masuk)
                            if (strtotime($rekap['tanggal_keluar']) < strtotime($rekap['tanggal_masuk'])) {
                                continue; // Skip jika tanggal keluar tidak valid
                            }

                            // Pastikan jam masuk dan jam keluar tidak kosong atau invalid
                            if (!empty($rekap['jam_masuk']) && !empty($rekap['jam_keluar']) && $rekap['jam_keluar'] != '00:00:00') {
                                // Menggunakan tanggal masuk dan jam masuk
                                $tanggal_keluar = ($rekap['tanggal_keluar'] != '0000-00-00') ? $rekap['tanggal_keluar'] : $rekap['tanggal_masuk'];

                                // Gabungkan tanggal dan jam menjadi format yang bisa diproses
                                $masuk = strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']);
                                $keluar = strtotime($tanggal_keluar . ' ' . $rekap['jam_keluar']);

                                // Jika keluar lebih kecil atau sama dengan masuk, tambahkan 1 hari ke waktu keluar
                                if ($keluar <= $masuk) {
                                    $keluar = strtotime('+1 day', $keluar);
                                }

                                // Hitung selisih waktu masuk dan keluar dalam detik
                                $selisih_detik = $keluar - $masuk;

                                // Pastikan waktu masuk dan keluar valid (kurang dari 24 jam)
                                if ($selisih_detik >= 0 && $selisih_detik <= 86400) { // Pastikan waktu tidak lebih dari 24 jam
                                    $jam = floor($selisih_detik / 3600); // Menghitung jam
                                    $menit = floor(($selisih_detik % 3600) / 60); // Menghitung menit
                                } else {
                                    // Jika selisih detik lebih dari 24 jam, anggap sebagai kesalahan
                                    $jam = 0;
                                    $menit = 0;
                                }

                                // Hitung keterlambatan
                                $jam_masuk_real = strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']);
                                $jam_masuk_sekolah = strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk_sekolah']);
                                $selisih_terlambat = $jam_masuk_real - $jam_masuk_sekolah;

                                if ($selisih_terlambat > 0) {
                                    $jam_terlambat = floor($selisih_terlambat / 3600); // Jam keterlambatan
                                    $selisih_terlambat -= $jam_terlambat * 3600;
                                    $menit_terlambat = floor($selisih_terlambat / 60); // Menit keterlambatan
                                }
                            }
                            ?>

                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $rekap['nip'] ?></td>
                                <td><?= $rekap['nama'] ?></td>
                                <td><?= date('d F Y', strtotime($rekap['tanggal_masuk'])) ?></td>
                                <td><?= $rekap['jam_masuk'] ?></td>
                                <td><?= $rekap['jam_keluar'] ?></td>
                                <td>
                                    <?php if ($rekap['jam_keluar'] == '00:00:00' || $jam == 0 && $menit == 0) : ?>
                                        0 Jam 0 Menit
                                    <?php else : ?>
                                        <?= $jam . ' Jam ' . $menit . ' Menit' ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($jam_terlambat == 0 && $menit_terlambat == 0) : ?>
                                        <span class="btn btn-success">Tepat Waktu</span>
                                    <?php else : ?>
                                        <?= $jam_terlambat . ' Jam ' . $menit_terlambat . ' Menit' ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8">Data tidak tersedia</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>