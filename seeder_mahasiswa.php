<?php
// seeder_mahasiswa.php
// Script untuk mengisi database dengan 200.000 data mahasiswa dummy
// Jalankan lewat CLI: C:\xampp\php\php.exe seeder_mahasiswa.php

if (php_sapi_name() !== 'cli') {
    die("Script ini hanya boleh dijalankan melalui CLI.");
}

include 'koneksi.php';

// Konfigurasi
$total_data = 200000;
$batch_size = 1000; // Insert 1000 baris per query
$prodi_list = ['Teknik Informatika', 'Sistem Informasi', 'Teknik Komputer', 'Manajemen Informatika'];
$start_time = microtime(true);

echo "Mulai seeding $total_data data mahasiswa...\n";

// Disable autocommit for speed
mysqli_autocommit($koneksi, false);

$inserted = 0;
while ($inserted < $total_data) {
    $values = [];
    
    for ($i = 0; $i < $batch_size && $inserted < $total_data; $i++) {
        $inserted++;
        
        // Generate Dummy Data
        $nim = "2024" . str_pad($inserted, 6, '0', STR_PAD_LEFT); // 2024000001 dst
        $nama = "Mahasiswa Dummy " . $inserted; // Nama unik sederhana
        $prodi = $prodi_list[array_rand($prodi_list)];
        $angkatan = rand(2020, 2024);
        
        // Escape string
        // Karena ini data dummy yg kita buat sendiri dan pasti aman (alphanumeric), 
        // real_escape_string mungkin overkill tapi good practice.
        // Untuk kecepatan di sini kita manual string construction saja karena polanya fix.
        
        $values[] = "('$nim', '$nama', '$prodi', '$angkatan')";
    }
    
    // Buat Query Batch
    $sql = "INSERT IGNORE INTO mahasiswa (nim, nama_mahasiswa, prodi, angkatan) VALUES " . implode(", ", $values);
    
    if (!mysqli_query($koneksi, $sql)) {
        echo "Error pada baris ke-$inserted: " . mysqli_error($koneksi) . "\n";
        exit;
    }
    
    // Progress
    if ($inserted % 10000 == 0) {
        $elapsed = number_format(microtime(true) - $start_time, 2);
        echo "Inserted $inserted data... ($elapsed sec)\r";
    }
}

// Commit transaksi
mysqli_commit($koneksi);

$total_time = number_format(microtime(true) - $start_time, 2);
echo "\nSelesai! $inserted data berhasil ditambahkan dalam $total_time detik.\n";

mysqli_close($koneksi);
?>
