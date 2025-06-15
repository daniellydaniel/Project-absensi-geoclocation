<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <!-- Total Pegawai -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="icon-card mb-30">
            <div class="icon purple">
                <i class="lni lni-users"></i>
            </div>
            <div class="content">
                <h6 class="mb-10">Total Guru</h6>
                <h3 class="text-bold mb-10"><?= esc($totalPegawai) ?></h3>
            </div>
        </div>
    </div>

    <!-- Presensi Hari Ini -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="icon-card mb-30">
            <div class="icon success">
                <i class="lni lni-checkmark-circle"></i>
            </div>
            <div class="content">
                <h6 class="mb-10">Absensi Hari Ini</h6>
                <h3 class="text-bold mb-10"><?= esc($presensiHariIni) ?></h3>
            </div>
        </div>
    </div>

    <!-- Absensi Bulan Ini -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="icon-card mb-30">
            <div class="icon warning">
                <i class="lni lni-calendar"></i>
            </div>
            <div class="content">
                <h6 class="mb-10">Absensi Bulan Ini</h6>
                <h3 class="text-bold mb-10"><?= esc($presensiBulanIni) ?></h3>
            </div>
        </div>
    </div>

    <!-- Lokasi Presensi -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="icon-card mb-30">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
            </svg>
            <div class="content">
                <h6 class="mb-10">Lokasi Absensi</h6>
                <h3 class="text-bold mb-10"><?= esc($lokasiPresensi) ?></h3>
            </div>
        </div>
    </div>

    <!-- Total Jabatan -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="icon-card mb-30">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ff3838" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-square">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M9 10a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                <path d="M6 21v-1a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v1" />
                <path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
            </svg>
            <div class="content">
                <h6 class="mb-10">Total Jabatan</h6>
                <h3 class="text-bold mb-10"><?= esc($jabatan) ?></h3>
            </div>
        </div>
    </div>
</div>


<!-- Divider -->
<div class="divider" style="width: 100%; height: 1px; background-color: #ccc; margin: 20px 0;"></div>

<!-- Map Section -->
<div id="map" style="height: 400px; width: 100%; border-radius: 8px; max-width: 900px; margin: 0 auto 30px auto;"></div>

<!-- Leaflet CSS dan JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<script>
    const pegawaiDalamRadius = <?= json_encode($pegawaiDalamRadius) ?>;
    const pegawaiLuarRadius = <?= json_encode($pegawaiLuarRadius) ?>;
    const lokasiKantor = {
        lat: <?= $lokasi_kantor['latitude'] ?>,
        lng: <?= $lokasi_kantor['longitude'] ?>,
        radius: <?= $lokasi_kantor['radius'] ?>
    };

    let map;

    document.addEventListener("DOMContentLoaded", function() {
        const mapContainer = document.getElementById('map');
        if (!mapContainer) {
            alert("Map container tidak ditemukan.");
            return;
        }
        initMap();
    });

    function initMap() {
        if (lokasiKantor.lat === 0 || lokasiKantor.lng === 0) {
            alert("Koordinat lokasi kantor belum diatur.");
            return;
        }

        map = L.map('map').setView([lokasiKantor.lat, lokasiKantor.lng], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
        }).addTo(map);

        // Marker Lokasi Kantor
        const iconKantor = L.divIcon({
            className: 'custom-icon',
            html: `<img src="<?= base_url('assets/images/gedung_sekolah.png') ?>" style="width:50px;height:64px;">`,
            iconSize: [50, 64],
            iconAnchor: [25, 64],
        });

        L.marker([lokasiKantor.lat, lokasiKantor.lng], { icon: iconKantor })
            .addTo(map)
            .bindPopup("🏢 Lokasi Sekolah");

        // Lingkaran radius
        L.circle([lokasiKantor.lat, lokasiKantor.lng], {
            radius: lokasiKantor.radius,
            color: 'red',
            fillColor: '#ffcccc',
            fillOpacity: 0.3
        }).addTo(map);

        // Marker Pegawai Dalam Radius
        pegawaiDalamRadius.forEach(pegawai => {
            const lat = parseFloat(pegawai.latitude);
            const lng = parseFloat(pegawai.longitude);
            const distance = map.distance([lat, lng], [lokasiKantor.lat, lokasiKantor.lng]);

            if (!isNaN(lat) && !isNaN(lng)) {
                const iconPegawai = L.divIcon({
                    className: 'custom-icon',
                    html: `<img src="<?= base_url('assets/images/pegawai.png') ?>" style="width:36px;height:36px;">`,
                    iconSize: [36, 36],
                    iconAnchor: [18, 36],
                });

                L.marker([lat, lng], { icon: iconPegawai })
                    .addTo(map)
                    .bindPopup(`
                        <strong>${pegawai.nama ?? 'Tidak diketahui'}</strong><br>
                        NIP: ${pegawai.nip ?? '-'}<br>
                        ✅ Dalam Radius<br>
                        Jarak: ${Math.round(distance)} meter
                    `);
            }
        });

        // Marker Pegawai Luar Radius
        pegawaiLuarRadius.forEach(pegawai => {
            const lat = parseFloat(pegawai.latitude);
            const lng = parseFloat(pegawai.longitude);
            const distance = map.distance([lat, lng], [lokasiKantor.lat, lokasiKantor.lng]);

            if (!isNaN(lat) && !isNaN(lng)) {
                const iconMerah = L.icon({
                    iconUrl: '<?= base_url('marker-red.png') ?>',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [0, -41]
                });

                L.marker([lat, lng], { icon: iconMerah })
                    .addTo(map)
                    .bindPopup(`
                        <strong>${pegawai.nama ?? 'Tidak diketahui'}</strong><br>
                        NIP: ${pegawai.nip ?? '-'}<br>
                        ❌ Luar Radius<br>
                        Jarak: ${Math.round(distance)} meter
                    `);
            }
        });

        // Refresh ukuran map
        setTimeout(() => map.invalidateSize(), 300);
    }

    // Debug log
    console.log("✅ Pegawai Dalam Radius:", pegawaiDalamRadius);
    console.log("❌ Pegawai Luar Radius:", pegawaiLuarRadius);
</script>

<?= $this->endSection() ?>