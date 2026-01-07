<?php
include 'koneksi.php';
$result = mysqli_query($koneksi, "SELECT nama_mahasiswa, fakultas, foto FROM mahasiswa ORDER BY RAND() LIMIT 5");
echo "--- SAMPLE DATA MAHASISWA ---\n";
while ($row = mysqli_fetch_assoc($result)) {
    echo "Nama: " . $row['nama_mahasiswa'] . "\n";
    echo "Fakultas: " . $row['fakultas'] . "\n";
    echo "Foto: " . substr($row['foto'], 0, 50) . "...\n";
    echo "---------------------------\n";
}

$count_ba = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM mahasiswa WHERE fakultas='Blue Archive'"))['c'];
$count_tr = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM mahasiswa WHERE fakultas='Trickcal Revive'"))['c'];
$count_ss = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM mahasiswa WHERE fakultas='Stella Sora'"))['c'];

echo "Total Blue Archive: $count_ba\n";
echo "Total Trickcal Revive: $count_tr\n";
echo "Total Stella Sora: $count_ss\n";
?>
