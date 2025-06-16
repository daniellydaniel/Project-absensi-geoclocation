<?= $this->extend('kepsek/layout') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">
            Selamat datang di dashboard kepala sekolah. Silakan pilih menu rekap laporan di bawah ini.</h2>
    </div>

    <div class="row justify-content-center gap-4">
        <!-- Rekap Harian -->
        <div class="col-md-4">
            <a href="<?= site_url('kepsek/rekap_harian') ?>" class="text-decoration-none">
                <div class="card shadow-sm border-start border-4 border-primary h-100 hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">📅 Rekap Harian</h5>
                        <p class="card-text text-muted">Lihat data absensi harian pegawai</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Rekap Bulanan -->
        <div class="col-md-4">
            <a href="<?= site_url('kepsek/rekap_bulanan') ?>" class="text-decoration-none">
                <div class="card shadow-sm border-start border-4 border-success h-100 hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-success">📊 Rekap Bulanan</h5>
                        <p class="card-text text-muted">Lihat rekap absensi per bulan</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>