<?php
// 1. Tentukan siapa yang boleh akses: Mahasiswa saja
$allowed_roles = ['mahasiswa'];
include 'auth_guard.php'; 
include 'koneksi.php';
include 'csrf_helper.php';
include 'config_semester.php'; // Helper Semester

$nim = $_SESSION['nim'] ?? ''; // Ambil NIM dari session

// --- LOGIKA FALLBACK NIM UNTUK AKUN ILLEGAL/TESTING ---
if (empty($nim)) {
    $username_clean = mysqli_real_escape_string($koneksi, $_SESSION['username']);
    $check_nim = mysqli_query($koneksi, "SELECT nim FROM mahasiswa WHERE nim = '$username_clean'");
    if (mysqli_num_rows($check_nim) > 0) {
        $nim = $username_clean;
        $_SESSION['nim'] = $nim;
    } else {
        // Jangan die() di sini dulu, biarkan error message ditangani di layout bawah atau redirect
        // Tapi karena script ini butuh NIM untuk logic POST, kita harus stop jika POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
             include 'header.php';
             echo "<div class='max-w-7xl mx-auto px-4 py-8'><div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative' role='alert'><strong class='font-bold'>Error!</strong> <span class='block sm:inline'>Akun Anda tidak memiliki NIM yang valid (Kosong). Tidak dapat menyimpan KRS. Hubungi Admin.</span></div><div class='mt-4'><a href='index.php' class='text-blue-600 hover:text-blue-800 font-medium'>&larr; Kembali ke Beranda</a></div></div>";
             include 'footer.php';
             exit;
        }
        // Jika GET, kita akan tampilkan pesan error cantik di body
    }
}
// --- END FALLBACK ---

$semester_aktif = get_active_semester($koneksi); // Ambil dari DB
$pesan = '';
$pesan_tipe = '';

// --- BAGIAN 1: PROSES PENYIMPANAN KRS ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifikasi CSRF Token
    verify_csrf_token();
    
    // Ambil array kode_matkul yang dipilih
    if (isset($_POST['matkul_diambil']) && is_array($_POST['matkul_diambil'])) {
        
        $matkul_dipilih = $_POST['matkul_diambil'];
        
        // 1. Hitung SKS yang SUDAH diambil di semester ini
        $sql_cek_sks = "SELECT SUM(m.sks) as total_sks 
                        FROM krs k 
                        JOIN mata_kuliah m ON k.kode_matkul = m.kode_mk 
                        WHERE k.nim_mahasiswa = ? AND k.semester = ?";
        
        $stmt_cek = mysqli_prepare($koneksi, $sql_cek_sks);
        mysqli_stmt_bind_param($stmt_cek, "ss", $nim, $semester_aktif);
        mysqli_stmt_execute($stmt_cek);
        $res_cek = mysqli_stmt_get_result($stmt_cek);
        $row_cek = mysqli_fetch_assoc($res_cek);
        $sks_existing = (int)$row_cek['total_sks'];

        // 2. Hitung SKS yang BARU dipilih
        // Kita butuh query ke DB untuk ambil SKS dari kode yang dikirim (amannya ambil dari DB, jangan dari hidden input)
        $kode_in = "'" . implode("','", array_map(function($val) use ($koneksi) { return mysqli_real_escape_string($koneksi, $val); }, $matkul_dipilih)) . "'";
        
        $sql_new_sks = "SELECT SUM(sks) as total_sks_baru FROM mata_kuliah WHERE kode_mk IN ($kode_in)";
        $res_new = mysqli_query($koneksi, $sql_new_sks);
        $row_new = mysqli_fetch_assoc($res_new);
        $sks_baru = (int)$row_new['total_sks_baru'];

        // 3. Cek Batas
        $total_sks_nanti = $sks_existing + $sks_baru;
        $max_sks = 24;

        if ($total_sks_nanti > $max_sks) {
            $pesan = "GAGAL: Total SKS melebihi batas (Maksimal $max_sks SKS).<br>SKS Saat Ini: $sks_existing<br>Akan Diambil: $sks_baru<br>Total: $total_sks_nanti";
            $pesan_tipe = "danger";
        } else {
            // Lanjut Insert
            $sukses_count = 0;
            $error_count = 0;
            
            // Siapkan statement INSERT sekali saja di luar loop
            $stmt_insert = mysqli_prepare($koneksi, "INSERT IGNORE INTO krs (nim_mahasiswa, kode_matkul, semester) VALUES (?, ?, ?)");
            
            if ($stmt_insert) {
                foreach ($matkul_dipilih as $kode_mk) {
                    // $kode_mk dari POST sudah aman jika dibind, tapi validasi tetap baik
                    
                    mysqli_stmt_bind_param($stmt_insert, "sss", $nim, $kode_mk, $semester_aktif);
                    
                    if (mysqli_stmt_execute($stmt_insert)) {
                        // Cek apakah benar-benar ada baris yang bertambah (karena IGNORE)
                        // Note: mysqli_stmt_affected_rows kadang return -1 kalau error, atau 0 kalau ignore.
                        // Di sini kita asumsikan execute sukses = OK.
                        if (mysqli_stmt_affected_rows($stmt_insert) > 0) {
                            $sukses_count++;
                        }
                    } else {
                        $error_count++;
                    }
                }
                mysqli_stmt_close($stmt_insert);
            }
            
            if ($sukses_count > 0) {
                $pesan = "Berhasil mengambil $sukses_count mata kuliah. Total SKS Anda sekarang: $total_sks_nanti/$max_sks.";
                $pesan_tipe = "success";
            } else if ($error_count > 0) {
                $pesan = "Gagal mengambil mata kuliah.";
                $pesan_tipe = "warning";
            } else {
                $pesan = "Mata kuliah sudah diambil sebelumnya.";
                $pesan_tipe = "info";
            }
        }

    } else {
        $pesan = "Anda belum memilih mata kuliah apapun.";
        $pesan_tipe = "danger";
    }
}

