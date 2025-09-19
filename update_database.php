<?php
// Script untuk mengubah struktur kolom foto
$conn = mysqli_connect('localhost', 'root', '', 'php_dasar');

if ($conn) {
    echo "Mengubah struktur kolom foto...\n";
    $sql = "ALTER TABLE mahasiswa MODIFY COLUMN foto VARCHAR(255)";
    if (mysqli_query($conn, $sql)) {
        echo "✅ Kolom foto berhasil diubah ke VARCHAR(255)\n";
    } else {
        echo "❌ Error: " . mysqli_error($conn) . "\n";
    }

    // Cek struktur tabel setelah perubahan
    echo "\n📋 Struktur tabel mahasiswa setelah perubahan:\n";
    $result = mysqli_query($conn, "DESCRIBE mahasiswa");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    }

    mysqli_close($conn);
} else {
    echo "❌ Koneksi gagal: " . mysqli_connect_error() . "\n";
}
