<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('pegawai/layout.php') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <a href="<?= base_url('pegawai/ketidakhadiran/create') ?>" class="btn btn-primary ms-4">
        <i class="lni lni-circle-plus me-2"></i>Ajukan
    </a>
</div>

<div style="padding-right: 50px;">
    <div class="container mt-4 ms-4">
        <table class="table table-striped table-bordered text-center" id="datatables">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($ketidakhadiran) : ?>
                    <?php $no = 1;
                    foreach ($ketidakhadiran as $item) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= isset($item['tanggal']) ? $item['tanggal'] : '-' ?></td>
                            <td><?= isset($item['nip']) ? $item['nip'] : '-' ?></td>
                            <td><?= isset($item['nama']) ? $item['nama'] : '-' ?></td>
                            <td><?= isset($item['keterangan']) ? $item['keterangan'] : '-' ?></td>
                            <td><?= isset($item['deskripsi']) ? $item['deskripsi'] : '-' ?></td>
                            <td>
                                <a class="badge bg-primary" href="<?= base_url('file_ketidakhadiran/' . $item['file']) ?>">Lihat</a>
                            </td>
                            <td><?= isset($item['status']) ? $item['status'] : '-' ?></td>
                            <td>
                                <a href="<?= base_url('pegawai/ketidakhadiran/edit/' . $item['id']) ?>" class="badge bg-primary">Edit</a>
                                <a href="<?= base_url('pegawai/ketidakhadiran/delete/' . $item['id']) ?>" class="badge bg-danger tombol-hapus">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9">Data masih kosong</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>