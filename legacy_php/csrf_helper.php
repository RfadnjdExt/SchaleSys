<?php
// Pastikan session sudah dimulai sebelum file ini di-include, 
// tapi kita cek dulu biar tidak double start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate CSRF Token jika belum ada
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output input hidden field untuk form HTML
 */
function csrf_field() {
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Verifikasi token dari POST request
 * Panggil fungsi ini di awal proccessing form POST
 */
function verify_csrf_token() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // Token tidak valid atau tidak dikirim
            die("<b>Error Keamanan (CSRF):</b> Token tidak valid. Mohon refresh halaman dan coba lagi.");
        }
    }
}
?>
