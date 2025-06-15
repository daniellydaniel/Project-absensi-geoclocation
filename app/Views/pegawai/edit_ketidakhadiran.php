<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('pegawai/layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6"> <!-- Buat setengah lebar -->
            <div class="card p-4 shadow-sm">
                <h5 class="mb-3">Edit Ketidakhadiran</h5>
                <form action="<?= base_url('pegawai/ketidakhadiran/update/'.$ketidakhadiran['id']) ?>" method="post"
                    enctype="multipart/form-data">

                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" value="<?= $ketidakhadiran['nip'] ?>" name="nip" class="form-control <?= isset($validation) && $validation->hasError('nip') ?
                                                                                                                    'is-invalid' : '' ?>"
                            placeholder="NIP" value="<?= set_value('nip') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('nip') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" value="<?= $ketidakhadiran['nama'] ?>" name="nama" class="form-control <?= isset($validation) && $validation->hasError('nama') ?
                        'is-invalid' : '' ?>"
                            placeholder="Nama" value="<?= set_value('nama') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('nama') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <div class="input-group">
                            <select name="keterangan" class="form-control <?= isset($validation) && $validation->hasError('keterangan') ? 'is-invalid' : '' ?>">
                                <option value="">--Pilih Keterangan--</option>
                                <option value="Izin" <?= ($ketidakhadiran['keterangan'] == 'Izin') ? 'selected' : '' ?>>Izin</option>
                                <option value="Sakit" <?= ($ketidakhadiran['keterangan'] == 'Sakit') ? 'selected' : '' ?>>Sakit</option>
                            </select>

                            <span class="input-group-text">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('keterangan') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Ketidakhadiran</label>
                        <input type="date" name="tanggal" class="form-control <?= isset($validation) && $validation->hasError('tanggal') ?
                        'is-invalid' : '' ?>" value="<?= $ketidakhadiran['tanggal']?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('tanggal') : '' ?>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control <?= isset($validation) && $validation->hasError('deskripsi') ? 'is-invalid' : '' ?>"
                            cols="30" rows="5" placeholder="Deskripsi"><?= $ketidakhadiran['deskripsi'] ?></textarea>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('deskripsi') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File</label>
                        <input type="hidden" name="file_lama" value="<?= $ketidakhadiran['file'] ?>">
                        <input type="file" name="file" class="form-control <?= isset($validation) && $validation->hasError('file') ? 'is-invalid' : '' ?>"
                            value="<?= set_value('file') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('file') : '' ?>
                        </div>
                    </div>
            </div>

            <button type="submit" class="btn btn-primary">Edit</button>
            </form>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>