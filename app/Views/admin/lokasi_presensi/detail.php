<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Leaflet CSS -->
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    #map {
        height: 400px;
        width: 100%;
        border-radius: 8px;
    }
</style>

<div class="container mt-4">
    <div class="row">
        <!-- Kolom Detail Lokasi -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold fs-5">Detail Lokasi Presensi</div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Nama Lokasi</td>
                            <td>:</td>
                            <td><?= $lokasi_presensi['nama_lokasi'] ?></td>
                        </tr>
                        <tr>
                            <td>Alamat Lokasi</td>
                            <td>:</td>
                            <td><?= $lokasi_presensi['alamat_lokasi'] ?></td>
                        </tr>
                        <tr>
                            <td>Tipe Lokasi</td>
                            <td>:</td>
                            <td><?= $lokasi_presensi['tipe_lokasi'] ?></td>
                        </tr>
                        <tr>
                            <td>Latitude</td>
                            <td>:</td>
                            <td><?= $lokasi_presensi['latitude'] ?></td>
                        </tr>
                        <tr>
                            <td>Longitude</td>
                            <td>:</td>
                            <td><?= $lokasi_presensi['longitude'] ?></td>
                        </tr>
                        <tr>
                            <td>Radius</td>
                            <td>:</td>
                            <td><?= $lokasi_presensi['radius'] ?></td>
                        </tr>
                        <tr>
                            <td>Zona Waktu</td>
                            <td>:</td>
                            <td><?= $lokasi_presensi['zona_waktu'] ?></td>
                        </tr>
                        <tr>
                            <td>Jam</td>
                            <td>:</td>
                            <td><?= $lokasi_presensi['jam_masuk'] ?></td>
                        </tr>
                        <tr>
                            <td>Jam Pulang</td>
                            <td>:</td>
                            <td><?= $lokasi_presensi['jam_pulang'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Map -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold fs-5">Lokasi di Map</div>
                <div class="card-body">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    var latitude = <?= json_encode($lokasi_presensi['latitude']) ?>;
    var longitude = <?= json_encode($lokasi_presensi['longitude']) ?>;

    var map = L.map('map').setView([latitude, longitude], 17);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    L.marker([latitude, longitude]).addTo(map)
        .bindPopup("<?= $lokasi_presensi['nama_lokasi'] ?>").openPopup();

    L.circle([latitude, longitude], {
        color: 'blue',
        fillColor: '#3f9cf7',
        fillOpacity: 0.2,
        radius: <?= json_encode($lokasi_presensi['radius']) ?>
    }).addTo(map);
</script>

<?= $this->endSection() ?>