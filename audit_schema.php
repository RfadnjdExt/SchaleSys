<?php
include 'koneksi.php';

$tables = ['users', 'mahasiswa', 'dosen', 'mata_kuliah', 'krs', 'nilai', 'dosen_pengampu', 'semester_config'];

echo "=== DATABASE SCHEMA SNAPSHOT ===\n";

try {
    // Gunakan information_schema agar kompatibel dengan MySQL dan PostgreSQL
    $stmt = $koneksi->prepare("SELECT column_name, data_type, character_maximum_length 
                               FROM information_schema.columns 
                               WHERE table_name = :table AND table_schema = DATABASE()"); // MySQL
                               
    // Untuk PostgreSQL, DATABASE() tidak ada, pakai current_schema() atau public
    // Kita cek driver dulu
    $driver = $koneksi->getAttribute(PDO::ATTR_DRIVER_NAME);
    
    foreach ($tables as $table) {
        echo "\nTABLE: $table\n";
        
        if ($driver == 'pgsql') {
            $sql = "SELECT column_name, data_type 
                    FROM information_schema.columns 
                    WHERE table_name = :table AND table_schema = 'public'";
        } else {
             $sql = "SELECT column_name, data_type 
                     FROM information_schema.columns 
                     WHERE table_name = :table AND table_schema = DATABASE()";
        }
        
        $stmt_col = $koneksi->prepare($sql);
        $stmt_col->execute([':table' => $table]);
        $columns = $stmt_col->fetchAll(PDO::FETCH_ASSOC);

        if (count($columns) > 0) {
            foreach ($columns as $row) {
                echo "  - " . $row['column_name'] . " (" . $row['data_type'] . ")\n";
            }
        } else {
            // Fallback try DESCRIBE for MySQL if standard fails (unlikely) or just not found
             echo "  [Table not found or no columns]\n";
        }
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
