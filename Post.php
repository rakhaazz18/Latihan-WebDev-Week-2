<?php
$greeting = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nama"])) {
    $nama = htmlspecialchars($_POST["nama"]);
    $greeting = "Selamat datang $nama";
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Selamat Datang</title>
</head>

<body>
    <?php if ($greeting) {
        echo "<h2>$greeting</h2>";
    } ?>
    <form method="post" action="">
        <label for="nama">Masukkan Nama:</label>
        <input type="text" id="nama" name="nama" >
        <button type="submit">Kirim</button>
    </form>
    <br>
</body>

</html>