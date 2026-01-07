<?php
include 'koneksi.php';

// 1. Buat Tabel semester_config
$sql_table = "CREATE TABLE IF NOT EXISTS semester_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_semester VARCHAR(50) NOT NULL UNIQUE,
    is_aktif TINYINT(1) DEFAULT 0
)";

if (mysqli_query($koneksi, $sql_table)) {
    echo "Tabel 'semester_config' berhasil dibuat/diperiksa.<br>";
} else {
    die("Error membuat tabel: " . mysqli_error($koneksi));
}

// 2. Insert Data Awal (Ganjil 2024/2025) jika belum ada
$sql_check = "SELECT * FROM semester_config";
$result_check = mysqli_query($koneksi, $sql_check);

if (mysqli_num_rows($result_check) == 0) {
    // Insert dan set sebagai aktif
    $sql_insert = "INSERT INTO semester_config (nama_semester, is_aktif) VALUES ('Ganjil 2024/2025', 1)";
    if (mysqli_query($koneksi, $sql_insert)) {
        echo "Data semester awal 'Ganjil 2024/2025' berhasil ditambahkan dan diaktifkan.<br>";
    } else {
        echo "Error insert data: " . mysqli_error($koneksi);
    }
} else {
    echo "Data semester sudah ada, tidak perlu insert awal.<br>";
}

mysqli_close($koneksi);
?>
