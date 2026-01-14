<?php
// Letakkan di baris paling pertama, SEBELUM 'include koneksi.php'
include 'auth_guard.php'; 

// Sertakan file koneksi.php
include 'koneksi.php';

// 2. Cek apakah parameter ID ada di URL dan tidak kosong
// 2. Cek apakah parameter ID ada di URL dan tidak kosong
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_nilai = (int)$_GET['id']; // Ambil dan bersihkan ID

    try {
        // 3. (PENTING) Ambil NIM mahasiswa SEBELUM data dihapus
        //    Kita perlu NIM ini untuk redirect kembali ke halaman transkrip yang benar.
        $stmt_get = $koneksi->prepare("SELECT nim_mahasiswa FROM nilai WHERE id_nilai = ?");
        $stmt_get->execute([$id_nilai]);
        $data = $stmt_get->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            $nim_mahasiswa = $data['nim_mahasiswa'];

            // 4. Buat kueri DELETE
            $stmt_delete = $koneksi->prepare("DELETE FROM nilai WHERE id_nilai = ?");

            // 5. Jalankan kueri DELETE
            if ($stmt_delete->execute([$id_nilai])) {
                // Jika berhasil, redirect kembali ke transkrip dengan pesan sukses
                header("Location: tampil_transkrip.php?nim=" . $nim_mahasiswa . "&status=hapus_sukses");
                exit;
            } else {
                // Jika gagal menghapus (sangat jarang dengan PDO kecuali constraint violation)
                die("Error menghapus data.");
            }

        } else {
            // Jika ID nilai tidak ditemukan
            die("Error: Data nilai tidak ditemukan.");
        }
    } catch (PDOException $e) {
         die("Error menghapus data: " . $e->getMessage());
    }

} else {
    // Jika tidak ada ID di URL
    die("Error: ID nilai tidak valid atau tidak disediakan.");
}

// 6. Tutup koneksi (meskipun jarang tercapai karena ada redirect/die)
// $koneksi auto-close
?>