<?php
$zona = $lokasi_presensi['zona_waktu'] ?? 'WIB';
switch ($zona) {
    case 'WIT':
        date_default_timezone_set('Asia/Jayapura');
        break;
    case 'WITA':
        date_default_timezone_set('Asia/Makassar');
        break;
    default:
        date_default_timezone_set('Asia/Jakarta');
        break;
}
?>
<?= $this->extend('pegawai/layout') ?>

<?= $this->section('content') ?>

<style>
    .parent-clock {
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        font-size: 35px;
        font-weight: bold;
        justify-content: center;
    }

    #map {
        height: 400px;
        width: 100%;
        border-radius: 8px;
        max-width: 600px;
        margin: 20px auto;
        min-height: 400px;
    }

    .divider {
        width: 100%;
        height: 1px;
        background-color: #ccc;
        margin: 20px 0;
    }
</style>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('gagal')) : ?>
    <div class="alert alert-danger text-center"><?= session()->getFlashdata('gagal') ?></div>
<?php endif; ?>

<div class="row">

    <!-- PRESENSI MASUK -->
    <div class="col-md-2"></div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white fw-bold text-center">Absensi Masuk</div>
            <?php if ($cek_presensi < 1 || $ambil_presensi_masuk === null) : ?>
                <div class="card-body text-center border border-dark">
                    <div class="fw-bold"><?= date('D F Y') ?></div>
                    <div class="parent-clock">
                        <div id="jam-masuk"></div>
                        <div>:</div>
                        <div id="menit-masuk"></div>
                        <div>:</div>
                        <div id="detik-masuk"></div>
                    </div>
                    <form action="<?= base_url('pegawai/presensi_masuk') ?>" method="post">
                        <input type="hidden" name="latitude_kantor" value="<?= $lokasi_presensi['latitude'] ?? 0 ?>">
                        <input type="hidden" name="longitude_kantor" value="<?= $lokasi_presensi['longitude'] ?? 0 ?>">
                        <input type="hidden" name="radius" value="<?= $lokasi_presensi['radius'] ?? 0 ?>">
                        <input type="text" name="latitude_pegawai" id="latitude_pegawai">
                        <input type="text" name="longitude_pegawai" id="longitude_pegawai">
                        <input type="hidden" name="tanggal_masuk" value="<?= date('Y-m-d') ?>">
                        <input type="hidden" name="jam_masuk" value="<?= date('H:i:s') ?>">
                        <input type="hidden" id="jam_masuk_real" name="jam_masuk_real">
                        <input type="hidden" name="id_pegawai" value="<?= session()->get('id_pegawai') ?>">
                        <button type="submit" class="btn btn-success">Absen Masuk</button>
                    </form>
                </div>
            <?php else : ?>
                <div class="card-body text-center">
                    <h5 class="mb-3">Anda telah melakukan Absensi masuk</h5>
                    <svg width="48" height="48" fill="none" viewBox="0 0 24 24" class="text-success">
                        <path d="M19.28 6.76a.75.75 0 010 1.06l-9.42 9.42a.75.75 0 01-1.06 0L4.72 13.16a.75.75 0 111.06-1.06l3.55 3.55 8.89-8.89a.75.75 0 011.06 0z" fill="#28a745" />
                    </svg>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- PRESENSI KELUAR -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-danger text-white fw-bold text-center">Absensi Keluar</div>

            <?php if ($cek_presensi < 1 || $ambil_presensi_masuk === null) : ?>
                <div class="card-body text-center">
                    <h5 class="text-center mt-3">Anda belum melakukan Absensi masuk</h5>
                </div>

            <?php elseif ($cek_presensi_keluar > 0) : ?>
                <div class="card-body text-center">
                    <h5 class="text-center mt-3">Anda telah melakukan Absensi keluar</h5>
                    <svg width="48" height="48" fill="none" viewBox="0 0 24 24" class="text-success">
                        <path d="M19.28 6.76a.75.75 0 010 1.06l-9.42 9.42a.75.75 0 01-1.06 0L4.72 13.16a.75.75 0 111.06-1.06l3.55 3.55 8.89-8.89a.75.75 0 011.06 0z" fill="#28a745" />
                    </svg>
                </div>

            <?php else : ?>
                <div class="card-body text-center border border-dark">
                    <div class="fw-bold"><?= date('D F Y') ?></div>
                    <div class="parent-clock">
                        <div id="jam-keluar"></div>
                        <div>:</div>
                        <div id="menit-keluar"></div>
                        <div>:</div>
                        <div id="detik-keluar"></div>
                    </div>
                    <?php $actionKeluar = base_url('pegawai/presensi_keluar/' . $ambil_presensi_masuk['id']); ?>
                    <form method="POST" action="<?= $actionKeluar ?>">
                        <input type="hidden" name="latitude_kantor" value="<?= $lokasi_presensi['latitude'] ?>">
                        <input type="hidden" name="longitude_kantor" value="<?= $lokasi_presensi['longitude'] ?>">
                        <input type="hidden" name="radius" value="<?= $lokasi_presensi['radius'] ?>">
                        <input type="text" name="latitude_pegawai" id="latitude_keluar">
                        <input type="text" name="longitude_pegawai" id="longitude_keluar">
                        <input type="hidden" name="tanggal_keluar" value="<?= date('Y-m-d') ?>">
                        <input type="hidden" name="jam_keluar" value="<?= date('H:i:s') ?>">
                        <button class="btn btn-danger mt-3">Keluar</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="divider"></div>

