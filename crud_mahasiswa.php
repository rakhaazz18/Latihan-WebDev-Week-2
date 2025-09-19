<?php
// Aktifkan laporan error untuk debugging (bisa dimatikan nanti)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke database (ubah sesuai database Anda: php_dasar)
$conn = mysqli_connect("localhost", "root", "", "php_dasar");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$message = ""; // pesan status operasi

// CREATE
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $foto = mysqli_real_escape_string($conn, $_POST['foto']);
    $sql = "INSERT INTO mahasiswa (nama, nim, jurusan, email, foto) VALUES ('$nama', '$nim', '$jurusan', '$email', '$foto')";
    if (mysqli_query($conn, $sql)) {
        $message = "Data berhasil ditambahkan.";
    } else {
        $message = "Gagal tambah: " . mysqli_error($conn);
    }
}

// UPDATE
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $foto = mysqli_real_escape_string($conn, $_POST['foto']);
    $sql = "UPDATE mahasiswa SET nama='$nama', nim='$nim', jurusan='$jurusan', email='$email', foto='$foto' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $message = "Data ID $id berhasil diupdate.";
    } else {
        $message = "Gagal update: " . mysqli_error($conn);
    }
}

// DELETE
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $sql = "DELETE FROM mahasiswa WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $message = "Data ID $id berhasil dihapus.";
    } else {
        $message = "Gagal hapus: " . mysqli_error($conn);
    }
}

// DELETE FOTO SAJA (tidak hapus data mahasiswa)
if (isset($_GET['hapus_foto'])) {
    $id = (int)$_GET['hapus_foto'];
    $sql = "UPDATE mahasiswa SET foto = NULL WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $message = "Foto mahasiswa ID $id berhasil dihapus.";
    } else {
        $message = "Gagal hapus foto: " . mysqli_error($conn);
    }
}

// DELETE MASSAL
if (isset($_POST['hapus_massal']) && isset($_POST['selected_ids'])) {
    $selected_ids = $_POST['selected_ids'];
    $ids = implode(',', array_map('intval', $selected_ids));
    $sql = "DELETE FROM mahasiswa WHERE id IN ($ids)";
    if (mysqli_query($conn, $sql)) {
        $count = count($selected_ids);
        $message = "$count data mahasiswa berhasil dihapus.";
    } else {
        $message = "Gagal hapus massal: " . mysqli_error($conn);
    }
}

// READ + hitung jumlah data
$result = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY id DESC");
$jumlah = mysqli_num_rows($result);

// Jika ingin edit, ambil data lama
$edit = false;
$editData = ["id" => "", "nama" => "", "nim" => "", "jurusan" => "", "email" => ""];
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $q = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
    if ($row = mysqli_fetch_assoc($q)) {
        $edit = true;
        $editData = $row;
    } else {
        $message = "Data dengan ID $id tidak ditemukan.";
    }
}

