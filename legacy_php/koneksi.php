<?php

// --- Konfigurasi Database --- //

// Nama host atau server database. 
// Untuk XAMPP, biasanya 'localhost'.
// --- Konfigurasi Database --- //

// Gunakan Environment Variables jika tersedia (untuk Production/Vercel)
// Jika tidak, gunakan default local XAMPP
// --- Konfigurasi Database --- //

$db_host = getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'localhost';
$db_user = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
$db_pass = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';
$db_name = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'db_akademik';
$db_port = getenv('DB_PORT') !== false ? getenv('DB_PORT') : 3306;

// Deteksi driver: Jika ada env DB_CONNECTION, pakai itu. Jika tidak, cek port (5432 usually pgsql). Default mysql.
$db_driver = getenv('DB_CONNECTION') ? getenv('DB_CONNECTION') : 'mysql';
if ($db_port == 5432 || $db_port == 6543) {
    $db_driver = 'pgsql';
}

// --- Membuat Koneksi PDO --- //

try {
    $dsn = "$db_driver:host=$db_host;port=$db_port;dbname=$db_name";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    
    // Alias $koneksi ke $pdo agar mudah diingat (tapi tipe datanya beda!)
    // Kita akan refactor kode lain untuk menggunakan $pdo
    $koneksi = $pdo; 
    
} catch (\PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}

// Jika Anda ingin melihat pesan sukses saat koneksi berhasil (opsional, bisa dihapus nanti).
// echo "Koneksi Berhasil!";

?>