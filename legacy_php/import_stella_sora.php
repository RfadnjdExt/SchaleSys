<?php
// import_stella_sora.php
// Script khusus untuk mengimpor data karakter Stella Sora dari file stella_sora.json
// Jalankan via CLI: php import_stella_sora.php

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

try {
    $driver = $koneksi->getAttribute(PDO::ATTR_DRIVER_NAME);

    // Ambil NIM terakhir untuk melanjutkan sequence
    // CAST AS UNSIGNED exists in MySQL, PostgreSQL uses integer/bigint
    if ($driver == 'pgsql') {
        $sql_max = "SELECT MAX(CAST(nim AS BIGINT)) as max_nim FROM mahasiswa";
    } else {
        $sql_max = "SELECT MAX(CAST(nim AS UNSIGNED)) as max_nim FROM mahasiswa";
    }
    
    $query_max = $koneksi->query($sql_max);
    $last_nim = $query_max->fetch(PDO::FETCH_ASSOC)['max_nim'];
    
    if (!$last_nim) $last_nim = 2024000;

    echo "Start NIM: " . ($last_nim + 1) . "\n";

    $koneksi->beginTransaction();
    $inserted = 0;
    
    $stmt = $koneksi->prepare("INSERT INTO mahasiswa (nim, nama_mahasiswa, fakultas, prodi, angkatan, foto) 
                                VALUES (:nim, :nama, :fakultas, :prodi, :angkatan, :foto)");

    foreach ($characters as $char) {
        $last_nim++;
        $nim = $last_nim;
        
        $params = [
            ':nim' => $nim,
            ':nama' => $char['nama'],
            ':fakultas' => 'Stella Sora',
            ':prodi' => 'Sistem Informasi',
            ':angkatan' => 2024,
            ':foto' => $char['foto']
        ];

        try {
            if ($stmt->execute($params)) {
                $inserted++;
            }
        } catch (PDOException $e) {
             echo "Gagal insert " . $char['nama'] . ": " . $e->getMessage() . "\n";
             $last_nim--; // Revert NIM increment if failed
        }
    }

    $koneksi->commit();
    echo "Selesai! Berhasil mengimpor $inserted karakter baru.\n";
    
} catch (PDOException $e) {
    if ($koneksi->inTransaction()) {
        $koneksi->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
}
?>
