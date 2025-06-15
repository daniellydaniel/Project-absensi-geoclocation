<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h5 class="mb-3">Tambah Lokasi Absensi</h5>

                <form method="POST" action="<?= base_url('admin/lokasi_presensi/update/'.$lokasi_presensi['id']) ?>">

                    <div class="mb-3">
                        <label class="form-label">Nama Lokasi</label>
                        <input type="text" value="<?= $lokasi_presensi['nama_lokasi'] ?>" name="nama_lokasi" class="form-control
                        <?= isset($validation) && $validation->hasError('nama_lokasi') ? 'is-invalid' : '' ?>"
                            placeholder="Nama Lokasi" value="<?= old('nama_lokasi') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('nama_lokasi') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Lokasi</label>
                        <textarea name="alamat_lokasi" class="form-control <?= isset($validation) &&
                        $validation->hasError('alamat_lokasi') ? 'is-invalid' : '' ?>" cols="30" rows="5"
                            placeholder="Alamat Lokasi"><?= $lokasi_presensi['alamat_lokasi'] ?></textarea>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('alamat_lokasi') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe Lokasi</label>
                        <input type="text" name="tipe_lokasi" value="<?= $lokasi_presensi['tipe_lokasi'] ?>"
                            class="form-control <?= isset($validation) && $validation->hasError('tipe_lokasi') ? 'is-invalid' : '' ?>"
                            placeholder="Tipe Lokasi">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('tipe_lokasi') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude"
                            class="form-control <?= isset($validation) && $validation->hasError('latitude') ? 'is-invalid' : '' ?>"
                            placeholder="Latitude"
                            value="<?= isset($lokasi_presensi['latitude']) ? $lokasi_presensi['latitude'] : old('latitude') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('latitude') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude"
                            class="form-control <?= isset($validation) && $validation->hasError('longitude') ? 'is-invalid' : '' ?>"
                            placeholder="Longitude"
                            value="<?= isset($lokasi_presensi['longitude']) ? $lokasi_presensi['longitude'] : old('longitude') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('longitude') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Radius (Meter)</label>
                        <input type="number" name="radius"
                            class="form-control <?= isset($validation) && $validation->hasError('radius') ? 'is-invalid' : '' ?>"
                            placeholder="Radius dalam meter"
                            value="<?= isset($lokasi_presensi['radius']) ? $lokasi_presensi['radius'] : old('radius') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('radius') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Zona Waktu</label>
                        <div class="input-group">
                            <select name="zona_waktu" class="form-control <?= isset($validation) && $validation->hasError('zona_waktu') ? 'is-invalid' : '' ?>">
                                <option value="">--Pilih Zona Waktu--</option>
                                <option value="WIB" <?= isset($lokasi_presensi['zona_waktu']) && $lokasi_presensi['zona_waktu'] == 'WIB' ? 'selected' : '' ?>>WIB</option>
                                <option value="WITA" <?= isset($lokasi_presensi['zona_waktu']) && $lokasi_presensi['zona_waktu'] == 'WITA' ? 'selected' : '' ?>>WITA</option>
                                <option value="WIT" <?= isset($lokasi_presensi['zona_waktu']) && $lokasi_presensi['zona_waktu'] == 'WIT' ? 'selected' : '' ?>>WIT</option>
                            </select>
                            <span class="input-group-text">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('zona_waktu') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="form-control <?= isset($validation) && $validation->hasError('jam_masuk') ? 'is-invalid' : '' ?>"
                            value="<?= isset($lokasi_presensi['jam_masuk']) ? $lokasi_presensi['jam_masuk'] : old('jam_masuk') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('jam_masuk') : '' ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Pulang</label>
                        <input type="time" name="jam_pulang" class="form-control <?= isset($validation) && $validation->hasError('jam_pulang') ? 'is-invalid' : '' ?>"
                            value="<?= isset($lokasi_presensi['jam_pulang']) ? $lokasi_presensi['jam_pulang'] : old('jam_pulang') ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('jam_pulang') : '' ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>