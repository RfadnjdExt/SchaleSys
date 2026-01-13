<?php
// 1. Sertakan file koneksi.php
header('Content-Type: application/json'); // Memberi tahu browser bahwa outputnya adalah format JSON
include 'koneksi.php';

// 2. Cek apakah parameter NIM ada di URL
if (!isset($_GET['nim']) || empty($_GET['nim'])) {
    // Jika tidak ada NIM, kirim pesan error dalam format JSON
    echo json_encode(['error' => 'NIM tidak ditemukan.']);
    exit; // Hentikan eksekusi skrip
}

// 3. Ambil NIM dari URL
$nim_mahasiswa = $_GET['nim'];

// 4. Buat kueri SQL untuk menghitung IPK secara langsung
$sql_ipk = "
    SELECT
        -- Menghitung total (SKS * Bobot Nilai)
        SUM(mk.sks * CASE n.nilai_huruf
                WHEN 'A' THEN 4
                WHEN 'B' THEN 3
                WHEN 'C' THEN 2
                WHEN 'D' THEN 1
                ELSE 0
            END
        ) AS total_bobot_sks,
        
        -- Menghitung total SKS yang diambil
        SUM(mk.sks) AS total_sks
    FROM
        nilai n
    JOIN
        mata_kuliah mk ON n.kode_matkul = mk.kode_mk
    WHERE
        n.nim_mahasiswa = :nim
";

try {
    // 5. Jalankan kueri
    $stmt = $koneksi->prepare($sql_ipk);
    $stmt->execute([':nim' => $nim_mahasiswa]);
    $data_ipk = $stmt->fetch(PDO::FETCH_ASSOC);

    // 6. Lakukan perhitungan IPK
    $ipk = 0;
    // Pastikan total_sks tidak null dan tidak 0 untuk menghindari error pembagian
    if ($data_ipk && $data_ipk['total_sks'] > 0) {
        $ipk = $data_ipk['total_bobot_sks'] / $data_ipk['total_sks'];
    }

    // 7. Siapkan output dalam format array
    $output = [
        'nim' => $nim_mahasiswa,
        'total_sks_diambil' => (int) ($data_ipk['total_sks'] ?? 0),
        'ipk' => round($ipk, 2) // Bulatkan IPK menjadi 2 desimal
    ];

    // 8. Tampilkan hasil dalam format JSON
    echo json_encode($output);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

// 9. Tutup koneksi - tidak perlu explicit close untuk PDO, terjadi otomatis saat script selesai
?>