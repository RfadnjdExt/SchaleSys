<?php
// import_stella_sora.php
// Script khusus untuk mengimpor data karakter Stella Sora dari file stella_sora.json
// Jalankan via CLI: c:\xampp\php\php.exe import_stella_sora.php

if (php_sapi_name() !== 'cli') {
    die("Script ini hanya boleh dijalankan melalui CLI.\n");
}

include 'koneksi.php';

echo "=== Import Data Stella Sora ===\n";

$json_file = 'stella_sora.json';
if (!file_exists($json_file)) {
    die("Error: File $json_file tidak ditemukan. Silakan buat file tersebut terlebih dahulu.\n");
}

$json_data = file_get_contents($json_file);
$characters = json_decode($json_data, true);

if (!$characters) {
    die("Error: Gagal decode JSON atau file kosong.\n");
}

echo "Ditemukan " . count($characters) . " data karakter.\n";

// Ambil NIM terakhir untuk melanjutkan sequence
$query_max = mysqli_query($koneksi, "SELECT MAX(CAST(nim AS UNSIGNED)) as max_nim FROM mahasiswa");
$row_max = mysqli_fetch_assoc($query_max);
$last_nim = $row_max['max_nim'];
if (!$last_nim) $last_nim = 2024000;

echo "Start NIM: " . ($last_nim + 1) . "\n";

$inserted = 0;
foreach ($characters as $char) {
    $last_nim++;
    $nim = $last_nim;
    $nama = mysqli_real_escape_string($koneksi, $char['nama']);
    $foto = mysqli_real_escape_string($koneksi, $char['foto']);
    $fakultas = 'Stella Sora';
    $prodi = 'Sistem Informasi'; // Default prodi untuk Stella Sora
    $angkatan = 2024;

    $sql = "INSERT INTO mahasiswa (nim, nama_mahasiswa, fakultas, prodi, angkatan, foto) 
            VALUES ('$nim', '$nama', '$fakultas', '$prodi', $angkatan, '$foto')";
    
    if (mysqli_query($koneksi, $sql)) {
        $inserted++;
    } else {
        echo "Gagal insert $nama: " . mysqli_error($koneksi) . "\n";
        $last_nim--; // Revert NIM increment if failed
    }
}

echo "Selesai! Berhasil mengimpor $inserted karakter baru.\n";
mysqli_close($koneksi);
?>
