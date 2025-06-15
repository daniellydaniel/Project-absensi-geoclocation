<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('admin/layout.php') ?>

<?= $this->section('content') ?>

<div style="padding-right: 50px;">
    <div class="container mt-4 ms-4">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

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
                    <th>Aksi</th> <!-- tambahin ini -->
                </tr>
            </thead>

            <?php if ($ketidakhadiran) :
            ?>
                <tbody>
                    <?php $no = 1;
                    foreach ($ketidakhadiran as $row) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['tanggal'] ?? '-' ?></td>
                            <td><?= $row['nip'] ?? '-' ?></td>
                            <td><?= $row['nama'] ?? '-' ?></td>
                            <td><?= $row['keterangan'] ?? '-' ?></td>
                            <td><?= $row['deskripsi'] ?? '-' ?></td>
                            <td>
                                <?php if (!empty($row['file'])) : ?>
                                    <a class="badge bg-primary" href="<?= base_url('file_ketidakhadiran/' . $row['file']) ?>" target="_blank">Download</a>
                                <?php else : ?>
                                    <span class="text-muted">Tidak ada file</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($row['status']) && $row['status'] == 'Menunggu') : ?>
                                    <span class="text-danger"><?= $row['status'] ?></span>
                                <?php elseif (isset($row['status']) && $row['status'] == 'Ditolak') : ?>
                                    <span class="text-danger"><?= $row['status'] ?></span>
                                <?php elseif (isset($row['status'])) : ?>
                                    <span class="text-success"><?= $row['status'] ?></span>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($row['id'])) : ?>
                                    <a class="badge bg-success" href="<?= base_url('admin/approved_ketidakhadiran/' . $row['id']) ?>">Terima</a>
                                    <a class="badge bg-danger" href="<?= base_url('admin/rejected_ketidakhadiran/' . $row['id']) ?>" onclick="return confirm('Yakin ingin menolak pengajuan ini?')">Tolak</a>
                                <?php else : ?>
                                    <span class="text-muted">ID tidak ditemukan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            <?php else : ?>

                <tbody>
                    <tr>
                        <td colspan="9"> Data masih kosong</td>
                    </tr>
                </tbody>
            <?php endif; ?>
        </table>
    </div>
</div>

<script>
    setTimeout(function() {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');
        }
    }, 3000); // 3 detik
</script>


<?= $this->endSection() ?>