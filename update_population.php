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
    die("Gagal menemukan variabel \$json_data di update_photos.php\n");
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
$result = mysqli_query($koneksi, "SELECT nim FROM mahasiswa");
$mahasiswa_list = [];
while ($row = mysqli_fetch_assoc($result)) {
    $mahasiswa_list[] = $row['nim'];
}
$total_mhs = count($mahasiswa_list);
echo "Total Mahasiswa Aktif: $total_mhs\n";

// 4. Proses Update
echo "Sedang mengupdate database... (Harap tunggu)\n";
$start_time = microtime(true);
$updated_count = 0;

foreach ($mahasiswa_list as $nim) {
    // Pilih karakter random
    $random_char = $all_characters[array_rand($all_characters)];
    
    $clean_nama = mysqli_real_escape_string($koneksi, $random_char['nama']);
    $url_foto = mysqli_real_escape_string($koneksi, $random_char['foto']);
    $fakultas = mysqli_real_escape_string($koneksi, $random_char['fakultas']);
    $nim_escaped = mysqli_real_escape_string($koneksi, $nim);

    $sql_update = "UPDATE mahasiswa SET 
                   nama_mahasiswa = '$clean_nama',
                   foto = '$url_foto',
                   fakultas = '$fakultas'
                   WHERE nim = '$nim_escaped'";
    
    if (mysqli_query($koneksi, $sql_update)) {
        $updated_count++;
    } else {
        echo "Gagal update NIM $nim: " . mysqli_error($koneksi) . "\n";
    }
}

$duration = number_format(microtime(true) - $start_time, 2);
echo "=== SELESAI ===\n";
echo "Berhasil mengupdate $updated_count dari $total_mhs mahasiswa.\n";
echo "Waktu eksekusi: $duration detik.\n";

mysqli_close($koneksi);
?>
