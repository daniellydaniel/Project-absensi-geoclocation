<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h5 class="mb-3">Tambah Pegawai</h5>
                <form action="<?= base_url('admin/data_pegawai/store') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" class="form-control <?= isset($validation)  && $validation->hasError('nip') ? 'is-invalid' : '' ?>"
                            placeholder="NIP" value="<?= set_value('nip') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('nip') : '' ?>
                        </div>
                    </div> <!-- Tutup div NIP -->

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control <?= isset($validation)  && $validation->hasError('nama') ? 'is-invalid' : '' ?>"
                            placeholder="Nama" value="<?= set_value('nama') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('nama') : '' ?>
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <div class="input-group">
                            <select name="jenis_kelamin" class="form-control <?= isset($validation) && $validation->hasError('jenis_kelamin') ? 'is-invalid' : '' ?>">
                                <option value="">--Pilih Jenis Kelamin--</option>
                                <option value="Laki-Laki" <?= set_select('jenis_kelamin', 'Laki-Laki') ?>>Laki-Laki</option>
                                <option value="Perempuan" <?= set_select('jenis_kelamin', 'Perempuan') ?>>Perempuan</option>
                            </select>
                            <span class="input-group-text"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('jenis_kelamin') : '' ?>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control <?= isset($validation) && $validation->hasError('alamat') ? 'is-invalid' : '' ?>" cols="30" rows="5" placeholder="Alamat"><?= set_value('alamat') ?></textarea>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('alamat') : '' ?>
                        </div>
                    </div>

                    <!-- No Handphone -->
                    <div class="mb-3">
                        <label class="form-label">No.Handphone</label>
                        <input type="text" name="no_handphone" class="form-control <?= isset($validation) && $validation->hasError('no_handphone') ? 'is-invalid' : '' ?>" placeholder="No. Handphone" value="<?= set_value('no_handphone') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('no_handphone') : '' ?>
                        </div>
                    </div>

                    <!-- Jabatan -->
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <div class="input-group">
                            <select name="jabatan" class="form-control <?= isset($validation) && $validation->hasError('jabatan') ? 'is-invalid' : '' ?>">
                                <option value="">--Pilih Jabatan--</option>
                                <?php foreach ($jabatan as $jab) : ?>
                                    <option value="<?= $jab['jabatan'] ?>" <?= set_select('jabatan', $jab['jabatan']) ?>><?= $jab['jabatan'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <span class="input-group-text"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('jabatan') : '' ?>
                        </div>
                    </div>

                    <!-- Lokasi Presensi -->
                    <div class="mb-3">
                        <label class="form-label">Lokasi Presensi</label>
                        <div class="input-group">
                            <select name="lokasi_presensi" class="form-control <?= isset($validation) && $validation->hasError('lokasi_presensi') ? 'is-invalid' : '' ?>">
                                <option value="">--Pilih Lokasi Presensi--</option>
                                <?php foreach ($lokasi_presensi as $lok) : ?>
                                    <option value="<?= $lok['id'] ?>" <?= set_select('lokasi_presensi', $lok['id']) ?>><?= $lok['nama_lokasi'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="input-group-text"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('lokasi_presensi') : '' ?>
                        </div>
                    </div>

                    <!-- Foto -->
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control <?= isset($validation) && $validation->hasError('foto') ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('foto') : '' ?>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?= isset($validation) && $validation->hasError('username') ? 'is-invalid' : '' ?>" placeholder="Username" value="<?= set_value('username') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('username') : '' ?>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" placeholder="Password" value="<?= set_value('password') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('password') : '' ?>
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="konfirmasi_password" class="form-control <?= isset($validation) && $validation->hasError('konfirmasi_password') ? 'is-invalid' : '' ?>" placeholder="Konfirmasi Password" value="<?= set_value('konfirmasi_password') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('konfirmasi_password') : '' ?>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <div class="input-group">
                            <select name="role" class="form-control <?= isset($validation) && $validation->hasError('role') ? 'is-invalid' : '' ?>">
                                <option value="">--Pilih Role--</option>
                                <option value="Admin" <?= set_select('role', 'Admin') ?>>Admin</option>
                                <option value="Pegawai" <?= set_select('role', 'Pegawai') ?>>Pegawai</option>
                                <option value="Kepala" <?= set_select('role', 'Kepala') ?>>Kepala</option>
                            </select>
                            <span class="input-group-text"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('role') : '' ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>

                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>