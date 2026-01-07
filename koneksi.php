<?php

// --- Konfigurasi Database --- //

// Nama host atau server database. 
// Untuk XAMPP, biasanya 'localhost'.
$db_host = 'localhost';

// Username untuk mengakses database. 
// Default di XAMPP adalah 'root'.
$db_user = 'root';

// Password untuk user database. 
// Default di XAMPP kosong ('').
$db_pass = '';

// Nama database yang telah Anda buat.
$db_name = 'db_akademik';


// --- Membuat Koneksi --- //

// Membuat koneksi ke server MySQL menggunakan fungsi mysqli_connect().
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);


// --- Cek Koneksi --- //

// Memeriksa apakah koneksi berhasil atau gagal.
// Jika gagal, hentikan skrip dan tampilkan pesan error.
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// Jika Anda ingin melihat pesan sukses saat koneksi berhasil (opsional, bisa dihapus nanti).
// echo "Koneksi Berhasil!";

?>