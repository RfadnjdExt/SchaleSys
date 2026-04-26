<?php
include 'koneksi.php';
$stmt = $koneksi->query("SELECT nim FROM mahasiswa ORDER BY nim DESC LIMIT 10");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['nim'] . "\n";
}
?>
