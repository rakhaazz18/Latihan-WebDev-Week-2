<?php
// Script untuk testing display foto
$conn = mysqli_connect('localhost', 'root', '', 'php_dasar');

if ($conn) {
    echo "🔍 Testing display foto...\n\n";

    // Ambil data mahasiswa yang memiliki foto
    $result = mysqli_query($conn, 'SELECT id, nama, foto FROM mahasiswa WHERE foto IS NOT NULL AND foto != "" LIMIT 5');
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $foto_path = "img/" . $row['foto'];
            echo "Mahasiswa: " . $row['nama'] . "\n";
            echo "File foto: " . $row['foto'] . "\n";
            echo "Path: " . $foto_path . "\n";
            echo "File exists: " . (file_exists($foto_path) ? "✅ YA" : "❌ TIDAK") . "\n";
            if (file_exists($foto_path)) {
                echo "File size: " . filesize($foto_path) . " bytes\n";
            }
            echo "-" . str_repeat("-", 30) . "\n";
        }
    } else {
        echo "❌ Tidak ada data dengan foto\n";
    }

    mysqli_close($conn);
} else {
    echo "❌ Koneksi gagal: " . mysqli_connect_error() . "\n";
}

// Test HTML preview untuk foto
echo "\n🖼️  HTML Preview untuk foto yang ada:\n";
echo "<html><body>\n";
echo "<h3>Test Display Foto</h3>\n";

$conn = mysqli_connect('localhost', 'root', '', 'php_dasar');
if ($conn) {
    $result = mysqli_query($conn, 'SELECT id, nama, foto FROM mahasiswa WHERE foto IS NOT NULL AND foto != "" LIMIT 3');
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (file_exists("img/" . $row['foto'])) {
                echo "<p>" . $row['nama'] . ":<br>\n";
                echo '<img src="img/' . htmlspecialchars($row['foto']) . '" style="width:50px;height:50px;object-fit:cover;border-radius:5px;" alt="' . htmlspecialchars($row['nama']) . '">' . "\n";
                echo "</p>\n";
            }
        }
    }
    mysqli_close($conn);
}
echo "</body></html>\n";
