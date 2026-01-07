<?php
include 'koneksi.php';
$result = mysqli_query($koneksi, "SELECT nim FROM mahasiswa ORDER BY nim DESC LIMIT 10");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['nim'] . "\n";
}
?>
