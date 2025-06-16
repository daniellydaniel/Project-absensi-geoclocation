<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid mt-4 px-3">
    <a href="<?= base_url('admin/lokasi_presensi/create') ?>" class="btn btn-primary mb-3">
        <i class="lni lni-circle-plus me-2"></i>Tambah Data
    </a>

    <!-- Tambahkan overflow auto -->
    <div style="overflow-x: auto;">
        <table class="table table-bordered table-striped" id="datatables" style="min-width: 900px; white-space: nowrap;">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Lokasi</th>
                    <th>Alamat Lokasi</th>
                    <th>Tipe Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($lokasi_presensi as $lok) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $lok['nama_lokasi'] ?></td>
                        <td><?= $lok['alamat_lokasi'] ?></td>
                        <td><?= $lok['tipe_lokasi'] ?></td>
                        <td>
                            <a href="<?= base_url('admin/lokasi_presensi/detail/' . $lok['id']) ?>" class="badge bg-primary">Detail</a>
                            <a href="<?= base_url('admin/lokasi_presensi/edit/' . $lok['id']) ?>" class="badge bg-success">Edit</a>
                            <a href="<?= base_url('admin/lokasi_presensi/delete/' . $lok['id']) ?>" class="badge bg-danger tombol-hapus">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#datatables').DataTable({
            responsive: false, // NON-AKTIFKAN fitur responsive bawaan DataTables
            scrollX: true // AKTIFKAN horizontal scroll
        });
    });
</script>
<?= $this->endSection() ?>