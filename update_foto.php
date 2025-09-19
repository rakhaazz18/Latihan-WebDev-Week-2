<?php
// Script untuk mengupdate data foto berdasarkan nama mahasiswa
$conn = mysqli_connect('localhost', 'root', '', 'php_dasar');

if ($conn) {
    echo "🔄 Mengupdate foto mahasiswa berdasarkan nama...\n\n";

    // Mapping nama mahasiswa dengan foto yang tersedia
    $foto_mapping = [
        'Andi Pratama' => 'Andi.jpg',
        'Budi Santoso' => 'Budi.jpg',
        'Dian Suryani' => 'Dian.jpg',
        'Fajar Nugroho' => 'Fajar.jpg',
        'Joko Susanto' => 'Joko.jpg',
        'Rina Kurniasari' => 'Rina.jpg',
        'Doni Kurniawan' => 'Doni.jpg'
    ];

    $updated = 0;

    foreach ($foto_mapping as $nama => $foto) {
        // Cek apakah file foto ada
        if (file_exists("img/" . $foto)) {
            $sql = "UPDATE mahasiswa SET foto = ? WHERE nama = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $foto, $nama);

            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    echo "✅ Updated: $nama -> $foto\n";
                    $updated++;
                } else {
                    echo "⚠️  Tidak ada data: $nama\n";
                }
            } else {
                echo "❌ Error update $nama: " . mysqli_error($conn) . "\n";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "❌ File tidak ada: $foto\n";
        }
    }

    echo "\n📊 Total data yang diupdate: $updated\n";

    // Tampilkan hasil update
    echo "\n🔍 Data setelah update:\n";
    echo "=" . str_repeat("=", 50) . "\n";
    $result = mysqli_query($conn, 'SELECT id, nama, foto FROM mahasiswa WHERE foto IS NOT NULL AND foto != ""');
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: " . $row['id'] . " | " . $row['nama'] . " | " . $row['foto'] . "\n";
        }
    } else {
        echo "Belum ada data dengan foto\n";
    }

    mysqli_close($conn);
} else {
    echo "❌ Koneksi gagal: " . mysqli_connect_error() . "\n";
}
