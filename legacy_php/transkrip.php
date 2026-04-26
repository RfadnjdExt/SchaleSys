<?php
// Sertakan file koneksi.php
include 'koneksi.php';

// Cek apakah parameter NIM ada di URL
if (!isset($_GET['nim']) || empty($_GET['nim'])) {
    die("<h1>Error: NIM tidak ditemukan.</h1><p>Silakan kembali dan pilih mahasiswa terlebih dahulu.</p>");
}

// Ambil NIM dari URL dan bersihkan dari karakter berbahaya
$nim_mahasiswa = $_GET['nim'];

try {
    // --- QUERY 1: Ambil data biodata mahasiswa ---
    $stmt_bio = $koneksi->prepare("SELECT nama_mahasiswa, prodi FROM mahasiswa WHERE nim = ?");
    $stmt_bio->execute([$nim_mahasiswa]);
    $biodata = $stmt_bio->fetch(PDO::FETCH_ASSOC);

    // Jika mahasiswa dengan NIM tersebut tidak ditemukan
    if (!$biodata) {
        die("<h1>Error: Mahasiswa dengan NIM " . htmlspecialchars($nim_mahasiswa) . " tidak ditemukan.</h1>");
    }
} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}

$page_title = "Transkrip Nilai - " . htmlspecialchars($biodata['nama_mahasiswa']);
?>
<?php include 'header.php'; ?>

    <div class="max-w-4xl mx-auto px-4 py-8">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center justify-center">
                <span class="mr-3 text-4xl">📜</span> Transkrip Nilai Akademik
            </h1>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex border-b border-gray-100 pb-2 md:border-b-0 md:pb-0">
                        <span class="w-32 font-semibold text-gray-600">NIM</span>
                        <span class="text-gray-900">: <?php echo htmlspecialchars($nim_mahasiswa); ?></span>
                    </div>
                    <div class="flex border-b border-gray-100 pb-2 md:border-b-0 md:pb-0">
                        <span class="w-32 font-semibold text-gray-600">Nama Lengkap</span>
                        <span class="text-gray-900">: <?php echo htmlspecialchars($biodata['nama_mahasiswa']); ?></span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-semibold text-gray-600">Program Studi</span>
                        <span class="text-gray-900">: <?php echo htmlspecialchars($biodata['prodi']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="overflow-hidden border-b border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Kode MK</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Mata Kuliah</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">SKS</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">Nilai</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">Bobot</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-24">K x B</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        try {
                            // --- QUERY 2: Ambil data nilai ---
                            $sql_nilai = "SELECT mk.kode_mk, mk.nama_mk, mk.sks, n.nilai_huruf
                                          FROM nilai n
                                          JOIN mata_kuliah mk ON n.kode_matkul = mk.kode_mk
                                          WHERE n.nim_mahasiswa = :nim
                                          ORDER BY mk.semester ASC";
                            
                            $stmt_nilai = $koneksi->prepare($sql_nilai);
                            $stmt_nilai->execute([':nim' => $nim_mahasiswa]);
                            $hasil_nilai = $stmt_nilai->fetchAll(PDO::FETCH_ASSOC);

                            $total_sks = 0;
                            $total_bobot_kali_sks = 0;

                            if (count($hasil_nilai) > 0) {
                                foreach ($hasil_nilai as $data) {
                                    $bobot = 0;
                                    switch ($data['nilai_huruf']) {
                                        case 'A': $bobot = 4; break;
                                        case 'B': $bobot = 3; break;
                                        case 'C': $bobot = 2; break;
                                        case 'D': $bobot = 1; break;
                                        default:  $bobot = 0; break;
                                    }

                                    $sks_kali_bobot = $data['sks'] * $bobot;
                                    $total_sks += $data['sks'];
                                    $total_bobot_kali_sks += $sks_kali_bobot;

                                    echo "<tr class='hover:bg-gray-50 transition-colors'>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono'>{$data['kode_mk']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$data['nama_mk']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500'>{$data['sks']}</td>";
                                    
                                    $gradeColor = $data['nilai_huruf'] == 'A' ? 'text-green-600 font-bold' : ($data['nilai_huruf'] == 'B' ? 'text-blue-600' : ($data['nilai_huruf'] == 'C' ? 'text-yellow-600' : 'text-red-600'));
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm {$gradeColor}'>{$data['nilai_huruf']}</td>";
                                    
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500'>{$bobot}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-gray-700'>{$sks_kali_bobot}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='px-6 py-8 text-center text-gray-500 italic'>Belum ada data nilai.</td></tr>";
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='6' class='px-6 py-8 text-center text-red-500 italic'>Error: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                    <tfoot class="bg-gray-100">
                        <tr>
                            <td colspan="2" class="px-6 py-3 text-right text-sm font-bold text-gray-900 uppercase">Total</td>
                            <td class="px-6 py-3 text-center text-sm font-bold text-blue-600"><?php echo $total_sks; ?></td>
                            <td colspan="2"></td>
                            <td class="px-6 py-3 text-center text-sm font-bold text-blue-600"><?php echo $total_bobot_kali_sks; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 shadow-sm flex justify-end">
            <?php
            // Hitung IPK
            $ipk = 0;
            if ($total_sks > 0) {
                $ipk = $total_bobot_kali_sks / $total_sks;
            }
            ?>
            <div class="text-right">
                <span class="block text-sm font-medium text-blue-600 uppercase tracking-wider">Indeks Prestasi Kumulatif (IPK)</span>
                <span class="block text-4xl font-extrabold text-blue-800"><?php echo number_format($ipk, 2); ?></span>
            </div>
        </div>
        
        <div class="text-center mt-8 no-print">
             <button onclick="window.print()" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Transkrip
            </button>
        </div>

    </div>

<?php include 'footer.php'; ?>
<?php // No close needed ?>