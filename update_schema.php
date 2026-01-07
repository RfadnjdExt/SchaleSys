<?php
include 'koneksi.php';

$queries = [
    "ALTER TABLE mata_kuliah ADD COLUMN hari VARCHAR(10) NULL AFTER semester",
    "ALTER TABLE mata_kuliah ADD COLUMN jam_mulai TIME NULL AFTER hari",
    "ALTER TABLE mata_kuliah ADD COLUMN jam_selesai TIME NULL AFTER jam_mulai",
    "ALTER TABLE mata_kuliah ADD COLUMN ruangan VARCHAR(50) NULL AFTER jam_selesai"
];

foreach ($queries as $sql) {
    if (mysqli_query($koneksi, $sql)) {
        echo "Success executing: $sql\n";
    } else {
        echo "Error executing: $sql - " . mysqli_error($koneksi) . "\n";
    }
}

// Verify the new structure
echo "\nNew Table Structure:\n";
$result = mysqli_query($koneksi, "DESCRIBE mata_kuliah");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

mysqli_close($koneksi);
?>
