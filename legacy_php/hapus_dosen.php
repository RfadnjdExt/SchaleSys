<?php
// 1. Panggil penjaga dan koneksi
include 'auth_guard.php';
include 'koneksi.php';

// 2. Cek apakah parameter NIP ada
if (isset($_GET['nip']) && !empty($_GET['nip'])) {
    
    $nip = $_GET['nip'];

    try {
        // 3. LOGIKA PENGAMAN: Cek apakah dosen ini adalah Dosen Wali
        $stmt_cek = $koneksi->prepare("SELECT COUNT(*) AS jumlah_wali FROM mahasiswa WHERE dosen_wali_id = ?");
        $stmt_cek->execute([$nip]);
        $data_cek = $stmt_cek->fetch(PDO::FETCH_ASSOC);
        
        if ($data_cek['jumlah_wali'] > 0) {
            // 4. JIKA MASIH JADI WALI: Gagal hapus, redirect dengan pesan error
            header("Location: tampil_dosen.php?status=hapus_gagal&alasan=dosen_wali");
            exit;
        } else {
            // 5. JIKA AMAN (tidak jadi wali): Lanjutkan proses hapus
            $stmt_delete = $koneksi->prepare("DELETE FROM dosen WHERE nip = ?");
            
            if ($stmt_delete->execute([$nip])) {
                // Jika berhasil, redirect dengan pesan sukses
                header("Location: tampil_dosen.php?status=hapus_sukses");
                exit;
            } else {
                 die("Error menghapus data.");
            }
        }
    } catch (PDOException $e) {
        die("Error menghapus data: " . $e->getMessage());
    }

} else {
    // Jika tidak ada NIP di URL
    die("Error: NIP dosen tidak valid atau tidak disediakan.");
}

// $koneksi auto-close
?>