<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <a href="<?= base_url('admin/data_pegawai/create') ?>" class="btn btn-primary ms-4">
        <i class="lni lni-circle-plus me-2"></i>Tambah Data
    </a>
</div>

<!-- Bungkus tabel dalam div agar sejajar dengan button -->
<div class="container mt-3 ps-3">
    <table class="table table-striped" id="datatables">
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <!-- <th>Lokasi Absensi</th> -->
                <th>Aksi</th>
        </thead>

        <tbody>
            <?php $no = 1;
            foreach ($pegawai as $peg) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= isset($peg['nip']) ? $peg['nip'] : '-' ?></td>
                    <td><?= isset($peg['nama']) ? $peg['nama'] : '-' ?></td>
                    <td><?= isset($peg['jabatan']) ? $peg['jabatan'] : '-' ?></td>
                    <!-- <td><?= isset($peg['lokasi_presensi']) ? $peg['lokasi_presensi'] : '-' ?></td> -->
                    <td>
                        <a href="<?= base_url('admin/data_pegawai/detail/' . $peg['id']) ?>" class="badge bg-primary">Detail</a>
                        <a href="<?= base_url('admin/data_pegawai/edit/' . $peg['id']) ?>" class="badge bg-primary">Edit</a>
                        <a href="<?= base_url('admin/data_pegawai/delete/' . $peg['id']) ?>" class="badge bg-danger tombol-hapus">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>