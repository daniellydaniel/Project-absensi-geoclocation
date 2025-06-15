<?php

/** @var \CodeIgniter\View\View $this */
?>
<?= $this->extend('pegawai/layout') ?>

<?= $this->section('content') ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
    integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<input type="hidden" id="id_pegawai" name="id_pegawai" value="<?= $id_pegawai ?>">
<input type="hidden" id="tanggal_masuk" name="tanggal_masuk" value="<?= $tanggal_masuk ?>">
<input type="hidden" id="jam_masuk" name="jam_masuk" value="<?= $jam_masuk ?>">


<div style="display: flex; align-items: center; gap: 20px; padding: 30px;">

    <div id="my_camera"></div>
    <div style="display: none;" id="my_result"></div>
</div>

<button class="btn btn-primary" id="ambil-foto" style="margin-left: 20px;">Masuk</button>

<div id="my_result" style="padding: 20px;"></div>


<script>
    Webcam.set({
        width: 320,
        height: 240,
        dest_width: 320,
        dest_height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90,
        force_flash: false
    });

    Webcam.attach('#my_camera');

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('ambil-foto').addEventListener('click', function() {
            let id = document.getElementsByName('id_pegawai')[0].value;
            let tanggal_masuk = document.getElementsByName('tanggal_masuk')[0].value;
            let jam_masuk = document.getElementsByName('jam_masuk')[0].value;
            let lat = document.getElementById('latitude_pegawai')?.value || '';
            let lon = document.getElementById('longitude_pegawai')?.value || '';
    
            console.log(tanggal_masuk);
    
            Webcam.snap(function(data_uri) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '"/>';
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        window.location.href = '<?= base_url('pegawai/home') ?>';
                    }
                };
                xhttp.open("POST", "<?= base_url('pegawai/presensi_masuk_aksi') ?>", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(
                    'foto_masuk=' + encodeURIComponent(data_uri) +
                    '&id_pegawai=' + id +
                    '&tanggal_masuk=' + tanggal_masuk +
                    '&jam_masuk=' + jam_masuk +
                    '&latitude=' + lat +
                    '&longitude=' + lon
                );
            });
        });
    });
</script>

<?= $this->endSection() ?>