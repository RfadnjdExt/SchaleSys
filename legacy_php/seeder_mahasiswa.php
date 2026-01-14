<?php
// seeder_mahasiswa.php
// Script untuk mengisi database dengan 200.000 data mahasiswa dummy
// Jalankan lewat CLI: php seeder_mahasiswa.php

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

try {
    // Disable autocommit for speed via Transaction
    $koneksi->beginTransaction();

    $driver = $koneksi->getAttribute(PDO::ATTR_DRIVER_NAME);

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
            
            // String construction for speed (Safe for dummy data)
            $values[] = "('$nim', '$nama', '$prodi', '$angkatan')";
        }
        
        // Buat Query Batch
        $value_string = implode(", ", $values);
        
        if ($driver == 'pgsql') {
            $sql = "INSERT INTO mahasiswa (nim, nama_mahasiswa, prodi, angkatan) VALUES $value_string ON CONFLICT (nim) DO NOTHING";
        } else {
            $sql = "INSERT IGNORE INTO mahasiswa (nim, nama_mahasiswa, prodi, angkatan) VALUES $value_string";
        }
        
        // Execute Batch
        $koneksi->exec($sql);
        
        // Progress
        if ($inserted % 10000 == 0) {
            $elapsed = number_format(microtime(true) - $start_time, 2);
            echo "Inserted $inserted data... ($elapsed sec)\r";
        }
    }

    // Commit transaksi
    $koneksi->commit();

    $total_time = number_format(microtime(true) - $start_time, 2);
    echo "\nSelesai! $inserted data berhasil ditambahkan dalam $total_time detik.\n";

} catch (PDOException $e) {
    if ($koneksi->inTransaction()) {
        $koneksi->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
}
?>
