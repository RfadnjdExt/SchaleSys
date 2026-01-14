<?php
// Tentukan role yang boleh akses: admin, dosen, mahasiswa (semua boleh)
$allowed_roles = ['admin', 'dosen', 'mahasiswa'];
include "auth_guard.php";

include "koneksi.php";

$sql_mhs = "SELECT COUNT(nim) AS total_mahasiswa FROM mahasiswa";
$stmt_mhs = $koneksi->query($sql_mhs);
$total_mahasiswa = $stmt_mhs->fetch(PDO::FETCH_ASSOC)["total_mahasiswa"];

$sql_mk = "SELECT COUNT(kode_mk) AS total_matkul FROM mata_kuliah";
$stmt_mk = $koneksi->query($sql_mk);
$total_matkul = $stmt_mk->fetch(PDO::FETCH_ASSOC)["total_matkul"];

$sql_dosen = "SELECT COUNT(nip) AS total_dosen FROM dosen";
$stmt_dosen = $koneksi->query($sql_dosen);
$total_dosen = $stmt_dosen->fetch(PDO::FETCH_ASSOC)["total_dosen"];

$sql_kinerja = "
    SELECT
        m.prodi,
        AVG(ipk_per_mahasiswa.ipk) AS ipk_rata_rata
    FROM
        mahasiswa m
    JOIN (
        SELECT
            n.nim_mahasiswa,
            SUM(mk.sks * CASE n.nilai_huruf
                WHEN 'A' THEN 4
                WHEN 'B' THEN 3
                WHEN 'C' THEN 2
                WHEN 'D' THEN 1
                ELSE 0
            END) / NULLIF(SUM(mk.sks), 0) AS ipk
        FROM
            nilai n
        JOIN
            mata_kuliah mk ON n.kode_matkul = mk.kode_mk
        GROUP BY
            n.nim_mahasiswa
    ) AS ipk_per_mahasiswa ON m.nim = ipk_per_mahasiswa.nim_mahasiswa
    GROUP BY
        m.prodi
    ORDER BY
        ipk_rata_rata DESC
";
// NOTE: Added NULLIF to prevent division by zero in SQL (compatible with Postgres)
try {
    $stmt_kinerja = $koneksi->query($sql_kinerja);
    $hasil_kinerja = $stmt_kinerja->fetchAll(PDO::FETCH_ASSOC); // Fetch all to easily check count
} catch (PDOException $e) {
     die("Gagal mengambil data kinerja prodi. Error: " . $e->getMessage());
}

$page_title = "Dashboard Kinerja Akademik";
include 'header.php'; 
?>


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Ringkasan Sistem</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                <div class="p-6 text-center text-white">
                    <h5 class="text-lg font-semibold opacity-90 mb-2">Total Mahasiswa</h5>
                    <p class="text-5xl font-bold"><?php echo $total_mahasiswa; ?></p>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                <div class="p-6 text-center text-white">
                    <h5 class="text-lg font-semibold opacity-90 mb-2">Total Mata Kuliah</h5>
                    <p class="text-5xl font-bold"><?php echo $total_matkul; ?></p>
                </div>
            </div>
            <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                <div class="p-6 text-center text-white">
                    <h5 class="text-lg font-semibold opacity-90 mb-2">Total Dosen</h5>
                    <p class="text-5xl font-bold"><?php echo $total_dosen; ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            🚀 Kinerja Akademik Program Studi
                        </h3>
                    </div>
                    <div class="p-0">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Program Studi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">IPK Rata-Rata</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (count($hasil_kinerja) > 0) {
                                        foreach ($hasil_kinerja as $data) {
                                            echo "<tr class='hover:bg-gray-50 transition-colors'>";
                                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . htmlspecialchars($data["prodi"]) . "</td>";
                                            // Handle potential null or floating point precision issues
                                            $ipk_val = is_numeric($data["ipk_rata_rata"]) ? $data["ipk_rata_rata"] : 0;
                                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'><strong>" . number_format((float)$ipk_val, 2) . "</strong></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2' class='px-6 py-4 text-center text-gray-500'>Belum ada data nilai untuk dianalisis.</td></tr>";
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 sticky top-4">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">🔗 Akses Cepat</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-2">
                            <a href="index.php" class="block px-4 py-3 rounded-lg bg-gray-50 text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200 font-medium">
                                🏠 Halaman Utama
                            </a>
                            
                            <?php if ($_SESSION['role'] != 'mahasiswa'): ?>
                                <a href="tambah_nilai.php" class="block px-4 py-3 rounded-lg bg-gray-50 text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors duration-200 font-medium">
                                    ➕ Tambah Nilai Baru
                                </a>
                                <a href="tampil_mahasiswa.php" class="block px-4 py-3 rounded-lg bg-gray-50 text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-colors duration-200 font-medium">
                                    👨‍🎓 Lihat Semua Mahasiswa
                                </a>
                            <?php else: ?>
                                <a href="input_krs.php" class="block px-4 py-3 rounded-lg bg-gray-50 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200 font-medium">
                                    📅 Isi KRS
                                </a>
                                <a href="tampil_krs.php" class="block px-4 py-3 rounded-lg bg-gray-50 text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200 font-medium">
                                    👀 Lihat KRS Saya
                                </a>
                                <a href="tampil_transkrip.php" class="block px-4 py-3 rounded-lg bg-gray-50 text-gray-700 hover:bg-cyan-50 hover:text-cyan-700 transition-colors duration-200 font-medium">
                                    📜 Transkrip Nilai
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>

