<?php

if (!function_exists('time_ago')) {
    function time_ago($datetime) {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;

        if ($diff < 60)
            return $diff . ' detik lalu';
        elseif ($diff < 3600)
            return floor($diff / 60) . ' menit lalu';
        elseif ($diff < 86400)
            return floor($diff / 3600) . ' jam lalu';
        elseif ($diff < 2592000)
            return floor($diff / 86400) . ' hari lalu';
        elseif ($diff < 31536000)
            return floor($diff / 2592000) . ' bulan lalu';
        else
            return floor($diff / 31536000) . ' tahun lalu';
    }
}
