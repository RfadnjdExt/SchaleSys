<?php
// update_population.php
// Script untuk mengisi data mahasiswa dengan gabungan karakter Trickcal Revive dan Blue Archive
// Jalankan via CLI: php update_population.php

if (php_sapi_name() !== 'cli') {
    die("Script ini hanya boleh dijalankan melalui CLI untuk alasan keamanan dan timeout.\n");
}

include 'koneksi.php';

echo "=== Memulai Proses Update Populasi Mahasiswa ===\n";

// 1. Ambil Data Trickcal Revive dari file update_photos.php yang sudah ada
//    Kita baca filenya dan ambil string JSON-nya agar tidak perlu tulis ulang 100+ baris data.
$trickcal_source = file_get_contents('update_photos.php');
// Cari string di antara $json_data = ' dan ';
if (preg_match('/\$json_data\s*=\s*\'(.*?)\';/s', $trickcal_source, $matches)) {
    $trickcal_raw = json_decode($matches[1], true);
    if (!$trickcal_raw) die("Gagal decode JSON Trickcal dari update_photos.php\n");
} else {
    die("Gagal menemukan variabel \$json_data di update_photos.php (Mungkin file sudah direfactor? Cek kembali format)\n");
}

// Konversi format Trickcal ke format standar: ['nama', 'foto', 'fakultas']
$all_characters = [];
foreach ($trickcal_raw as $name => $url) {
    $all_characters[] = [
        'nama' => $name,
        'foto' => $url,
        'fakultas' => 'Trickcal Revive'
    ];
}
echo "[OK] " . count($all_characters) . " Karakter Trickcal dimuat.\n";

// 2. Ambil Data Blue Archive dari file blue_archive.json (User yang buat)
$ba_file = 'blue_archive.json';
if (file_exists($ba_file)) {
    $ba_json = file_get_contents($ba_file);
    $ba_raw = json_decode($ba_json, true);
    
    if ($ba_raw) {
        foreach ($ba_raw as $char) {
            $all_characters[] = [
                'nama' => $char['nama'],
                'foto' => $char['foto'],
                'fakultas' => 'Blue Archive' // Set fakultas sesuai game
            ];
        }
        echo "[OK] " . count($ba_raw) . " Karakter Blue Archive dimuat.\n";
    } else {
        echo "[WARNING] Gagal decode blue_archive.json. Pastikan format JSON valid.\n";
    }
} else {
    echo "[WARNING] File blue_archive.json tidak ditemukan. Hanya menggunakan data Trickcal.\n";
}

$total_pool = count($all_characters);
echo "Total Pool Karakter: $total_pool\n";
if ($total_pool == 0) die("Tidak ada data karakter untuk di-update.\n");


// 3. Ambil semua NIM mahasiswa
try {
    $stmt_mhs = $koneksi->query("SELECT nim FROM mahasiswa");
    $mahasiswa_list = $stmt_mhs->fetchAll(PDO::FETCH_COLUMN); // Ambil array 1 dimensi NIM
    $total_mhs = count($mahasiswa_list);
    echo "Total Mahasiswa Aktif: $total_mhs\n";

    // 4. Proses Update
    echo "Sedang mengupdate database... (Harap tunggu)\n";
    $start_time = microtime(true);
    $updated_count = 0;

    $koneksi->beginTransaction();
    
    $sql_update = "UPDATE mahasiswa SET 
                   nama_mahasiswa = :nama,
                   foto = :foto,
                   fakultas = :fakultas
                   WHERE nim = :nim";
    $stmt_update = $koneksi->prepare($sql_update);

    foreach ($mahasiswa_list as $nim) {
        // Pilih karakter random
        $random_char = $all_characters[array_rand($all_characters)];
        
        $params = [
            ':nama' => $random_char['nama'],
            ':foto' => $random_char['foto'],
            ':fakultas' => $random_char['fakultas'],
            ':nim' => $nim
        ];
        
        if ($stmt_update->execute($params)) {
            $updated_count++;
        }
        
        // Progress batch commit (optional untuk memori, tapi transaksi 200k masih oke di DB modern)
        if ($updated_count % 5000 == 0) {
              echo "Updated $updated_count...\r";
        }
    }

    $koneksi->commit();

    $duration = number_format(microtime(true) - $start_time, 2);
    echo "\n=== SELESAI ===\n";
    echo "Berhasil mengupdate $updated_count dari $total_mhs mahasiswa.\n";
    echo "Waktu eksekusi: $duration detik.\n";

} catch (PDOException $e) {
    if ($koneksi->inTransaction()) {
        $koneksi->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
}

// Tutup koneksi otomatis
?>
