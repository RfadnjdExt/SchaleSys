<?php
// update_names.php
// Script untuk mengubah semua nama mahasiswa menjadi karakter Trickcal (Chibi)
// Jalankan lewat CLI: C:\xampp\php\php.exe update_names.php

if (php_sapi_name() !== 'cli') {
    die("Script ini hanya boleh dijalankan melalui CLI.");
}

include 'koneksi.php';

// Daftar Nama Karakter Trickcal Revive (User Provided)
$raw_names = "Amelia       Asia       Aya       Barong       Benny       Caesar       Canta       Diana (Heyday)       Ed       Elena       Espi       Fricle       Gwynn       Jade       Kommy (Swimsuit)       Layze       Lethe       Meluna       Patula       Picora       Ricotta       Sylla       Velvet       Asana       Ashur       Barie       Blanchet       Chopi       Festa       Hilde       Joan       Kidian       Kommy       Leone       Levi       Orr       Posher       Rim       Risty       Rohne (Mayor)       Shasha       Snorky       Veroo       xXionx       Yomi       Allet       Ayla       Bigwood       Daya       Erpin       Gabia       Haley       Kathy       Kyarot       Kyuri       Mago       Mayo (Cool)       Mute       Naia       Opal       Raika       Ran       Rohne       Sari       Sherum       Silphir       Speaki       Vivi       Alice       Annette       Belita       Chloe       Diana       Heidi       Ifrit       Leets       MaestroMK2       Maison       Mayo       Ner       Neti       Pira       Polan       Renewa       Rim (Chaos)       Rollet       Shaydi       Sist       Tig (Hero)       Yumimi       Arco       Bana       Beni       Butter       Canna       Carren       Epica       Jubee       Makasha       Marie       Miro       Momo       Mynx       Rudd       Rufo       Selline       Shoupan       Shuro       Speaki (Maid)       Taida       Tig       Ui       Vela       Uros";

// Split by multiple spaces to separate names while keeping "Name (Variant)" together
$names = preg_split('/\s{2,}/', trim($raw_names));
$names = array_filter($names); // Remove empty entries if any

$total_data = 200000;
//$batch_size = 1000; // Not used for direct SQL update approach optimization

echo "Mulai update nama menjadi karakter Trickcal...\n";

// Cara cepat: Menggunakan SQL CASE atau ELT untuk update random secara massal
// Karena MySQL ELT punya limit argumen, kita akan loop update per record atau per batch kecil
// Tapi update 200k row satu per satu lama.
// Kita coba update random dari PHP dengan mengambil ID dulu? No, ID tidak urut (NIM string).
// Kita gunakan UPDATE tanpa WHERE tapi dengan fungsi random SQL jika memungkinkan.
// UPDATE mahasiswa SET nama_mahasiswa = ELT(FLOOR(1 + RAND() * 30), 'Vivi', 'Erbie'...)
// MySQL supports this!

// Construct SQL Update string
$elt_args = implode("', '", $names);
$count_names = count($names);

// Query: UPDATE mahasiswa SET nama_mahasiswa = ELT(FLOOR(1 + RAND() * count), 'Name1', 'Name2'...)
// Kita tambahkan variasi unik agar tidak duplikat parah (opsional, tapi user minta nama karakter)
// User: "ubah semua nama mahasiswa menjadi karakter trickcal chibi go"
// Kita akan tambahkan angka random atau string unik di belakang jika perlu, 
// tapi kalau 'menjadi karakter', mungkin mereka mau nama exact. 
// Mari kita buat variasi: "Vivi #1234" biar unik?
// Atau biarkan duplikat? 200k siswa dengan 30 nama = banyak duplikat.
// Kita gabungkan random name + random ID/Number untuk variasi.
// UPDATE mahasiswa SET nama_mahasiswa = CONCAT(ELT(..), ' ', FLOOR(RAND()*10000))

$sql = "UPDATE mahasiswa SET nama_mahasiswa = CONCAT(
            ELT(FLOOR(1 + RAND() * $count_names), '$elt_args'),
            ' (Chibi)'
        )";

// Note: RAND() di UPDATE dievaluasi per baris, jadi setiap baris dapat nama beda.
// Tapi CONCAT string ' (Chibi)' statis.

// Jalankan
$start_time = microtime(true);

if (mysqli_query($koneksi, $sql)) {
    $time = number_format(microtime(true) - $start_time, 2);
    echo "Sukses! Semua nama telah diubah. Waktu: $time detik.\n";
} else {
    echo "Gagal: " . mysqli_error($koneksi) . "\n";
}

mysqli_close($koneksi);
?>
