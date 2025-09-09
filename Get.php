<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa</title>
</head>

<body>

    <?php
    // Contoh data mahasiswa
    $mahasiswa = [
        [
            'nama' => 'Andi',
            'umur' => 20,
            'gambar' => 'andi.jpg'
        ],
        [
            'nama' => 'Budi',
            'umur' => 21,
            'gambar' => 'budi.jpg'
        ],
        [
            'nama' => 'Citra',
            'umur' => 19,
            'gambar' => 'zee.jpg'
        ]
    ];
    

    if (isset($_GET['nama_get']) && isset($_GET['umur_get'])) {
        $nama = htmlspecialchars($_GET['nama_get']);
        $umur = htmlspecialchars($_GET['umur_get']);
        echo "<h3>Hasil Input (GET):</h3>";
        echo "Nama: $nama<br>";
        echo "Umur: $umur tahun<br><br>";
    }

    ?>

    <?php if (isset($_GET['mahasiswa']) && $_GET['mahasiswa'] !== "" && isset($mahasiswa[(int)$_GET['mahasiswa']])): ?>
        <?php $idx = (int)$_GET['mahasiswa'];
        $mhs = $mahasiswa[$idx]; ?>
        <h2>Detail Mahasiswa</h2>
        <strong>Nama:</strong> <?php echo htmlspecialchars($mhs['nama']); ?><br>
        <strong>Umur:</strong> <?php echo htmlspecialchars($mhs['umur']); ?> tahun<br>
        <?php $imgPath = 'img/' . $mhs['gambar']; ?>
        <?php if (file_exists($imgPath)): ?>
            <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($mhs['nama']); ?>" width="120">
        <?php else: ?>
            <em>Gambar tidak tersedia</em>
        <?php endif; ?>
        <br><br>
        <!-- Tombol GET untuk kembali ke daftar mahasiswa -->
        <form action="" method="get">
            <button type="submit">Kembali ke Daftar Mahasiswa</button>
        </form>
    <?php else: ?>
        <h2>Daftar Mahasiswa</h2>
        <ul>
            <?php foreach ($mahasiswa as $index => $mhs): ?>
                <li>
                    <?php echo htmlspecialchars($mhs['nama']); ?>
                    <a href="?mahasiswa=<?php echo $index; ?>">Lihat Detail</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php
    // Contoh data mahasiswa
    $mahasiswa = [
        [
            'nama' => 'Andi',
            'umur' => 20,
            'gambar' => 'andi.jpg'
        ],
        [
            'nama' => 'Budi',
            'umur' => 21,
            'gambar' => 'budi.jpg'
        ],
        [
            'nama' => 'Citra',
            'umur' => 19,
            'gambar' => 'zee.jpg'
        ]
    ];
    ?>

    <?php
    // Penanganan GET
    if (isset($_GET['nama_get']) && isset($_GET['umur_get'])) {
        $nama = htmlspecialchars($_GET['nama_get']);
        $umur = htmlspecialchars($_GET['umur_get']);
        echo "<h3>Hasil Input (GET):</h3>";
        echo "Nama: $nama<br>";
        echo "Umur: $umur tahun<br><br>";
    }
    ?>
</body>

</html>