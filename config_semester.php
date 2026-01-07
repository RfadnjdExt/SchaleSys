<?php
// File ini berfungsi untuk mengambil semester yang sedang aktif dari database.
// Sertakan file ini di setiap halaman yang membutuhkan konteks semester.

// Pastikan koneksi sudah ada (jika belum di-include sebelumnya, include di sini)
// Namun agar aman, kita cek dulu variabel $koneksi
if (!isset($koneksi)) {
    include 'koneksi.php';
}

function get_active_semester($conn) {
    $sql = "SELECT nama_semester FROM semester_config WHERE is_aktif = 1 LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['nama_semester'];
    }
    
    // Fallback jika tidak ada semester aktif di DB
    return 'Ganjil 2024/2025'; 
}
?>