// Mode test cepat: jika ?test=1 tampilkan JSON (bisa untuk cek koneksi via browser)
if (isset($_GET['test'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'koneksi' => $conn ? 'ok' : 'gagal',
        'jumlah_data' => $jumlah,
        'sample' => mysqli_fetch_assoc(mysqli_query($conn, 'SELECT * FROM mahasiswa LIMIT 1'))
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>CRUD Mahasiswa</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
        }
    </style>
</head>

<body>
    <h2>CRUD Mahasiswa</h2>
    <?php if ($message) { ?>
        <div style="padding:6px;margin-bottom:10px;border:1px solid #999;background:#f5f5f5;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php } ?>
    <div style="font-size:14px;margin-bottom:8px;">Total data: <b><?= $jumlah ?></b></div>
    <form method="post">
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        Nama: <input type="text" name="nama" value="<?= $editData['nama'] ?>" required><br><br>
        NIM: <input type="text" name="nim" value="<?= $editData['nim'] ?>" required><br><br>
        Jurusan: <input type="text" name="jurusan" value="<?= $editData['jurusan'] ?>" required><br><br>
        Email: <input type="email" name="email" value="<?= $editData['email'] ?>" required><br><br>
        Foto:
        <select name="foto">
            <option value="">-- Pilih Foto --</option>
            <?php
            $foto_dir = "img/";
            if (is_dir($foto_dir)) {
                $files = scandir($foto_dir);
                foreach ($files as $file) {
                    if ($file != "." && $file != ".." && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
                        $selected = ($editData['foto'] == $file) ? 'selected' : '';
                        echo "<option value='$file' $selected>$file</option>";
                    }
                }
            }
            ?>
        </select>
        <br><br>
        <?php if ($edit) { ?>
            <button type="submit" name="update">Update</button>
            <a href="?">Batal</a>
        <?php } else { ?>
            <button type="submit" name="tambah">Tambah</button>
        <?php } ?>
    </form>
    <br>

    <!-- Form untuk hapus massal -->
    <form method="post" id="formHapusMassal">
        <div style="margin-bottom:10px;">
            <button type="button" onclick="selectAll()" style="background:#007bff;color:white;padding:5px 10px;border:none;cursor:pointer;">Pilih Semua</button>
            <button type="button" onclick="deselectAll()" style="background:#6c757d;color:white;padding:5px 10px;border:none;cursor:pointer;">Batal Pilih</button>
            <button type="submit" name="hapus_massal" onclick="return confirmHapusMassal()" style="background:#dc3545;color:white;padding:5px 10px;border:none;cursor:pointer;">🗑️ Hapus Terpilih</button>
        </div>

        <table>
            <tr>
                <th><input type="checkbox" id="checkAll" onchange="toggleAll(this)"></th>
                <th>No</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Jurusan</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
            <?php $no = 1;
            if ($jumlah === 0) { ?>
                <tr>
                    <td colspan="8" style="text-align:center;">Belum ada data.</td>
                </tr>
            <?php }
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><input type="checkbox" name="selected_ids[]" value="<?= $row['id'] ?>" class="checkbox-item"></td>
                    <td><?= $no++ ?></td>
                    <td>
                        <?php if (!empty($row['foto']) && file_exists("img/" . $row['foto'])) { ?>
                            <img src="img/<?= htmlspecialchars($row['foto']) ?>"
                                alt="Foto <?= htmlspecialchars($row['nama']) ?>"
                                style="width:50px;height:50px;object-fit:cover;border-radius:5px;"><br>
                            <small>
                                <a href="?hapus_foto=<?= $row['id'] ?>"
                                    onclick="return confirm('Yakin hapus foto ini?')"
                                    style="color:red;font-size:10px;">Hapus Foto</a>
                            </small>
                        <?php } else { ?>
                            <span style="color:#999;">Tidak ada foto</span>
                        <?php } ?>
                    </td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['nim']) ?></td>
                    <td><?= htmlspecialchars($row['jurusan']) ?></td>
                    <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                    <td>
                        <a href="?edit=<?= $row['id'] ?>" style="color:blue;">✏️ Edit</a> |
                        <a href="?hapus=<?= $row['id'] ?>"
                            onclick="return confirm('⚠️ PERINGATAN!\n\nAnda akan menghapus data mahasiswa:\n• Nama: <?= htmlspecialchars($row['nama']) ?>\n• NIM: <?= htmlspecialchars($row['nim']) ?>\n\nData yang dihapus tidak dapat dikembalikan!\nYakin ingin melanjutkan?')"
                            style="color:red;">🗑️ Hapus Data</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </form>

    <script>
        function toggleAll(source) {
            var checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        function selectAll() {
            var checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            var checkAll = document.getElementById('checkAll');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = true;
            }
            checkAll.checked = true;
        }

        function deselectAll() {
            var checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            var checkAll = document.getElementById('checkAll');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = false;
            }
            checkAll.checked = false;
        }

        function confirmHapusMassal() {
            var selected = document.querySelectorAll('input[name="selected_ids[]"]:checked');
            if (selected.length === 0) {
                alert('Pilih minimal 1 data untuk dihapus!');
                return false;
            }

            var names = [];
            selected.forEach(function(checkbox) {
                var row = checkbox.closest('tr');
                var nameCell = row.cells[3]; // Kolom nama (index 3)
                names.push(nameCell.textContent);
            });

            var confirmation = '⚠️ PERINGATAN HAPUS MASSAL!\n\n';
            confirmation += 'Anda akan menghapus ' + selected.length + ' data mahasiswa:\n\n';
            for (var i = 0; i < Math.min(names.length, 5); i++) {
                confirmation += '• ' + names[i] + '\n';
            }
            if (names.length > 5) {
                confirmation += '• ... dan ' + (names.length - 5) + ' data lainnya\n';
            }
            confirmation += '\nData yang dihapus tidak dapat dikembalikan!\nYakin ingin melanjutkan?';

            return confirm(confirmation);
        }
    </script>
    <br>
</body>

</html>