<?php
// Script untuk mengecek data dalam database
$conn = mysqli_connect('localhost', 'root', '', 'php_dasar');

if ($conn) {
    echo "🔍 Mengecek data dalam tabel mahasiswa...\n\n";

    $result = mysqli_query($conn, 'SELECT id, nama, foto FROM mahasiswa');
    if ($result && mysqli_num_rows($result) > 0) {
        echo "📊 Data yang ada:\n";
        echo "=" . str_repeat("=", 50) . "\n";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: " . $row['id'] . "\n";
            echo "Nama: " . $row['nama'] . "\n";
            echo "Foto: " . ($row['foto'] ?: 'NULL/KOSONG') . "\n";
            echo "-" . str_repeat("-", 30) . "\n";
        }
    } else {
        echo "❌ Tidak ada data dalam tabel mahasiswa\n";
        echo "💡 Kemungkinan: Belum ada data yang ditambahkan\n";
    }

    echo "\n🔍 Mengecek folder foto...\n";
    $foto_dir = "img/";
    if (is_dir($foto_dir)) {
        $files = scandir($foto_dir);
        $image_files = [];
        foreach ($files as $file) {
            if ($file != "." && $file != ".." && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
                $image_files[] = $file;
            }
        }
        echo "📁 Foto yang tersedia (" . count($image_files) . " file):\n";
        foreach ($image_files as $file) {
            echo "- " . $file . "\n";
        }
    } else {
        echo "❌ Folder 'img/' tidak ditemukan\n";
    }

    mysqli_close($conn);
} else {
    echo "❌ Koneksi gagal: " . mysqli_connect_error() . "\n";
}
