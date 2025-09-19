<?php
// Script untuk mengecek dan mengupdate semua data foto di database
$conn = mysqli_connect('localhost', 'root', '', 'php_dasar');

if ($conn) {
    echo "🔍 ANALISA DATA FOTO DI DATABASE\n";
    echo "=" . str_repeat("=", 50) . "\n\n";

    // 1. Cek total data dan yang punya foto
    $total_result = mysqli_query($conn, 'SELECT COUNT(*) as total FROM mahasiswa');
    $total_row = mysqli_fetch_assoc($total_result);
    $total_data = $total_row['total'];

    $foto_result = mysqli_query($conn, 'SELECT COUNT(*) as ada_foto FROM mahasiswa WHERE foto IS NOT NULL AND foto != ""');
    $foto_row = mysqli_fetch_assoc($foto_result);
    $ada_foto = $foto_row['ada_foto'];

    $kosong_foto = $total_data - $ada_foto;

    echo "📊 STATISTIK DATA:\n";
    echo "Total mahasiswa: $total_data\n";
    echo "Punya foto: $ada_foto\n";
    echo "Kosong foto: $kosong_foto\n\n";

    // 2. Tampilkan data yang punya foto
    echo "👥 DATA YANG SUDAH PUNYA FOTO:\n";
    echo "-" . str_repeat("-", 40) . "\n";
    $result = mysqli_query($conn, 'SELECT id, nama, foto FROM mahasiswa WHERE foto IS NOT NULL AND foto != ""');
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $file_exists = file_exists("img/" . $row['foto']) ? "✅" : "❌";
            echo "ID: {$row['id']} | {$row['nama']} | {$row['foto']} $file_exists\n";
        }
    } else {
        echo "Tidak ada data dengan foto\n";
    }

    // 3. Tampilkan beberapa data yang kosong
    echo "\n❌ CONTOH DATA YANG KOSONG FOTO (10 pertama):\n";
    echo "-" . str_repeat("-", 40) . "\n";
    $result = mysqli_query($conn, 'SELECT id, nama, foto FROM mahasiswa WHERE foto IS NULL OR foto = "" LIMIT 10');
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: {$row['id']} | {$row['nama']} | KOSONG\n";
        }
    }

    // 4. Cek foto yang tersedia di folder
    echo "\n📁 FOTO YANG TERSEDIA DI FOLDER:\n";
    echo "-" . str_repeat("-", 40) . "\n";
    $foto_dir = "img/";
    if (is_dir($foto_dir)) {
        $files = scandir($foto_dir);
        $image_files = [];
        foreach ($files as $file) {
            if ($file != "." && $file != ".." && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
                $image_files[] = $file;
            }
        }
        echo "Total foto tersedia: " . count($image_files) . "\n";
        foreach ($image_files as $file) {
            echo "- $file\n";
        }
    }

    // 5. Solusi otomatis assign foto
    echo "\n🔧 SOLUSI AUTO-ASSIGN FOTO:\n";
    echo "-" . str_repeat("-", 40) . "\n";

    // Mapping nama dengan foto yang tersedia
    $auto_mapping = [
        'Agus' => 'Agus.jpg',
        'Andi' => 'Andi.jpg',
        'Aris' => 'Aris.png',
        'Bayu' => 'Bayu.jpg',
        'Budi' => 'Budi.jpg',
        'Dedi' => 'Dedi.jpg',
        'Dewi' => 'Dewi.jpg',
        'Dian' => 'Dian.jpg',
        'Doni' => 'Doni.jpg',
        'Eko' => 'Eko.jpg',
        'Fadly' => 'Fadly.png',
        'Fajar' => 'Fajar.jpg',
        'Hadi' => 'Hadi.jpg',
        'Joko' => 'Joko.jpg',
        'Lia' => 'Lia.jpg',
        'Lina' => 'Lina.jpg',
        'Maya' => 'Maya.jpg',
        'Mega' => 'Mega.png',
        'Nita' => 'Nita.jpg',
        'Rina' => 'Rina.jpg',
        'Rini' => 'Rini.jpg',
        'Rudi' => 'Rudi.jpg',
        'Sari' => 'Sari.jpg',
        'Siti' => 'Siti.jpg',
        'Yuni' => 'Yuni.jpg'
    ];

    echo "💡 Dapat auto-assign foto untuk nama yang cocok:\n";
    $dapat_assign = 0;

    // Cek nama yang bisa di-assign
    $result = mysqli_query($conn, 'SELECT id, nama FROM mahasiswa WHERE foto IS NULL OR foto = ""');
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $nama = $row['nama'];
            $first_name = explode(' ', $nama)[0]; // Ambil nama depan

            if (isset($auto_mapping[$first_name])) {
                echo "- ID {$row['id']}: $nama -> {$auto_mapping[$first_name]}\n";
                $dapat_assign++;
            }
        }
    }

    echo "\nTotal yang dapat di-assign otomatis: $dapat_assign\n";
    echo "Masih kosong setelah auto-assign: " . ($kosong_foto - $dapat_assign) . "\n";

    mysqli_close($conn);
} else {
    echo "❌ Koneksi gagal: " . mysqli_connect_error() . "\n";
}
