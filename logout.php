<?php
// 1. Mulai session
// Ini wajib ada untuk bisa mengakses dan menghancurkan session yang aktif.
session_start();

// 2. Kosongkan semua variabel session
// Ini menghapus semua data yang tersimpan (seperti 'username', 'user_id', dll)
$_SESSION = array();

// 3. Hancurkan session
// Ini menghapus session itu sendiri dari server.
session_destroy();

// 4. Redirect (arahkan) pengguna kembali ke halaman login
// Pengguna sekarang dianggap sebagai "tamu" lagi.
header("Location: login.php");
exit;
?>