<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('pegawai/layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6"> <!-- Buat setengah lebar -->
            <div class="card p-4 shadow-sm">
                <h5 class="mb-3">Tambah Ketidakhadiran</h5>
                <form action="<?= base_url('pegawai/ketidakhadiran/store') ?>" method="post"
                enctype="multipart/form-data">

                    <?= csrf_field() ?>
                    <input type="hidden" value="<?= session()->get('id_pegawai') ?>" name="id_pegawai" >
                    
                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" value="<?= session()->get('nip') ?>" name="nip" class="form-control <?= isset($validation) && $validation->hasError('nip') ?
                        'is-invalid' : '' ?>"
                            placeholder="NIP" value="<?= set_value('nip') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('nip') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" value="<?= session()->get('nama') ?>" name="nama" class="form-control <?= isset($validation) && $validation->hasError('nama') ?
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
                                <option value="Izin" <?= set_select('keterangan', 'izin') ?>>Izin</option>
                                <option value="Sakit" <?= set_select('keterangan', 'sakit') ?>>Sakit</option>
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
                        'is-invalid' : '' ?>" value="<?= set_value('tanggal') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('tanggal') : '' ?>
                        </div>
                    </div>
                    
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control <?= isset($validation) && $validation->hasError('deskripsi') ? 'is-invalid' : '' ?>"
                            cols="30" rows="5" placeholder="Deskripsi"><?= set_value('deskripsi') ?></textarea>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('deskripsi') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File</label>
                        <input type="file" name="file" class="form-control <?= isset($validation) && $validation->hasError('file') ? 'is-invalid' : '' ?>"
                        value="<?= set_value('file') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('file') : '' ?>
                        </div>
                    </div>
            </div>

            <button type="submit" class="btn btn-primary">Ajukan</button>
            </form>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>