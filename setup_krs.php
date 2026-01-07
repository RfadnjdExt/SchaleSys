<?php
include 'koneksi.php';

$sql = "CREATE TABLE IF NOT EXISTS krs (
    id_krs INT AUTO_INCREMENT PRIMARY KEY,
    nim_mahasiswa VARCHAR(20) NOT NULL,
    kode_matkul VARCHAR(10) NOT NULL,
    semester VARCHAR(20) NOT NULL,
    tanggal_ambil DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (nim_mahasiswa) REFERENCES mahasiswa(nim) ON DELETE CASCADE,
    FOREIGN KEY (kode_matkul) REFERENCES mata_kuliah(kode_mk) ON DELETE CASCADE,
    UNIQUE KEY unique_krs (nim_mahasiswa, kode_matkul, semester)
)";

if (mysqli_query($koneksi, $sql)) {
    echo "Tabel 'krs' berhasil dibuat atau sudah ada.";
} else {
    echo "Error membuat tabel: " . mysqli_error($koneksi);
}

mysqli_close($koneksi);
?>
