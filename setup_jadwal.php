<?php
include 'koneksi.php';

// Array kolom yang akan ditambahkan beserta definisi tipenya
$columns = [
    'hari' => "VARCHAR(10) NULL COMMENT 'Senin, Selasa, dst'",
    'jam_mulai' => "TIME NULL",
    'jam_selesai' => "TIME NULL",
    'ruangan' => "VARCHAR(20) NULL"
];

$success_count = 0;

foreach ($columns as $col_name => $col_definition) {
    // Cek apakah kolom sudah ada
    $check_sql = "SHOW COLUMNS FROM mata_kuliah LIKE '$col_name'";
    $check_result = mysqli_query($koneksi, $check_sql);
    
    if (mysqli_num_rows($check_result) == 0) {
        // Jika belum ada, lakukan ALTER TABLE
        $alter_sql = "ALTER TABLE mata_kuliah ADD COLUMN $col_name $col_definition";
        if (mysqli_query($koneksi, $alter_sql)) {
            echo "Kolom '$col_name' berhasil ditambahkan.<br>";
            $success_count++;
        } else {
            echo "Error menambahkan kolom '$col_name': " . mysqli_error($koneksi) . "<br>";
        }
    } else {
        echo "Kolom '$col_name' sudah ada, melewati...<br>";
    }
}

if ($success_count > 0) {
    echo "<b>Selesai! Struktur tabel mata_kuliah telah diperbarui.</b>";
} else {
    echo "<b>Tidak ada perubahan yang dilakukan (mungkin kolom sudah ada semua).</b>";
}

mysqli_close($koneksi);
?>
