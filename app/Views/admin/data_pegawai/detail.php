<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 ms-3">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($pegawai)) : ?>
                        <img style="border-radius: 10px;" width="200px"
                            src="<?= !empty($pegawai['foto']) ? base_url('profile/' . $pegawai['foto']) : '/path/to/default/image.jpg' ?>"
                            alt="">

                        <table class="table">
                            <tr>
                                <td>NIP</td>
                                <td>:</td>
                                <td><?= isset($pegawai['nip']) ? $pegawai['nip'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td><?= isset($pegawai['nama']) ? $pegawai['nama'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td>:</td>
                                <td><?= isset($pegawai['username']) ? $pegawai['username'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td><?= isset($pegawai['jenis_kelamin']) ? $pegawai['jenis_kelamin'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td><?= isset($pegawai['alamat']) ? $pegawai['alamat'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>No. Handphone</td>
                                <td>:</td>
                                <td><?= isset($pegawai['no_handphone']) ? $pegawai['no_handphone'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td><?= isset($pegawai['jabatan']) ? $pegawai['jabatan'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Lokasi Absensi</td>
                                <td>:</td>
                                <td><?= isset($pegawai['nama_lokasi']) ? $pegawai['nama_lokasi'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td><?= isset($pegawai['status']) ? $pegawai['status'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Role</td>
                                <td>:</td>
                                <td><?= isset($pegawai['role']) ? $pegawai['role'] : '-' ?></td>
                            </tr>
                        </table>
                    <?php else : ?>
                        <p>Data pegawai tidak ditemukan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>