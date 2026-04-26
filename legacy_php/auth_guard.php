<?php
// 1. Mulai session
// Ini harus dipanggil sebelum pengecekan session.
session_start();

// 2. Cek login dasar
// Jika TIDAK ada session 'username', berarti pengguna belum login.
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    
    // "Tendang" pengguna kembali ke halaman login
    header("Location: login.php");
    
    // Hentikan eksekusi skrip
    exit;
}

// 3. Cek Peran (Centralized ACL)
// Ambil nama file saat ini
$current_page = basename($_SERVER['PHP_SELF']);

// Load konfigurasi ACL
$acl = include 'acl_config.php';

// Jika halaman tidak ada di daftar ACL, tolak akses secara default (Whitelisting Security)
// Kecuali kita mau izinkan file yg tdk terdaftar (berisiko), lebih aman tolak.
if (!array_key_exists($current_page, $acl)) {
    // Cek kasus khusus: tampil_transkrip.php mungkin diakses dosen
    if ($current_page == 'tampil_transkrip.php') {
        // Override sementara atau tambahkan logika khusus jika perlu.
        // Tapi lebih baik update acl_config.php. 
        // Kita update acl_config.php di langkah sebelumnya untuk handle ini.
        // Jadi di sini kita strict.
        die("Error Keamanan: Halaman '$current_page' belum terdaftar dalam ACL.");
    }
    
    // Default Deny
    die("Error Keamanan: Halaman tidak dikenali oleh sistem perlindungan.");
}

$allowed_roles = $acl[$current_page];

// 4. Periksa apakah peran pengguna ada di dalam array $allowed_roles
if (!in_array($_SESSION['role'], $allowed_roles)) {
    // Khusus untuk tampil_transkrip.php, Dosen Wali boleh akses
    if ($current_page == 'tampil_transkrip.php' && $_SESSION['role'] == 'dosen') {
         // Pass / Izinkan
    } 
    // Khusus untuk tampil_transkrip.php, Admin juga mungkin perlu akses?
    elseif ($current_page == 'tampil_transkrip.php' && $_SESSION['role'] == 'admin') {
         // Pass / Izinkan
    }
    else {
        // Peran pengguna tidak diizinkan.
        $_SESSION['error_message'] = "Maaf, Anda tidak memiliki hak akses untuk mengunjungi halaman tersebut.";
        header("Location: index.php");
        exit;
    }
}
?>