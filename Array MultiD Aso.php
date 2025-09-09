<!DOCTYPE html>
<head>
<body>
    <h1>Daftar Mahasiswa</h1>
    <ul>
        <?php foreach($mahasiswa as $mhs): ?>
            <li>
                <a href ="detailmhs.php?nama=<?php echo $mhs["nama"] . " - " . $mhs[1] . " - " . $mhs["nama"]; ?>">
                    <?php echo $mhs["nama"] . " - " . $mhs[1] . " - " . $mhs[2]; ?>
                </a>
            </li>
        <?php endforeach; ?>    
    </ul>
</body>
</head>