<!-- Judul Tracking -->
<h4 class="fw-bold text-center text-primary mb-3">📍 Map Absensi </h4>

<!-- Maps -->
<div id="map"></div>

<!-- LEAFLET CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Timer presensi real-time
    setInterval(waktuMasuk, 1000);
    setInterval(waktuKeluar, 1000);

    function waktuMasuk() {
        const t = new Date();
        if (document.getElementById("jam-masuk")) {
            document.getElementById("jam-masuk").textContent = format(t.getHours());
            document.getElementById("menit-masuk").textContent = format(t.getMinutes());
            document.getElementById("detik-masuk").textContent = format(t.getSeconds());
        }
    }

    function waktuKeluar() {
        const t = new Date();
        if (document.getElementById("jam-keluar")) {
            document.getElementById("jam-keluar").textContent = format(t.getHours());
            document.getElementById("menit-keluar").textContent = format(t.getMinutes());
            document.getElementById("detik-keluar").textContent = format(t.getSeconds());
        }
    }

    function format(w) {
        return w < 10 ? "0" + w : w;
    }

    // Map
    const map = L.map('map').setView([<?= $lokasiPegawai['latitude'] ?>, <?= $lokasiPegawai['longitude'] ?>], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // Marker Pegawai
    L.marker([<?= $lokasiPegawai['latitude'] ?>, <?= $lokasiPegawai['longitude'] ?>])
        .addTo(map)
        .bindPopup("📍 Lokasi Anda Sekarang")
        .openPopup();

    // Marker Kantor
    L.marker([<?= $lokasi_presensi['latitude'] ?>, <?= $lokasi_presensi['longitude'] ?>], {
        icon: L.icon({
            iconUrl: "<?= base_url('assets/images/gedung_sekolah.png') ?>",
            iconSize: [42, 42]
        })
    }).addTo(map).bindPopup("🏢 Lokasi Kantor");

    // Radius kantor
    L.circle([<?= $lokasi_presensi['latitude'] ?>, <?= $lokasi_presensi['longitude'] ?>], {
        radius: <?= $lokasi_presensi['radius'] ?>,
        color: 'blue',
        fillColor: '#cce5ff',
        fillOpacity: 0.3
    }).addTo(map).bindPopup("Radius Area Absensi");

    // Lokasi Masuk
    <?php if (is_array($ambil_presensi_masuk) && !empty($ambil_presensi_masuk['latitude_masuk'])) : ?>
        L.marker(
            [<?= $ambil_presensi_masuk['latitude_masuk'] ?>, <?= $ambil_presensi_masuk['longitude_masuk'] ?>], {
                icon: L.icon({
                    iconUrl: "<?= base_url('assets/images/icon_masuk.png') ?>",
                    iconSize: [32, 32]
                })
            }
        ).addTo(map).bindPopup("📌 Lokasi Absensi Masuk");
    <?php endif; ?>

    // Lokasi Keluar
    <?php if (!empty($ambil_presensi_masuk['latitude_keluar'])) : ?>
        L.marker(
            [<?= $ambil_presensi_masuk['latitude_keluar'] ?>, <?= $ambil_presensi_masuk['longitude_keluar'] ?>], {
                icon: L.icon({
                    iconUrl: "<?= base_url('assets/images/icon_keluar.png') ?>",
                    iconSize: [32, 32]
                })
            }
        ).addTo(map).bindPopup("🏁 Lokasi Absensi Keluar");
    <?php endif; ?>

    // Ambil lokasi dari device
    navigator.geolocation.getCurrentPosition(function(position) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;

        // Update map
        map.setView([lat, lon], 17);

        // Marker posisi terkini
        L.marker([lat, lon])
            .addTo(map)
            .bindPopup("📍 Lokasi Anda Sekarang")
            .openPopup();

        // Isi ke form absen masuk
        const latMasuk = document.getElementById('latitude_masuk');
        const lonMasuk = document.getElementById('longitude_masuk');
        if (latMasuk && lonMasuk) {
            latMasuk.value = lat;
            lonMasuk.value = lon;
        }

        // Isi ke form absen keluar
        const latKeluar = document.getElementById('latitude_keluar');
        const lonKeluar = document.getElementById('longitude_keluar');
        if (latKeluar && lonKeluar) {
            latKeluar.value = lat;
            lonKeluar.value = lon;
        }
    });

    const formMasuk = document.querySelector('form[action*="presensi_masuk"]');
    if (formMasuk) {
        formMasuk.addEventListener('submit', function() {
            const now = new Date();
            const input = document.getElementById("jam_masuk_real");
            if (input) input.value = now.toTimeString().split(' ')[0];
        });
    }

    const formKeluar = document.querySelector('form[action*="presensi_keluar"]');
    if (formKeluar) {
        formKeluar.addEventListener('submit', function() {
            const now = new Date();
            const jamMasukReal = document.getElementById("jam_masuk_real");
            if (jamMasukReal) jamMasukReal.value = now.toTimeString().split(' ')[0];
        });
    }

    setInterval(() => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;

                fetch("<?= base_url('pegawai/update_lokasi') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lon
                    })
                }).then(res => res.json()).then(data => {
                    console.log("Lokasi terkirim:", data);
                }).catch(err => {
                    console.error("Gagal kirim lokasi", err);
                });
            });
        }
    }, 5000); // tiap 5 detik
</script>


<?= $this->endSection() ?>