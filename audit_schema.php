<?php
include 'koneksi.php';

$tables = ['users', 'mahasiswa', 'dosen', 'mata_kuliah', 'krs', 'nilai', 'dosen_pengampu', 'semester_config'];

echo "=== DATABASE SCHEMA SNAPSHOT ===\n";
foreach ($tables as $table) {
    echo "\nTABLE: $table\n";
    $result = mysqli_query($koneksi, "DESCRIBE $table");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        echo "  [Error: Table not found]\n";
    }
}
?>
