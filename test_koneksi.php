<?php
// Test koneksi database
$conn = mysqli_connect('localhost', 'root', '', 'php_dasar');

if ($conn) {
    echo "✅ Koneksi ke database berhasil!\n";
    echo "Database: php_dasar\n";

    // Cek tabel yang ada
    $result = mysqli_query($conn, 'SHOW TABLES');
    if ($result) {
        echo "\n📋 Tabel yang tersedia:\n";
        $ada_tabel = false;
        while ($row = mysqli_fetch_array($result)) {
            echo "- " . $row[0] . "\n";
            $ada_tabel = true;
        }
        if (!$ada_tabel) {
            echo "❌ Tidak ada tabel dalam database php_dasar\n";
        }
    }

    // Cek struktur tabel mahasiswa
    $result = mysqli_query($conn, "DESCRIBE mahasiswa");
    if ($result) {
        echo "\n🏗️ Struktur tabel mahasiswa:\n";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        echo "\n❌ Tabel 'mahasiswa' tidak ditemukan!\n";
        echo "Anda perlu membuat tabel mahasiswa terlebih dahulu.\n";
    }

    mysqli_close($conn);
} else {
    echo "❌ Koneksi gagal: " . mysqli_connect_error() . "\n";
    echo "\n🔧 Pastikan:\n";
    echo "1. XAMPP sudah berjalan (Apache & MySQL)\n";
    echo "2. Database 'php_dasar' sudah dibuat di phpMyAdmin\n";
    echo "3. Username: root, Password: kosong\n";
}
