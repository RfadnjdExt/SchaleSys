<?php
$allowed_roles = ['mahasiswa', 'admin', 'dosen']; // Dosen/Admin mungkin perlu lihat juga, tapi ini untuk view siswa sendiri
include 'auth_guard.php'; 
include 'koneksi.php';

// Jika admin/dosen lihat, mungkin ada parameter NIM. 
// Tapi untuk sekarang kita buat simpel: mahasiswa lihat punya sendiri.
// Jika admin ingin lihat KRS mahasiswa, bisa dibuat halaman terpisah atau parameter.
// Kita fokus ke fitur "Mahasiswa lihat KRS sendiri".

if ($_SESSION['role'] != 'mahasiswa') {
    // Redirect atau error jika bukan mahasiswa (untuk kesederhanaan sesi ini)
    // Kecuali kita tambahkan fitur "Lihat KRS Mahasiswa X" nanti.
    // Untuk sekarang kita batasi ke mahasiswa sja.
    // Atau jika admin, kita butuh parameter ?nim=...
    if (isset($_GET['nim']) && $_SESSION['role'] == 'admin') {
         $nim = mysqli_real_escape_string($koneksi, $_GET['nim']);
         // Ambil nama mahasiswa 
         $q_mhs = mysqli_query($koneksi, "SELECT nama_mahasiswa FROM mahasiswa WHERE nim='$nim'");
         $d_mhs = mysqli_fetch_assoc($q_mhs);
         $nama_mhs = $d_mhs['nama_mahasiswa'];
    } else {
        // Default (Mahasiswa login)
        if ($_SESSION['role'] != 'mahasiswa') {
             die("Halaman ini sementara khusus Mahasiswa. Admin silakan gunakan fitur lain.");
        }
        
        // --- LOGIKA FALLBACK NIM UNTUK AKUN ILLEGAL/TESTING ---
        $nim = $_SESSION['nim'] ?? '';
        if (empty($nim)) {
            $username_clean = mysqli_real_escape_string($koneksi, $_SESSION['username']);
            $check_nim = mysqli_query($koneksi, "SELECT nim FROM mahasiswa WHERE nim = '$username_clean'");
            if (mysqli_num_rows($check_nim) > 0) {
                $nim = $username_clean;
                $_SESSION['nim'] = $nim;
            } else {
                 // Stop execution but render a nice page first
                $page_title = "Akses Ditolak";
                include 'header.php';
                ?>
                <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
                    <div class="sm:mx-auto sm:w-full sm:max-w-md">
                        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border-l-4 border-red-500">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-red-100 rounded-full p-2">
                                     <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg leading-6 font-medium text-gray-900">
                                    Data Akun Tidak Lengkap
                                </h3>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Akun Anda (Username: <span class="font-mono font-bold bg-gray-100 px-1 rounded"><?php echo htmlspecialchars($_SESSION['username']); ?></span>) tidak terhubung dengan data Mahasiswa manapun (NIM Kosong).
                                </p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Silakan hubungi Administrator atau gunakan akun yang valid.
                                </p>
                            </div>
                            <div class="mt-6">
                                <a href="index.php" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                include 'footer.php';
                exit;
            }
        }
        // --- END FALLBACK ---

        $nama_mhs = $_SESSION['nama_lengkap'];
    }
} else {
    // --- LOGIKA FALLBACK NIM JUGA DI SINI (JIKA USER LANGSUNG KE SINI ) ---
     $nim = $_SESSION['nim'] ?? '';
        if (empty($nim)) {
            $username_clean = mysqli_real_escape_string($koneksi, $_SESSION['username']);
            $check_nim = mysqli_query($koneksi, "SELECT nim FROM mahasiswa WHERE nim = '$username_clean'");
            if (mysqli_num_rows($check_nim) > 0) {
                $nim = $username_clean;
                $_SESSION['nim'] = $nim;
            } else {
                 // Stop execution but render a nice page first
                $page_title = "Akses Ditolak";
                include 'header.php';
                ?>
                <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
                    <div class="sm:mx-auto sm:w-full sm:max-w-md">
                        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border-l-4 border-red-500">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-red-100 rounded-full p-2">
                                     <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg leading-6 font-medium text-gray-900">
                                    Data Akun Tidak Lengkap
                                </h3>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Akun Anda (Username: <span class="font-mono font-bold bg-gray-100 px-1 rounded"><?php echo htmlspecialchars($_SESSION['username']); ?></span>) tidak terhubung dengan data Mahasiswa manapun (NIM Kosong).
                                </p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Silakan hubungi Administrator atau gunakan akun yang valid.
                                </p>
                            </div>
                            <div class="mt-6">
                                <a href="index.php" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                include 'footer.php';
                exit;
            }
        }
    $nama_mhs = $_SESSION['nama_lengkap'];
}

