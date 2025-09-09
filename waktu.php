<?php
// Function untuk menghitung selisih waktu (format: HH:MM:SS)
function hitungSelisihWaktu($waktu1, $waktu2)
{
    $t1 = strtotime($waktu1);
    $t2 = strtotime($waktu2);
    $selisih = abs($t2 - $t1);
    $jam = floor($selisih / 3600);
    $menit = floor(($selisih % 3600) / 60);
    $detik = $selisih % 60;
    return sprintf("%02d:%02d:%02d", $jam, $menit, $detik);
}

// Function untuk mengalikan waktu (format: HH:MM:SS) dengan faktor tertentu
function kalikanWaktu($waktu, $faktor)
{
    $t = strtotime($waktu) - strtotime('TODAY');
    $hasil = $t * $faktor;
    $jam = floor($hasil / 3600);
    $menit = floor(($hasil % 3600) / 60);
    $detik = $hasil % 60;
    return sprintf("%02d:%02d:%02d", $jam, $menit, $detik);
}

// Contoh penggunaan:
$waktu1 = "08:30:00";
$waktu2 = "12:45:30";
echo "Selisih waktu: " . hitungSelisihWaktu($waktu1, $waktu2) . "<br>";

$waktu = "01:15:00"; // 1 jam 15 menit
$faktor = 3;
echo "Waktu dikali $faktor: " . kalikanWaktu($waktu, $faktor);
