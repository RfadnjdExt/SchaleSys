<?php
include 'koneksi.php';

echo "Cleaning up placeholders...\n";
$sql = "DELETE FROM mahasiswa WHERE fakultas='Stella Sora' AND (foto LIKE '%placehold%' OR nama_mahasiswa LIKE '%Isi Nama%')";
if (mysqli_query($koneksi, $sql)) {
    echo "Deleted " . mysqli_affected_rows($koneksi) . " placeholder rows.\n";
} else {
    echo "Error: " . mysqli_error($koneksi) . "\n";
}
mysqli_close($koneksi);
?>