include 'config_semester.php';
$semester_aktif = get_active_semester($koneksi);

// Query ambil KRS
$sql = "SELECT k.tanggal_ambil, m.kode_mk, m.nama_mk, m.sks, m.semester, m.hari, m.jam_mulai, m.jam_selesai, m.ruangan
        FROM krs k 
        JOIN mata_kuliah m ON k.kode_matkul = m.kode_mk 
        WHERE k.nim_mahasiswa = '$nim' AND k.semester = '$semester_aktif'
        ORDER BY m.semester, m.nama_mk";

$hasil = mysqli_query($koneksi, $sql);

$page_title = "Lihat KRS - " . htmlspecialchars($nama_mhs);
include 'header.php'; 
?>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Kartu Rencana Studi (KRS)
                    </h2>
                    <p class="text-blue-200 text-sm mt-1 ml-8"><?php echo htmlspecialchars($semester_aktif); ?></p>
                </div>
                <div class="hidden sm:block">
                     <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 bg-opacity-25 text-white border border-blue-400">
                        Mahasiswa
                    </span>
                </div>
            </div>
            
            <div class="p-8">
                
                <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <span class="w-24 text-sm font-medium text-gray-500 uppercase tracking-wider">NIM</span>
                            <span class="text-gray-900 font-semibold">: <?php echo htmlspecialchars($nim); ?></span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-24 text-sm font-medium text-gray-500 uppercase tracking-wider">Nama</span>
                            <span class="text-gray-900 font-semibold">: <?php echo htmlspecialchars($nama_mhs); ?></span>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden border border-gray-200 rounded-lg mb-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-12">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Kode MK</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Mata Kuliah</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">Sem</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Jadwal</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">SKS</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $nomor = 1;
                            $total_sks = 0;
                            if (mysqli_num_rows($hasil) > 0) {
                                while ($row = mysqli_fetch_assoc($hasil)) {
                                    echo "<tr class='hover:bg-gray-50 transition-colors'>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500'>{$nomor}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono'>{$row['kode_mk']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$row['nama_mk']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500'>{$row['semester']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>
                                            <div>{$row['hari']} {$row['jam_mulai']}-{$row['jam_selesai']}</div>
                                            <div class='text-xs text-gray-400'>R. {$row['ruangan']}</div>
                                          </td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900'>{$row['sks']}</td>";
                                    echo "</tr>";
                                    $nomor++;
                                    $total_sks += $row['sks'];
                                }
                            } else {
                                echo "<tr><td colspan='6' class='px-6 py-8 text-center text-gray-500 italic'>Belum ada mata kuliah yang diambil.</td></tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="5" class="px-6 py-3 text-right text-sm font-bold text-gray-900 uppercase">Total SKS</td>
                                <td class="px-6 py-3 text-center text-sm font-bold text-blue-600 border-t border-gray-200 bg-blue-50"><?php echo $total_sks; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-4 no-print">
                    <button onclick="window.print()" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak KRS
                    </button>
                    <?php if($_SESSION['role'] == 'mahasiswa'): ?>
                         <a href="input_krs.php" class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit / Tambah Mata Kuliah
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<?php mysqli_close($koneksi); ?>
