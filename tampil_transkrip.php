<?php
// 1. Tentukan siapa yang boleh akses (semua boleh, tapi konten beda)
$allowed_roles = ['admin', 'dosen', 'mahasiswa'];
include 'auth_guard.php'; 
include 'koneksi.php';

// ----------------------------------------------------
// ## LOGIKA NOTIFIKASI (dari sebelumnya) ##
// ----------------------------------------------------
$pesan = '';
$pesan_tipe = '';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'update_sukses':
            $pesan = "Data nilai berhasil diperbarui."; $pesan_tipe = "success"; break;
        case 'hapus_sukses':
            $pesan = "Data nilai berhasil dihapus."; $pesan_tipe = "success"; break;
    }
}

// ----------------------------------------------------
// ## LOGIKA KEAMANAN PERAN (BARU) ##
// ----------------------------------------------------
$nim_mahasiswa = '';

if ($_SESSION['role'] == 'mahasiswa') {
    // --- JIKA MAHASISWA ---
    
    // 1. Coba ambil NIM dari session
    $nim_mahasiswa = $_SESSION['nim'] ?? '';

    // 2. [FALLBACK] Jika session kosong, coba gunakan username sebagai NIM
    if (empty($nim_mahasiswa)) {
        // Cek apakah username ini ada di tabel mahasiswa
        $username_clean = mysqli_real_escape_string($koneksi, $_SESSION['username']);
        $check_nim = mysqli_query($koneksi, "SELECT nim FROM mahasiswa WHERE nim = '$username_clean'");
        if (mysqli_num_rows($check_nim) > 0) {
            $nim_mahasiswa = $username_clean;
            // Update session untuk request berikutnya
            $_SESSION['nim'] = $nim_mahasiswa; 
        }
    }

    // 3. Validasi Akhir
    if (empty($nim_mahasiswa)) {
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
                            Silakan hubungi Administrator untuk memverifikasi akun Anda.
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
    
    // Beri peringatan jika mahasiswa mencoba mengintip NIM lain (hanya jika NIM valid)
    if (isset($_GET['nim']) && !empty($_GET['nim']) && $_GET['nim'] != $nim_mahasiswa) {
        $pesan = "Anda hanya dapat melihat transkrip Anda sendiri. Menampilkan data Anda.";
        $pesan_tipe = "warning";
    }
    
} else {
    // --- JIKA ADMIN ATAU DOSEN ---
    // Ambil NIM dari URL seperti biasa
    if (!isset($_GET['nim']) || empty($_GET['nim'])) {
        // Redirect kembali atau beri pesan manis
        echo "<div class='container mt-5'><div class='alert alert-warning'>NIM tidak ditemukan. Silakan pilih mahasiswa dari <a href='tampil_mahasiswa.php'>Daftar Mahasiswa</a>.</div></div>";
        include 'footer.php';
        exit;
    }
    $nim_mahasiswa = mysqli_real_escape_string($koneksi, $_GET['nim']);
}
// ----------------------------------------------------
// ## LOGIKA KEAMANAN SELESAI ##
// ----------------------------------------------------


// --- QUERY 1: Ambil data biodata (Variabel $nim_mahasiswa sekarang sudah aman)
$sql_biodata = "SELECT nama_mahasiswa, prodi, angkatan FROM mahasiswa WHERE nim = '$nim_mahasiswa'";
$hasil_biodata = mysqli_query($koneksi, $sql_biodata);
$biodata = mysqli_fetch_assoc($hasil_biodata);

if (!$biodata) {
    die("<h1>Error: Mahasiswa dengan NIM $nim_mahasiswa tidak ditemukan.</h1>");
}

$page_title = "Transkrip Nilai - " . htmlspecialchars($biodata['nama_mahasiswa']);
include 'header.php'; 
?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <?php
        if ($pesan) {
             $alertClass = $pesan_tipe == 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            echo "<div class='{$alertClass} border px-4 py-3 rounded relative mb-4' role='alert'>
                    <span class='block sm:inline'>{$pesan}</span>
                  </div>";
        }
        ?>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100 mb-8">
            <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-8 py-6 text-white">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                         <h1 class="text-3xl font-bold tracking-tight">📜 Transkrip Nilai Akademik</h1>
                         <p class="text-blue-200 mt-1 text-lg"><?php echo htmlspecialchars($biodata['nama_mahasiswa']); ?></p>
                    </div>
                    <div class="mt-4 md:mt-0 text-right">
                        <div class="text-sm text-blue-300 uppercase tracking-widest font-semibold">Prodi</div>
                        <div class="font-medium text-lg"><?php echo htmlspecialchars($biodata['prodi']); ?></div>
                        <div class="text-sm text-blue-300 uppercase tracking-widest font-semibold mt-1">Angkatan</div>
                        <div class="font-medium"><?php echo htmlspecialchars($biodata['angkatan']); ?></div>
                    </div>
                </div>
            </div>

            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode MK</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">SKS x Bobot</th>
                                
                                <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'dosen'): ?>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $sql_nilai = "SELECT n.id_nilai, mk.kode_mk, mk.nama_mk, mk.sks, n.nilai_huruf
                                          FROM nilai n
                                          JOIN mata_kuliah mk ON n.kode_matkul = mk.kode_mk
                                          WHERE n.nim_mahasiswa = '$nim_mahasiswa'
                                          ORDER BY mk.semester ASC";
                            
                            $hasil_nilai = mysqli_query($koneksi, $sql_nilai);
                            $total_sks = 0; $total_bobot_kali_sks = 0;

                            if (mysqli_num_rows($hasil_nilai) > 0) {
                                while ($data = mysqli_fetch_assoc($hasil_nilai)) {
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

                                    echo "<tr class='hover:bg-gray-50 transition-colors duration-150'>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . htmlspecialchars($data['kode_mk']) . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>" . htmlspecialchars($data['nama_mk']) . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($data['sks']) . "</td>";
                                    
                                    // Badge untuk Nilai
                                    $badgeColor = $data['nilai_huruf'] == 'A' ? 'bg-green-100 text-green-800' : ($data['nilai_huruf'] == 'B' ? 'bg-blue-100 text-blue-800' : ($data['nilai_huruf'] == 'C' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'));
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-center'><span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full {$badgeColor}'>" . htmlspecialchars($data['nilai_huruf']) . "</span></td>";
                                    
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center'>" . $bobot . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right'>" . $sks_kali_bobot . "</td>";
                                    
                                    if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'dosen') {
                                        echo "<td class='px-6 py-4 whitespace-nowrap text-right text-sm font-medium'>
                                                <a href='edit_nilai.php?id=" . $data['id_nilai'] . "' class='text-indigo-600 hover:text-indigo-900 mr-4'>Edit</a>
                                                <a href='hapus_nilai.php?id=" . $data['id_nilai'] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"Yakin hapus?\");'>Hapus</a>
                                              </td>";
                                    }
                                    echo "</tr>";
                                }
                            } else {
                                $colspan = ($_SESSION['role'] == 'mahasiswa') ? 6 : 7;
                                echo "<tr><td colspan='$colspan' class='px-6 py-4 text-center text-sm text-gray-500'>Belum ada data nilai.</td></tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot class="bg-gray-100 font-bold">
                            <tr>
                                <td colspan="2" class="px-6 py-3 text-right text-gray-700">Total Kredit</td>
                                <td class="px-6 py-3 text-gray-900"><?php echo $total_sks; ?></td>
                                <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'dosen'): ?>
                                    <td colspan="3" class="px-6 py-3 text-right text-gray-700">Total (SKS x Bobot)</td>
                                <?php else: ?>
                                    <td colspan="2" class="px-6 py-3 text-right text-gray-700">Total (SKS x Bobot)</td>
                                <?php endif; ?>
                                <td class="px-6 py-3 text-right text-gray-900"><?php echo $total_bobot_kali_sks; ?></td>
                                <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'dosen'): ?>
                                    <td></td>
                                <?php endif; ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex justify-between items-center">
                <div class="text-xl font-bold text-gray-800">
                    IPK: 
                    <span class="text-blue-600">
                        <?php echo $total_sks > 0 ? number_format($total_bobot_kali_sks / $total_sks, 2) : '0.00'; ?>
                    </span>
                </div>
                <a href="index.php" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Kembali ke Home
                </a>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<?php
mysqli_close($koneksi);
?>