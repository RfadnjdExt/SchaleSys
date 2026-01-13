<?php
include 'koneksi.php';

$queries = [
    "ALTER TABLE mata_kuliah ADD COLUMN hari VARCHAR(10) NULL AFTER semester",
    "ALTER TABLE mata_kuliah ADD COLUMN jam_mulai TIME NULL AFTER hari",
    "ALTER TABLE mata_kuliah ADD COLUMN jam_selesai TIME NULL AFTER jam_mulai",
    "ALTER TABLE mata_kuliah ADD COLUMN ruangan VARCHAR(50) NULL AFTER jam_selesai"
];

try {
    $driver = $koneksi->getAttribute(PDO::ATTR_DRIVER_NAME);

    foreach ($queries as $sql) {
        // Postgres does not support 'AFTER column_name' in ALTER TABLE ADD COLUMN
        if ($driver == 'pgsql') {
            $sql = preg_replace('/ AFTER \w+/', '', $sql);
        }

        try {
            $koneksi->exec($sql);
            echo "Success executing: $sql\n";
        } catch (PDOException $e) {
            // Ignore "Duplicate column name" error (SQLSTATE 42701 in Pg, 42S21 in MySQL approx)
            // Or just check message
            echo "Error executing: $sql - " . $e->getMessage() . "\n";
        }
    }

    // Verify the new structure
    echo "\nNew Table Structure:\n";
    if ($driver == 'pgsql') {
        $stmt = $koneksi->prepare("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'mata_kuliah' AND table_schema = 'public'");
    } else {
        $stmt = $koneksi->prepare("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'mata_kuliah' AND table_schema = DATABASE()");
    }
    
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['column_name'] . " - " . $row['data_type'] . "\n";
    }

} catch (PDOException $e) {
    echo "Fatal Error: " . $e->getMessage();
}
?>
