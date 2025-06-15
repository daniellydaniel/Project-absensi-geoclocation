<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('admin/layout.php') ?>

<?= $this->section('content') ?>

<div style="margin-left: 50px; margin-top: 20px; margin-right: 50px;">
    <form class="row g-2 align-items-center mb-3">
        <div class="col-auto">
            <input type="date" class="form-control form-control-sm" name="filter_tanggal" value="<?= $tanggal ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm px-3 py-1" style="height: 32px;">Tampilkan</button>
        </div>
    </form>

    <div class="bg-light p-2 rounded shadow-sm mb-3 border-start border-3 border-primary" style="font-size: 0.9rem;">
        <h5 class="mb-1 text-primary fw-semibold">📋 Rekap Harian Guru</h5>
        <small class="text-muted">Menampilkan data :
            <?= $tanggal ? date('d F Y', strtotime($tanggal)) : date('d F Y') ?>
        </small>

    </div>

    <div class="mb-3 d-flex gap-2">
        <?php $tglExport = $tanggal ?? date('Y-m-d'); ?>
        <a href="<?= base_url('admin/rekap/export-excel?tanggal=' . $tglExport) ?>" class="btn btn-success btn-sm">
            📅 Export Excel
        </a>
        <a href="<?= base_url('admin/rekap/export-pdf?tanggal=' . $tglExport) ?>" class="btn btn-danger btn-sm">
            🧾 Export PDF
        </a>

    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center table-sm">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Total Jam Kerja</th>
                    <th>Keterlambatan</th>
                </tr>
            </thead>
            <tbody style="background-color: rgba(255, 255, 153, 0.5);">
                <?php if (!empty($rekap_harian)) : ?>
                    <?php foreach ($rekap_harian as $no => $row) : ?>
                        <tr>
                            <td><?= $no + 1 ?></td>
                            <td><?= $row['nip'] ?? '-' ?></td>
                            <td><?= $row['nama'] ?? '-' ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tanggal_masuk'])) ?></td>
                            <td><?= $row['jam_masuk'] ?? '-' ?></td>
                            <td><?= $row['jam_keluar'] ?? '-' ?></td>
                            <td><?= $row['total_jam_kerja'] ?? '0 Jam 0 Menit' ?></td>
                            <td>
                                <?php if (($row['total_keterlambatan'] ?? '00:00:00') === '00:00:00') : ?>
                                    Tepat Waktu
                                <?php else : ?>
                                    <?= $row['total_keterlambatan'] ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>