<?php
// 1. Panggil penjaga dan koneksi
include 'auth_guard.php';
include 'koneksi.php';

// 2. Cek apakah parameter NIM ada
if (isset($_GET['nim']) && !empty($_GET['nim'])) {
    
    $nim = $_GET['nim'];

    try {
        // 3. LOGIKA PENGAMAN: Cek apakah mahasiswa punya data nilai
        $stmt_cek = $koneksi->prepare("SELECT COUNT(*) AS jumlah_nilai FROM nilai WHERE nim_mahasiswa = ?");
        $stmt_cek->execute([$nim]);
        $data_cek = $stmt_cek->fetch(PDO::FETCH_ASSOC);
        
        if ($data_cek['jumlah_nilai'] > 0) {
            // 4. JIKA PUNYA NILAI: Gagal hapus, redirect dengan pesan error
            header("Location: tampil_mahasiswa.php?status=hapus_gagal&alasan=nilai_ada");
            exit;
        } else {
            // 5. JIKA AMAN (tidak punya nilai): Lanjutkan proses hapus
            $stmt_delete = $koneksi->prepare("DELETE FROM mahasiswa WHERE nim = ?");
            
            if ($stmt_delete->execute([$nim])) {
                // Jika berhasil, redirect dengan pesan sukses
                header("Location: tampil_mahasiswa.php?status=hapus_sukses");
                exit;
            } else {
                // Jika gagal (PDO Exception usually catches this, but just in case)
                die("Error menghapus data.");
            }
        }
    } catch (PDOException $e) {
        die("Error menghapus data: " . $e->getMessage());
    }

} else {
    // Jika tidak ada NIM di URL
    die("Error: NIM mahasiswa tidak valid atau tidak disediakan.");
}

// $koneksi auto-close
?>