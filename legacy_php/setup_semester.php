<?php
include 'koneksi.php';

try {
    $driver = $koneksi->getAttribute(PDO::ATTR_DRIVER_NAME);
    
    // 1. Buat Tabel semester_config
    if ($driver == 'pgsql') {
        $sql_table = "CREATE TABLE IF NOT EXISTS semester_config (
            id SERIAL PRIMARY KEY,
            nama_semester VARCHAR(50) NOT NULL UNIQUE,
            is_aktif SMALLINT DEFAULT 0
        )";
    } else {
        $sql_table = "CREATE TABLE IF NOT EXISTS semester_config (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama_semester VARCHAR(50) NOT NULL UNIQUE,
            is_aktif TINYINT(1) DEFAULT 0
        )";
    }

    $koneksi->exec($sql_table);
    echo "Tabel 'semester_config' berhasil dibuat/diperiksa.<br>";

    // 2. Insert Data Awal (Ganjil 2024/2025) jika belum ada
    $sql_check = "SELECT COUNT(*) FROM semester_config";
    $stmt_check = $koneksi->query($sql_check);
    $count = $stmt_check->fetchColumn();

    if ($count == 0) {
        // Insert dan set sebagai aktif
        $sql_insert = "INSERT INTO semester_config (nama_semester, is_aktif) VALUES ('Ganjil 2024/2025', 1)";
        $koneksi->exec($sql_insert);
        echo "Data semester awal 'Ganjil 2024/2025' berhasil ditambahkan dan diaktifkan.<br>";
    } else {
        echo "Data semester sudah ada, tidak perlu insert awal.<br>";
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
