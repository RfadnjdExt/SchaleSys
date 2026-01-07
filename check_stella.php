<?php
include 'koneksi.php';
$result = mysqli_query($koneksi, "SELECT nim, nama_mahasiswa, foto FROM mahasiswa WHERE fakultas='Stella Sora' ORDER BY nim");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['nim'] . " - " . $row['nama_mahasiswa'] . " - " . $row['foto'] . "\n";
}
?>
