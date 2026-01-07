<?php
// Letakkan di baris paling pertama, SEBELUM 'include koneksi.php'
include 'auth_guard.php'; 

// Sertakan file koneksi.php
include 'koneksi.php';

// 2. Cek apakah parameter ID ada di URL dan tidak kosong
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_nilai = (int)$_GET['id']; // Ambil dan bersihkan ID

    // 3. (PENTING) Ambil NIM mahasiswa SEBELUM data dihapus
    //    Kita perlu NIM ini untuk redirect kembali ke halaman transkrip yang benar.
    $sql_get_nim = "SELECT nim_mahasiswa FROM nilai WHERE id_nilai = $id_nilai";
    $hasil_nim = mysqli_query($koneksi, $sql_get_nim);
    
    if (mysqli_num_rows($hasil_nim) == 1) {
        $data = mysqli_fetch_assoc($hasil_nim);
        $nim_mahasiswa = $data['nim_mahasiswa'];

        // 4. Buat kueri DELETE
        $sql_delete = "DELETE FROM nilai WHERE id_nilai = $id_nilai";

        // 5. Jalankan kueri DELETE
        if (mysqli_query($koneksi, $sql_delete)) {
            // Jika berhasil, redirect kembali ke transkrip dengan pesan sukses
            header("Location: tampil_transkrip.php?nim=" . $nim_mahasiswa . "&status=hapus_sukses");
            exit;
        } else {
            // Jika gagal menghapus
            die("Error menghapus data: " . mysqli_error($koneksi));
        }

    } else {
        // Jika ID nilai tidak ditemukan
        die("Error: Data nilai tidak ditemukan.");
    }

} else {
    // Jika tidak ada ID di URL
    die("Error: ID nilai tidak valid atau tidak disediakan.");
}

// 6. Tutup koneksi (meskipun jarang tercapai karena ada redirect/die)
mysqli_close($koneksi);
?>