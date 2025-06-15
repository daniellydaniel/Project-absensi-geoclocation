<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6"> <!-- Buat setengah lebar -->
            <div class="card p-4 shadow-sm">
                <h5 class="mb-3">Tambah Jabatan</h5>

                <form method="POST" action="<?= base_url('admin/jabatan/update/' . $jabatan['id']) ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Jabatan</label>
                        <input type="text" name="jabatan" class="form-control <?= isset($validation) &&
                                                                                    $validation->hasError('jabatan') ? 'is-invalid' : '' ?>" placeholder="Nama Jabatan"
                            value="<?= $jabatan['jabatan'] ?>">
                        <div class="invalid-feedback">
                            <?= isset($validation) ? $validation->getError('jabatan') : '' ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>