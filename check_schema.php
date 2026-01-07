<?php
include 'koneksi.php';
$result = mysqli_query($koneksi, "DESCRIBE mata_kuliah");
while ($row = mysqli_fetch_assoc($result)) {
    print_r($row);
    echo "\n";
}
?>
