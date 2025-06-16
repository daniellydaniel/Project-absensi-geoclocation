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

<!-- Judul Tracking -->
<h4 class="fw-bold text-center text-primary mb-3">📍 Tracking Lokasi Pegawai</h4>

<!-- Maps -->
<div class="container my-3">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between bg-light p-3 rounded shadow-sm mb-3">
        <div class="form-check p-3 bg-light rounded shadow-sm d-flex align-items-center mb-3">
            <input class="form-check-input me-2" type="checkbox" id="filterDalamRadius">
            <label class="form-check-label fw-semibold" for="filterDalamRadius">
                Tampilkan hanya pegawai dalam radius
            </label>
        </div>

        <button id="refreshLokasi" class="btn btn-sm btn-primary mb-2">
            🔄 Refresh Lokasi Pegawai
        </button>

    </div>

    <div class="d-flex justify-content-end mb-2 gap-2" style="opacity: 0.8;">
        <!-- Tombol Simulasi -->
        <button id="btnSimulasi" class="btn btn-sm btn-outline-success" title="Jalankan Simulasi">
            ✅
        </button>

        <!-- Tombol Stop Simulasi -->
        <button id="btnStopSimulasi" class="btn btn-sm btn-outline-danger d-none" title="Hentikan Simulasi">
            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>✖
        </button>
    </div>

    <div id="map" class="rounded shadow-sm mb-4" style="height: 400px;"></div>
</div>

<div id="notif-auto-refresh" class="position-fixed top-0 end-0 m-3 bg-success text-white px-3 py-2 rounded d-none shadow">
    🔁 Data lokasi pegawai diperbarui
</div>

<script>
    // Simpan marker dalam dan luar di variabel global
    window.markersDalam = [];
    window.markersLuar = [];

    document.getElementById('refreshLokasi').addEventListener('click', function() {
        fetch("<?= base_url('admin/home/getLokasiPegawai') ?>")
            .then(response => response.json())
            .then(data => {
                // Hapus marker sebelumnya
                window.markersDalam.forEach(m => map.removeLayer(m));
                window.markersLuar.forEach(m => map.removeLayer(m));
                window.markersDalam = [];
                window.markersLuar = [];

                // Tambah marker pegawai dalam radius
                data.dalam.forEach(p => {
                    let marker = L.marker([p.latitude, p.longitude], {
                        icon: L.icon({
                            iconUrl: '<?= base_url("assets/images/icon_dalam.png") ?>',
                            iconSize: [32, 32]
                        })
                    }).addTo(map).bindPopup(`<b>${p.nama}</b><br>✅ Dalam Radius`);
                    window.markersDalam.push(marker);
                });

                // Tambah marker pegawai luar radius
                data.luar.forEach(p => {
                    let marker = L.marker([p.latitude, p.longitude], {
                        icon: L.icon({
                            iconUrl: '<?= base_url("assets/images/icon_luar.png") ?>',
                            iconSize: [32, 32]
                        })
                    }).addTo(map).bindPopup(`<b>${p.nama}</b><br>❌ Luar Radius`);
                    window.markersLuar.push(marker);
                });
            });
    });
