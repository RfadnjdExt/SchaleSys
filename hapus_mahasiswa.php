<?php
// 1. Panggil penjaga dan koneksi
include 'auth_guard.php';
include 'koneksi.php';

// 2. Cek apakah parameter NIM ada
if (isset($_GET['nim']) && !empty($_GET['nim'])) {
    
    $nim = mysqli_real_escape_string($koneksi, $_GET['nim']);

    // 3. LOGIKA PENGAMAN: Cek apakah mahasiswa punya data nilai
    $sql_cek_nilai = "SELECT COUNT(*) AS jumlah_nilai FROM nilai WHERE nim_mahasiswa = '$nim'";
    $hasil_cek = mysqli_query($koneksi, $sql_cek_nilai);
    $data_cek = mysqli_fetch_assoc($hasil_cek);
    
    if ($data_cek['jumlah_nilai'] > 0) {
        // 4. JIKA PUNYA NILAI: Gagal hapus, redirect dengan pesan error
        header("Location: tampil_mahasiswa.php?status=hapus_gagal&alasan=nilai_ada");
        exit;
    } else {
        // 5. JIKA AMAN (tidak punya nilai): Lanjutkan proses hapus
        $sql_delete = "DELETE FROM mahasiswa WHERE nim = '$nim'";

        if (mysqli_query($koneksi, $sql_delete)) {
            // Jika berhasil, redirect dengan pesan sukses
            header("Location: tampil_mahasiswa.php?status=hapus_sukses");
            exit;
        } else {
            // Jika gagal (error aneh)
            die("Error menghapus data: " . mysqli_error($koneksi));
        }
    }

} else {
    // Jika tidak ada NIM di URL
    die("Error: NIM mahasiswa tidak valid atau tidak disediakan.");
}

mysqli_close($koneksi);
?>