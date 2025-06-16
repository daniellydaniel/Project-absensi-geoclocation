<?php

/** @var \CodeIgniter\View\View $this */
?>

<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 px-2">
        <a href="<?= base_url('admin/data_pegawai/create') ?>" class="btn btn-primary">
            <i class="lni lni-circle-plus me-2"></i>Tambah Data
        </a>
    </div>
</div>


<div class="container-fluid mt-3 px-3">
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="datatables">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($pegawai as $peg) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $peg['nip'] ?? '-' ?></td>
                        <td><?= $peg['nama'] ?? '-' ?></td>
                        <td><?= $peg['jabatan'] ?? '-' ?></td>
                        <td>
                            <a href="<?= base_url('admin/data_pegawai/detail/' . $peg['id']) ?>" class="badge bg-primary">Detail</a>
                            <a href="<?= base_url('admin/data_pegawai/edit/' . $peg['id']) ?>" class="badge bg-success">Edit</a>
                            <a href="<?= base_url('admin/data_pegawai/delete/' . $peg['id']) ?>" class="badge bg-danger tombol-hapus">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<?= $this->endSection() ?>