</script>

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
    // Simpan semua marker agar bisa dihapus saat refresh
    let markersDalam = [];
    let markersLuar = [];

    function showNotifRefresh() {
        const notif = document.getElementById("notif-auto-refresh");
        notif.style.display = 'block';
        setTimeout(() => notif.style.display = 'none', 2000);
    }
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("btnSimulasi").addEventListener("click", function() {
            simulasiPergerakan();
        });


        const mapContainer = document.getElementById('map');
        if (!mapContainer) {
            alert("Map container tidak ditemukan.");
            return;
        }

        initMap();
        loadLokasiPegawai();
        // simulasiPergerakan();
        let simulasiTimeout = null;
        let dummyMarker = null;
        let indexSimulasi = 0;

        function simulasiPergerakan() {
            const simulasiCoords = [
                [-2.5933020755413363, 140.63097457294788],
                [-2.5935000000000000, 140.63085000000000],
                [-2.5940855755495487, 140.63076391698277]
            ];

            indexSimulasi = 0;

            document.getElementById("btnSimulasi").classList.add("d-none");
            document.getElementById("btnStopSimulasi").classList.remove("d-none");

            function getDistance(lat1, lon1, lat2, lon2) {
                const R = 6371e3;
                const φ1 = lat1 * Math.PI / 180;
                const φ2 = lat2 * Math.PI / 180;
                const Δφ = (lat2 - lat1) * Math.PI / 180;
                const Δλ = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                    Math.cos(φ1) * Math.cos(φ2) *
                    Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            function updateSimulasi() {
                if (indexSimulasi >= simulasiCoords.length) {
                    document.getElementById("btnSimulasi").classList.remove("d-none");
                    document.getElementById("btnStopSimulasi").classList.add("d-none");
                    return;
                }

                const [lat, lng] = simulasiCoords[indexSimulasi];
                const jarak = getDistance(lat, lng, lokasiKantor.lat, lokasiKantor.lng);

                const iconDummy = L.icon({
                    iconUrl: "<?= base_url('assets/images/pegawai.png') ?>",
                    iconSize: [36, 36],
                    iconAnchor: [18, 36],
                });

                if (!dummyMarker) {
                    dummyMarker = L.marker([lat, lng], {
                        icon: iconDummy
                    }).addTo(map);
                } else {
                    dummyMarker.setLatLng([lat, lng]);
                }

                dummyMarker.bindPopup(`
            <b>Simulasi Pegawai</b><br>
            Status: ${jarak <= lokasiKantor.radius ? '<span class="text-success">✅ Dalam Radius</span>' : '<span class="text-danger">❌ Luar Radius</span>'}<br>
            Jarak: ${Math.round(jarak)} meter
        `).openPopup();

                indexSimulasi++;
                simulasiTimeout = setTimeout(updateSimulasi, 3000);
            }

            updateSimulasi();
        }

        document.getElementById("btnStopSimulasi").addEventListener("click", function() {
            clearTimeout(simulasiTimeout);
            document.getElementById("btnSimulasi").classList.remove("d-none");
            document.getElementById("btnStopSimulasi").classList.add("d-none");

            if (dummyMarker) {
                dummyMarker.remove();
                dummyMarker = null;
            }
        });

        // Tombol manual
        document.getElementById("btnRefreshLokasi").addEventListener("click", function() {
            loadLokasiPegawai(showNotifRefresh);
        });

        setInterval(() => {
            console.log("⏰ Auto Refresh Lokasi Pegawai...");
            loadLokasiPegawai(showNotifRefresh);
        }, 10000);
        // 10000 ms = 10 detik
    });


    function initMap() {
        if (lokasiKantor.lat === 0 || lokasiKantor.lng === 0) {
            alert("Koordinat lokasi kantor belum diatur.");
            return;
        }

        map = L.map('map').setView([lokasiKantor.lat, lokasiKantor.lng], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // Marker kantor
        const iconKantor = L.divIcon({
            html: '<img src="<?= base_url('assets/images/gedung_sekolah.png') ?>" style="width: 50px; height: 64px; background: transparent;" alt="Gedung Sekolah">',
            iconSize: [50, 64],
            iconAnchor: [25, 64],
        });

        L.marker([lokasiKantor.lat, lokasiKantor.lng], {
                icon: iconKantor
            })
            .addTo(map)
            .bindPopup("🏢 Lokasi Sekolah");

        // Radius kantor
        L.circle([lokasiKantor.lat, lokasiKantor.lng], {
            radius: lokasiKantor.radius,
            color: 'red',
            fillColor: '#ffcccc',
            fillOpacity: 0.3
        }).addTo(map);

        // Perbaiki ukuran
        setTimeout(() => map.invalidateSize(), 300);
    }

    function tampilkanSemuaMarkerAwal() {
        const hanyaDalam = document.getElementById("filterDalamRadius").checked;

        pegawaiDalamRadius.forEach(pegawai => {
            const lat = parseFloat(pegawai.latitude);
            const lng = parseFloat(pegawai.longitude);

            if (hanyaDalam) {
                const icon = L.icon({
                    iconUrl: "<?= base_url('assets/images/icon_dalam.svg') ?>",
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                });

                const marker = L.marker([lat, lng], {
                        icon: icon
                    })
                    .addTo(map)
                    .bindPopup(`<b>${pegawai.nama}</b><br>NIP: ${pegawai.nip}<br>Status: ✅ Dalam Radius`);

                markersDalam.push(marker);
            }
        });

        if (!hanyaDalam) {
            pegawaiLuarRadius.forEach(pegawai => {
                const lat = parseFloat(pegawai.latitude);
                const lng = parseFloat(pegawai.longitude);

                const icon = L.icon({
                    iconUrl: "<?= base_url('assets/images/icon_luar.png') ?>",
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                });

                const marker = L.marker([lat, lng], {
                        icon: icon
                    })
                    .addTo(map)
                    .bindPopup(`<b>${pegawai.nama}</b><br>NIP: ${pegawai.nip}<br>Status: ❌ Luar Radius`);

                markersLuar.push(marker);
            });
        }
    }

    function loadLokasiPegawai(callback = null) {
        const hanyaDalam = document.getElementById("filterDalamRadius").checked;

        // Hapus semua marker lama
        markersDalam.forEach(marker => map.removeLayer(marker));
        markersLuar.forEach(marker => map.removeLayer(marker));
        markersDalam = [];
        markersLuar = [];

        fetch("<?= base_url('admin/home/getLokasiPegawai') ?>")
            .then(response => response.json())
            .then(data => {
                const semua = [...data.dalam, ...data.luar];

                semua.forEach(pegawai => {
                    const lat = parseFloat(pegawai.latitude);
                    const lng = parseFloat(pegawai.longitude);
                    const dalamRadius = data.dalam.some(p => p.id === pegawai.id);

                    if (hanyaDalam && !dalamRadius) return;

                    const icon = L.icon({
                        iconUrl: dalamRadius ?
                            "<?= base_url('assets/images/icon_dalam.png') ?>" : "<?= base_url('assets/images/icon_luar.png') ?>",
                        iconSize: [30, 30],
                        iconAnchor: [15, 30]
                    });

                    const marker = L.marker([lat, lng], {
                            icon
                        })
                        .addTo(map)
                        .bindPopup(`<b>${pegawai.nama}</b><br>NIP: ${pegawai.nip}<br>Status: ${dalamRadius ? '✅ Dalam Radius' : '❌ Luar Radius'}`);

                    (dalamRadius ? markersDalam : markersLuar).push(marker);
                });

                // ✅ Jalankan callback setelah marker selesai
                if (callback) callback();
            })
            .catch(error => {
                console.error("Gagal mengambil data lokasi:", error);
            });
    }
</script>

<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        let dummyMarker = null;
        let simulasiTimeout;

        document.getElementById("btnSimulasi").addEventListener("click", function() {
            const newLat = kantorLat + (Math.random() - 0.5) * 0.001;
            const newLng = kantorLng + (Math.random() - 0.5) * 0.001;

            dummyMarker = L.marker([newLat, newLng], {
                icon: markerDalam
            }).addTo(map).bindPopup("📍 Simulasi Lokasi Pegawai");

            this.classList.add("d-none");
            document.getElementById("btnStopSimulasi").classList.remove("d-none");

            simulasiTimeout = setTimeout(() => {
                dummyMarker.remove();
                dummyMarker = null;

                document.getElementById("btnSimulasi").classList.remove("d-none");
                document.getElementById("btnStopSimulasi").classList.add("d-none");
            }, 5000);
        });

        document.getElementById("btnStopSimulasi").addEventListener("click", function() {
            clearTimeout(simulasiTimeout);
            if (dummyMarker) {
                dummyMarker.remove();
                dummyMarker = null;
            }

            this.classList.add("d-none");
            document.getElementById("btnSimulasi").classList.remove("d-none");
        });

        document.getElementById("btnClearSimulasi").addEventListener("click", function() {
            if (dummyMarker) {
                dummyMarker.remove();
                dummyMarker = null;
            }

            clearTimeout(simulasiTimeout);

            document.getElementById("btnSimulasi").classList.remove("d-none");
            document.getElementById("btnStopSimulasi").classList.add("d-none");
        });
    });
</script> -->

<?= $this->endSection() ?>