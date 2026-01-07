<?php
// 1. Panggil penjaga dan koneksi
include 'auth_guard.php';
include 'koneksi.php';

// 2. Cek apakah parameter NIP ada
if (isset($_GET['nip']) && !empty($_GET['nip'])) {
    
    $nip = mysqli_real_escape_string($koneksi, $_GET['nip']);

    // 3. LOGIKA PENGAMAN: Cek apakah dosen ini adalah Dosen Wali
    $sql_cek_wali = "SELECT COUNT(*) AS jumlah_wali FROM mahasiswa WHERE dosen_wali_id = '$nip'";
    $hasil_cek = mysqli_query($koneksi, $sql_cek_wali);
    $data_cek = mysqli_fetch_assoc($hasil_cek);
    
    if ($data_cek['jumlah_wali'] > 0) {
        // 4. JIKA MASIH JADI WALI: Gagal hapus, redirect dengan pesan error
        header("Location: tampil_dosen.php?status=hapus_gagal&alasan=dosen_wali");
        exit;
    } else {
        // 5. JIKA AMAN (tidak jadi wali): Lanjutkan proses hapus
        $sql_delete = "DELETE FROM dosen WHERE nip = '$nip'";

        if (mysqli_query($koneksi, $sql_delete)) {
            // Jika berhasil, redirect dengan pesan sukses
            header("Location: tampil_dosen.php?status=hapus_sukses");
            exit;
        } else {
            // Jika gagal (error aneh)
            die("Error menghapus data: " . mysqli_error($koneksi));
        }
    }

} else {
    // Jika tidak ada NIP di URL
    die("Error: NIP dosen tidak valid atau tidak disediakan.");
}

mysqli_close($koneksi);
?>