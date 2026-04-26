<?php
// File ini berfungsi untuk mengambil semester yang sedang aktif dari database.
// Sertakan file ini di setiap halaman yang membutuhkan konteks semester.

// Pastikan koneksi sudah ada (jika belum di-include sebelumnya, include di sini)
// Namun agar aman, kita cek dulu variabel $koneksi
// Pastikan koneksi sudah ada (jika belum di-include sebelumnya, include di sini)
// Namun agar aman, kita cek dulu variabel $koneksi
if (!isset($koneksi)) {
    include 'koneksi.php';
}

function get_active_semester($conn) {
    try {
        $stmt = $conn->query("SELECT nama_semester FROM semester_config WHERE is_aktif = 1 LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return $row['nama_semester'];
        }
    } catch (PDOException $e) {
        // Fallback silently or log error
    }
    
    // Fallback jika tidak ada semester aktif di DB
    return 'Ganjil 2024/2025'; 
}
?>