// --- BAGIAN 2: DATA MATA KULIAH & KRS SAAT INI ---

// Ambil semua mata kuliah
$sql_mk = "SELECT * FROM mata_kuliah ORDER BY semester, nama_mk";
$hasil_mk = mysqli_query($koneksi, $sql_mk);

// Ambil mata kuliah yang SUDAH diambil mahasiswa ini di semester ini
$sql_krs_taken = "SELECT k.kode_matkul, m.sks FROM krs k JOIN mata_kuliah m ON k.kode_matkul = m.kode_mk WHERE k.nim_mahasiswa = ? AND k.semester = ?";
$stmt_taken = mysqli_prepare($koneksi, $sql_krs_taken);
mysqli_stmt_bind_param($stmt_taken, "ss", $nim, $semester_aktif);
mysqli_stmt_execute($stmt_taken);
$hasil_krs = mysqli_stmt_get_result($stmt_taken);

$mk_taken = [];
$total_sks_diambil = 0;
while ($row = mysqli_fetch_assoc($hasil_krs)) {
    $mk_taken[] = $row['kode_matkul'];
    $total_sks_diambil += $row['sks'];
}

// Set Title dan Header
$page_title = "Input KRS - Sistem Akademik";
include 'header.php';
?>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Kartu Rencana Studi (KRS)
                </h1>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">
                        SKS: <?php echo $total_sks_diambil; ?> / 24
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200 shadow-sm">
                        <?php echo htmlspecialchars($semester_aktif); ?>
                    </span>
                </div>
            </div>

            <div class="p-8">
                <?php if ($pesan) { 
                     $alertClass = $pesan_tipe == 'success' ? 'bg-green-100 border-green-400 text-green-700' : 
                                  ($pesan_tipe == 'warning' ? 'bg-yellow-100 border-yellow-400 text-yellow-700' : 
                                  ($pesan_tipe == 'info' ? 'bg-blue-100 border-blue-400 text-blue-700' : 'bg-red-100 border-red-400 text-red-700'));
                    echo "<div class='{$alertClass} border px-4 py-3 rounded relative mb-6' role='alert'>
                            <span class='block sm:inline'>{$pesan}</span>
                          </div>";
                } ?>

                <div class="mb-8 bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Halo, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?> <span class="text-gray-500 text-sm font-normal">(<?php echo htmlspecialchars($nim); ?>)</span></h5>
                    
                    <?php if (empty($nim)): ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        Akun Anda tidak memiliki NIM yang valid. Anda tidak dapat mengambil mata kuliah.
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-600 mb-4">Silakan pilih mata kuliah yang akan diambil semester ini (Maksimal 24 SKS).</p>
                    <?php endif; ?>
                    
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                    Progress SKS
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-blue-600">
                                    <?php echo $total_sks_diambil; ?> / 24
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                            <?php 
                            $persen_sks = ($total_sks_diambil / 24) * 100;
                            $warna_bar = $persen_sks > 80 ? 'bg-red-500' : ($persen_sks > 50 ? 'bg-yellow-500' : 'bg-green-500');
                            ?>
                            <div style="width:<?php echo $persen_sks; ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center <?php echo $warna_bar; ?>"></div>
                        </div>
                    </div>
                </div>

                <form action="input_krs.php" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="overflow-hidden border border-gray-200 rounded-lg mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">Pilih</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider w-24">Kode</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Mata Kuliah</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">SKS</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">Sem</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Jadwal</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-32">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                if (mysqli_num_rows($hasil_mk) > 0) {
                                    while ($mk = mysqli_fetch_assoc($hasil_mk)) {
                                        $kode = $mk['kode_mk'];
                                        $is_taken = in_array($kode, $mk_taken);
                                        $row_class = $is_taken ? 'bg-green-50' : 'hover:bg-gray-50'; // Use Tailwind classes
                                        
                                        echo "<tr class='$row_class transition-colors'>";
                                        
                                        // Kolom Checkbox
                                        echo "<td class='px-6 py-4 whitespace-nowrap text-center'>";
                                        if ($is_taken) {
                                            echo "<input type='checkbox' checked disabled class='h-4 w-4 text-green-600 rounded border-gray-300 focus:ring-green-500 cursor-not-allowed opacity-50'>";
                                        } else {
                                            echo "<input type='checkbox' name='matkul_diambil[]' value='{$kode}' class='h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer'>";
                                        }
                                        echo "</td>";
                                        
                                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono'>{$kode}</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$mk['nama_mk']}</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-bold'>{$mk['sks']}</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center'>{$mk['semester']}</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>
                                                <div class='font-medium'>{$mk['hari']}, {$mk['jam_mulai']}-{$mk['jam_selesai']}</div>
                                                <div class='text-xs text-gray-400'>R. {$mk['ruangan']}</div>
                                              </td>";
                                        
                                        // Kolom Status
                                        echo "<td class='px-6 py-4 whitespace-nowrap text-center'>";
                                        if ($is_taken) {
                                            echo "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200'>
                                                    <svg class='w-2 h-2 mr-1.5 text-green-500 fill-current' viewBox='0 0 8 8'><circle cx='4' cy='4' r='3' /></svg>
                                                    Terambil
                                                  </span>";
                                        } else {
                                            echo "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800'>Belum</span>";
                                        }
                                        echo "</td>";
                                        
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='px-6 py-8 text-center text-gray-500 italic'>Belum ada data mata kuliah.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-6">
                        <?php if(!empty($nim)): ?>
                            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Simpan Rencana Studi
                            </button>
                        <?php else: ?>
                             <button type="button" disabled class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-gray-400 cursor-not-allowed">
                                Simpan Rencana Studi
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php 
include 'footer.php';
mysqli_close($koneksi); 
?>
