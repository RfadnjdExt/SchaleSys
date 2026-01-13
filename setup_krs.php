<?php
include 'koneksi.php';

try {
    $driver = $koneksi->getAttribute(PDO::ATTR_DRIVER_NAME);

    if ($driver == 'pgsql') {
        $sql = "CREATE TABLE IF NOT EXISTS krs (
            id_krs SERIAL PRIMARY KEY,
            nim_mahasiswa VARCHAR(20) NOT NULL,
            kode_matkul VARCHAR(10) NOT NULL,
            semester VARCHAR(20) NOT NULL,
            tanggal_ambil DATE DEFAULT CURRENT_DATE,
            FOREIGN KEY (nim_mahasiswa) REFERENCES mahasiswa(nim) ON DELETE CASCADE,
            FOREIGN KEY (kode_matkul) REFERENCES mata_kuliah(kode_mk) ON DELETE CASCADE,
            UNIQUE (nim_mahasiswa, kode_matkul, semester)
        )";
    } else {
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
    }

    $koneksi->exec($sql);
    echo "Tabel 'krs' berhasil dibuat atau sudah ada.";

} catch (PDOException $e) {
    echo "Error membuat tabel: " . $e->getMessage();
}
?>
