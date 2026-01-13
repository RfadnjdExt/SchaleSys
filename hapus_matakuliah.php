<?php
// 1. Panggil penjaga dan koneksi
include 'auth_guard.php';
include 'koneksi.php';

// 2. Cek apakah parameter KODE ada
if (isset($_GET['kode']) && !empty($_GET['kode'])) {
    
    $kode_mk = $_GET['kode']; // Tidak perlu escape string manual dengan PDO prepare

    try {
        // 3. LOGIKA PENGAMAN: Cek apakah MK ini punya data nilai
        $stmt_cek = $koneksi->prepare("SELECT COUNT(*) AS jumlah_nilai FROM nilai WHERE kode_matkul = ?");
        $stmt_cek->execute([$kode_mk]);
        $data_cek = $stmt_cek->fetch(PDO::FETCH_ASSOC);
        
        if ($data_cek['jumlah_nilai'] > 0) {
            // 4. JIKA PUNYA NILAI: Gagal hapus, redirect dengan pesan error
            header("Location: tampil_matakuliah.php?status=hapus_gagal&alasan=nilai_ada");
            exit;
        } else {
            // 5. JIKA AMAN (tidak punya nilai): Lanjutkan proses hapus
            $stmt_delete = $koneksi->prepare("DELETE FROM mata_kuliah WHERE kode_mk = ?");
            
            if ($stmt_delete->execute([$kode_mk])) {
                // Jika berhasil, redirect dengan pesan sukses
                header("Location: tampil_matakuliah.php?status=hapus_sukses");
                exit;
            } else {
                 die("Error menghapus data.");
            }
        }
    } catch (PDOException $e) {
        die("Error menghapus data: " . $e->getMessage());
    }

} else {
    // Jika tidak ada KODE di URL
    die("Error: Kode mata kuliah tidak valid atau tidak disediakan.");
}

// $koneksi auto-close
?>