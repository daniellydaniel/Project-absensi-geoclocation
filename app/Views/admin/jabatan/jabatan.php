<?php
/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <a href="<?= base_url('admin/jabatan/create') ?>" class="btn btn-primary ms-4">
        <i class="lni lni-circle-plus me-2"></i>Tambah Data
    </a>
</div>

<!-- Bungkus tabel dalam div agar sejajar dengan button -->
<div class="container mt-3 ps-3">
    <table class="table table-striped" id="datatables">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Jabatan</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php $no = 1; foreach($jabatan as $jab) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $jab['jabatan'] ?></td>
                    <td>
                        <a href="<?= base_url('admin/jabatan/edit/' . $jab['id']) ?>" class="badge bg-primary">Edit Data</a>
                        <a href="<?= base_url('admin/jabatan/delete/' . $jab['id']) ?>" class="badge bg-danger tombol-hapus">Hapus Data</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
