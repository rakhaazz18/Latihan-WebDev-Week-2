<?php
$mahasiswa = [
    [
        "nama" => "Budi",
        "nim" => "12345678",
        "jurusan" => "Teknik Informatika",
        "angkatan" => 2022
    ],
    [
        "nama" => "Siti",
        "nim" => "87654321",
        "jurusan" => "Sistem Informasi",
        "angkatan" => 2021
    ],
    [
        "nama" => "Andi",
        "nim" => "11223344",
        "jurusan" => "Teknik Komputer",
        "angkatan" => 2023
    ],
    [
        "nama" => "Rina",
        "nim" => "44332211",
        "jurusan" => "Manajemen Informatika",
        "angkatan" => 2020
    ]
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa</title>
</head>

<body>
    <h1>Daftar Mahasiswa</h1>
    <ul>
        <?php foreach ($mahasiswa as $mhs): ?>
            <li>
                <a href="detailmhs.php?nama=<?php echo urlencode($mhs['nama']); ?>&nim=<?php echo urlencode($mhs['nim']); ?>">
                    <?php echo $mhs['nama'] . " - " . $mhs['nim'] . " - " . $mhs['jurusan'] . " - " . $mhs['angkatan']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>