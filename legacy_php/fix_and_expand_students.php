<?php
// fix_and_expand_students.php
// Script untuk MEMPERBAIKI data (Restore Trickcal) dan MENAMBAH data (Insert Blue Archive)
// Jalankan via CLI: c:\xampp\php\php.exe fix_and_expand_students.php

if (php_sapi_name() !== 'cli') {
    die("Script ini hanya boleh dijalankan melalui CLI.\n");
}

include 'koneksi.php';

echo "=== Memulai Proses Perbaikan & Penambahan Mahasiswa ===\n";

// --- 1. Load Data Trickcal (TCG) ---
$trickcal_source = file_get_contents('update_photos.php');
if (preg_match('/\$json_data\s*=\s*\'(.*?)\';/s', $trickcal_source, $matches)) {
    $trickcal_raw = json_decode($matches[1], true);
} else {
    die("Gagal load data Trickcal.\n");
}

$list_tcg = [];
foreach ($trickcal_raw as $name => $url) {
    $list_tcg[] = [
        'nama' => $name,
        'foto' => $url,
        'fakultas' => 'Trickcal Revive',
        'prodi' => 'Teknik Informatika', // Default
        'angkatan' => 2024
    ];
}
echo "[Data] Trickcal Revive: " . count($list_tcg) . " karakter.\n";

// --- 2. Load Data Blue Archive (BA) ---
$ba_file = 'blue_archive.json';
if (!file_exists($ba_file)) die("File blue_archive.json tidak ditemukan.\n");

$ba_json = file_get_contents($ba_file);
$ba_raw = json_decode($ba_json, true);
$list_ba = [];
if ($ba_raw) {
    foreach ($ba_raw as $char) {
        $list_ba[] = [
            'nama' => $char['nama'],
            'foto' => $char['foto'],
            'fakultas' => 'Blue Archive',
            'prodi' => 'Ilmu Komputer', // Beda prodi biar variasi
            'angkatan' => 2024
        ];
    }
}
echo "[Data] Blue Archive: " . count($list_ba) . " karakter.\n";

// --- 3. Load Data Stella Sora (SS) ---
$ss_file = 'stella_sora.json';
$list_ss = [];
if (file_exists($ss_file)) {
    $ss_json = file_get_contents($ss_file);
    $ss_raw = json_decode($ss_json, true);
    if ($ss_raw) {
        foreach ($ss_raw as $char) {
            $list_ss[] = [
                'nama' => $char['nama'],
                'foto' => $char['foto'],
                'fakultas' => 'Stella Sora',
                'prodi' => 'Sistem Informasi', // Variasi prodi
                'angkatan' => 2024
            ];
        }
    }
}
echo "[Data] Stella Sora: " . count($list_ss) . " karakter.\n";

// Gabung semua data target: TCG, BA, SS
$all_target_chars = array_merge($list_tcg, $list_ba, $list_ss);
$total_target = count($all_target_chars);
echo "[Total] Target Populasi: $total_target mahasiswa.\n\n";


// --- 3. Ambil Data Mahasiswa Existing (Database Real) ---
// Kita akan update data existing terlebih dahulu agar ID/FK (Nilai, KRS) tetap aman.
$query_mhs = mysqli_query($koneksi, "SELECT nim FROM mahasiswa ORDER BY nim ASC");
$existing_nims = [];
while ($row = mysqli_fetch_assoc($query_mhs)) {
    $existing_nims[] = $row['nim'];
}
$count_existing = count($existing_nims);
echo "[DB] Jumlah Mahasiswa Existing: $count_existing\n";

// Tentukan max NIM untuk generate NIM baru
// Asumsi NIM integer. Jika string, cast int.
$query_max = mysqli_query($koneksi, "SELECT MAX(CAST(nim AS UNSIGNED)) as max_nim FROM mahasiswa");
$row_max = mysqli_fetch_assoc($query_max);
$last_nim = $row_max['max_nim'];
if (!$last_nim) $last_nim = 2024000; 
echo "[DB] Last NIM: $last_nim\n\n";


// --- 4. Eksekusi Update & Insert ---
$db = $koneksi; // alias
$updated = 0;
$inserted = 0;

foreach ($all_target_chars as $index => $char) {
    
    $safe_nama = mysqli_real_escape_string($db, $char['nama']);
    $safe_foto = mysqli_real_escape_string($db, $char['foto']);
    $safe_fakultas = mysqli_real_escape_string($db, $char['fakultas']);
    $safe_prodi = mysqli_real_escape_string($db, $char['prodi']);
    $angkatan = $char['angkatan'];

    if ($index < $count_existing) {
        // --- CASE A: UPDATE Existing Row ---
        // Kita pakai slot NIM yang sudah ada (preserve Nilai/KRS)
        $target_nim = $existing_nims[$index];
        
        $sql = "UPDATE mahasiswa SET 
                nama_mahasiswa = '$safe_nama',
                foto = '$safe_foto',
                fakultas = '$safe_fakultas',
                prodi = '$safe_prodi'
                WHERE nim = '$target_nim'";
        
        if (mysqli_query($db, $sql)) {
            $updated++;
        } else {
            echo "Gagal Update NIM $target_nim: " . mysqli_error($db) . "\n";
        }

    } else {
        // --- CASE B: INSERT New Row ---
        // Generate NIM Baru
        $last_nim++;
        $new_nim = $last_nim;
        
        $sql = "INSERT INTO mahasiswa (nim, nama_mahasiswa, fakultas, prodi, angkatan, foto) 
                VALUES ('$new_nim', '$safe_nama', '$safe_fakultas', '$safe_prodi', $angkatan, '$safe_foto')";
        
        if (mysqli_query($db, $sql)) {
            $inserted++;
            if ($inserted % 50 == 0) echo "Inserted $inserted rows...\n";
        } else {
            // Jika duplicate key (karena race condition atau manual insert), coba increment lagi
            echo "Gagal Insert NIM $new_nim: " . mysqli_error($db) . "\n";
            $last_nim++; // Skip problematic ID
        }
    }
}

echo "\n=== SELESAI ===\n";
echo "Total Updated (Existing): $updated\n";
echo "Total Inserted (New): $inserted\n";
echo "Total Final Database: " . ($updated + $inserted) . " mahasiswa.\n";

mysqli_close($koneksi);
?>
