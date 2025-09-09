
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latihan Function</title>
</head>

<body>
    <h1> <?php echo salam("Pagi", "User"); ?> </h1>
</body>

</html>


<?php
function salam($welcome = "Datang", $nama_admin = "Admin")
{
    return "Selamat $welcome, $nama_admin!";
}

$hari  = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
$bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$ar1   = [28, "Juli", true];

var_dump($hari);
echo "<br>";
print_r($bulan);
echo "<br>";
var_dump($ar1);
echo "<br>";
echo $hari[0];
echo "<br>";



$mahasiswa = [ 
    ["Rifki"  , "0801234", "Ilmu Kekebalan"],
    ["Alya"   , "0805678", "Sistem Kedokteran"],
    ["Michael", "08091011", "Teknik Hukum"]

];

echo $mahasiswa[1][0] . " - " . $mahasiswa[1][1] . " - " . $mahasiswa[1][2];
echo "<br>";
foreach ($mahasiswa as $mhs) {
    foreach ($mhs as $value) {
        echo "$value ";
    }
    echo "<br>";
}


