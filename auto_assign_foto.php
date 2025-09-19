<?php
// Script untuk auto-assign foto ke semua data mahasiswa
$conn = mysqli_connect('localhost', 'root', '', 'php_dasar');

if ($conn) {
    echo "🔄 AUTO-ASSIGN FOTO KE SEMUA DATA MAHASISWA\n";
    echo "=" . str_repeat("=", 50) . "\n\n";

    // Mapping yang lebih lengkap berdasarkan nama yang ada di database dan foto tersedia
    $foto_assignments = [
        // Yang sudah ada (skip)
        'Andi Pratama' => 'Andi.jpg',      // sudah ada
        'Budi Santoso' => 'Budi.jpg',      // sudah ada
        'Dian Suryani' => 'Dian.jpg',      // sudah ada
        'Fajar Nugroho' => 'Fajar.jpg',    // sudah ada
        'Joko Susanto' => 'Joko.jpg',      // sudah ada
        'Rina Kurniasari' => 'Rina.jpg',   // sudah ada
        'Doni Kurniawan' => 'Doni.jpg',    // sudah ada

        // Yang belum ada - assign foto yang tersedia
        'Citra Lestari' => 'Siti.jpg',     // assign foto perempuan
        'Eka Putri' => 'Dewi.jpg',         // assign foto perempuan
        'Galih Saputra' => 'Agus.jpg',     // assign foto laki-laki
        'Hana Ramadhani' => 'Lia.jpg',     // assign foto perempuan
        'Indra Kurniawan' => 'Eko.jpg',    // assign foto laki-laki
        'Kartika Dewi' => 'Maya.jpg',      // assign foto perempuan
        'Lestari Ayu' => 'Lina.jpg',       // assign foto perempuan
        'Muhammad Rizky' => 'Rudi.jpg',    // assign foto laki-laki
        'Nur Aisyah' => 'Nita.jpg',        // assign foto perempuan
        'Oka Prasetyo' => 'Hadi.jpg',      // assign foto laki-laki
        'Putri Amelia' => 'Mega.png',      // assign foto perempuan
        'Qori Hidayat' => 'Bayu.jpg',      // assign foto laki-laki
        'Sigit Wibowo' => 'Dedi.jpg',      // assign foto laki-laki
        'Tania Safitri' => 'Yuni.jpg',     // assign foto perempuan
        'Rizky Ananda' => 'Fadly.png',     // assign foto laki-laki
        'Melati Safitri' => 'Rini.jpg',    // assign foto perempuan
        'Bagus Pratama' => 'Aris.png',     // assign foto laki-laki
        'Nabila Putri' => 'Sari.jpg'       // assign foto perempuan
    ];

    $updated = 0;
    $skipped = 0;
    $errors = 0;

    foreach ($foto_assignments as $nama => $foto) {
        // Cek apakah file foto ada
        if (!file_exists("img/" . $foto)) {
            echo "❌ File tidak ada: $foto\n";
            $errors++;
            continue;
        }

        // Cek apakah data mahasiswa ada dan belum punya foto
        $check_sql = "SELECT id, foto FROM mahasiswa WHERE nama = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $nama);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (!empty($row['foto'])) {
                echo "⚠️  Skip: $nama (sudah punya foto: {$row['foto']})\n";
                $skipped++;
            } else {
                // Update foto
                $update_sql = "UPDATE mahasiswa SET foto = ? WHERE nama = ?";
                $update_stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($update_stmt, "ss", $foto, $nama);

                if (mysqli_stmt_execute($update_stmt)) {
                    echo "✅ Assign: $nama -> $foto\n";
                    $updated++;
                } else {
                    echo "❌ Error update $nama: " . mysqli_error($conn) . "\n";
                    $errors++;
                }
                mysqli_stmt_close($update_stmt);
            }
        } else {
            echo "⚠️  Nama tidak ditemukan: $nama\n";
        }
        mysqli_stmt_close($check_stmt);
    }

    // Tampilkan ringkasan
    echo "\n📊 RINGKASAN AUTO-ASSIGN:\n";
    echo "-" . str_repeat("-", 30) . "\n";
    echo "✅ Berhasil di-assign: $updated\n";
    echo "⚠️  Dilewati (sudah ada): $skipped\n";
    echo "❌ Error: $errors\n";
    echo "📈 Total diproses: " . ($updated + $skipped + $errors) . "\n";

    // Cek hasil akhir
    echo "\n🔍 STATISTIK SETELAH AUTO-ASSIGN:\n";
    echo "-" . str_repeat("-", 30) . "\n";

    $total_result = mysqli_query($conn, 'SELECT COUNT(*) as total FROM mahasiswa');
    $total_row = mysqli_fetch_assoc($total_result);
    $total_data = $total_row['total'];

    $foto_result = mysqli_query($conn, 'SELECT COUNT(*) as ada_foto FROM mahasiswa WHERE foto IS NOT NULL AND foto != ""');
    $foto_row = mysqli_fetch_assoc($foto_result);
    $ada_foto = $foto_row['ada_foto'];

    $kosong_foto = $total_data - $ada_foto;

    echo "Total mahasiswa: $total_data\n";
    echo "Punya foto: $ada_foto\n";
    echo "Masih kosong: $kosong_foto\n";
    echo "Persentase punya foto: " . round(($ada_foto / $total_data) * 100, 1) . "%\n";

    mysqli_close($conn);
} else {
    echo "❌ Koneksi gagal: " . mysqli_connect_error() . "\n";
}
