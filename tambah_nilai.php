<?php
// 1. Tentukan siapa yang boleh akses: Admin & Dosen
// 1. Tentukan siapa yang boleh akses (Diatur di acl_config.php)
include 'auth_guard.php'; 
include 'koneksi.php';
include 'csrf_helper.php';

// Inisialisasi variabel untuk pesan
$pesan = '';
$pesan_tipe = ''; // Untuk menentukan warna alert (sukses/gagal)

// Cek apakah form telah disubmit (method POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifikasi CSRF Token
    verify_csrf_token();
    
    // Ambil dan bersihkan data dari form
    $nim_mahasiswa = mysqli_real_escape_string($koneksi, $_POST['nim_mahasiswa']);
    $kode_matkul = mysqli_real_escape_string($koneksi, $_POST['kode_matkul']);
    $nilai_huruf = strtoupper(mysqli_real_escape_string($koneksi, $_POST['nilai_huruf'])); // Ubah nilai jadi huruf besar

    // Validasi dasar
    if (!empty($nim_mahasiswa) && !empty($kode_matkul) && !empty($nilai_huruf)) {
        
        // 1. Validasi Nilai Huruf (Harus A, B, C, D, atau E)
        $allowed_grades = ['A', 'B', 'C', 'D', 'E'];
        if (!in_array($nilai_huruf, $allowed_grades)) {
            $pesan = "Gagal: Nilai harus berupa huruf A, B, C, D, atau E.";
            $pesan_tipe = "warning";
        } else {
            // --- CEK KRS TERLEBIH DAHULU (Prepared Statement) ---
        include 'config_semester.php';
        $semester_aktif = get_active_semester($koneksi); 
        
        $stmt_cek = mysqli_prepare($koneksi, "SELECT * FROM krs WHERE nim_mahasiswa = ? AND kode_matkul = ? AND semester = ?");
        mysqli_stmt_bind_param($stmt_cek, "sss", $nim_mahasiswa, $kode_matkul, $semester_aktif);
        mysqli_stmt_execute($stmt_cek);
        $res_cek = mysqli_stmt_get_result($stmt_cek);
        
        if (mysqli_num_rows($res_cek) == 0) {
            $pesan = "Gagal: Mahasiswa ini belum mengambil mata kuliah tersebut di KRS semester ini.";
            $pesan_tipe = "danger";
        } else {
            // Jika ada di KRS, baru boleh input nilai
             
            // Buat kueri INSERT (Prepared Statement)
            $stmt_insert = mysqli_prepare($koneksi, "INSERT INTO nilai (nim_mahasiswa, kode_matkul, nilai_huruf) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt_insert, "sss", $nim_mahasiswa, $kode_matkul, $nilai_huruf);
            
            // Jalankan kueri
            if (mysqli_stmt_execute($stmt_insert)) {
                $pesan = "Nilai berhasil ditambahkan!";
                $pesan_tipe = "success";
            } else {
                $pesan = "Error: " . mysqli_error($koneksi);
                $pesan_tipe = "danger";
            }
            mysqli_stmt_close($stmt_insert);
        }
        mysqli_stmt_close($stmt_cek);
        
        } // End else validasi nilai huruf

    } else {
        $pesan = "Semua kolom harus diisi.";
        $pesan_tipe = "warning";
    }
}
?>

<?php
// Set Title dan Include Header
$page_title = "Tambah Nilai Mahasiswa";
include 'header.php'; 
?>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Form Tambah Nilai
                </h1>
            </div>
            
            <div class="p-8">
                <?php
                if ($pesan) {
                     $alertClass = $pesan_tipe == 'success' ? 'bg-green-100 border-green-400 text-green-700' : 
                                  ($pesan_tipe == 'warning' ? 'bg-yellow-100 border-yellow-400 text-yellow-700' : 'bg-red-100 border-red-400 text-red-700');
                    echo "<div class='{$alertClass} border px-4 py-3 rounded relative mb-6' role='alert'>
                            <span class='block sm:inline'>{$pesan}</span>
                          </div>";
                }
                ?>

                <form action="tambah_nilai.php" method="POST" class="space-y-6">
                    
                    <!-- CSRF Token -->
                    <?php echo csrf_field(); ?>

                    <div>
                        <label for="nim_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">Pilih Mahasiswa</label>
                        <select id="nim_mahasiswa" name="nim_mahasiswa" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-white" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            <?php
                                $sql_mhs = "SELECT nim, nama_mahasiswa FROM mahasiswa ORDER BY nama_mahasiswa";
                                $hasil_mhs = mysqli_query($koneksi, $sql_mhs);
                                while ($mhs = mysqli_fetch_assoc($hasil_mhs)) {
                                    echo "<option value='" . $mhs['nim'] . "'>" . htmlspecialchars($mhs['nim'] . " - " . $mhs['nama_mahasiswa']) . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="kode_matkul" class="block text-sm font-medium text-gray-700 mb-2">Pilih Mata Kuliah</label>
                        <select id="kode_matkul" name="kode_matkul" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-white" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php
                                $sql_mk = "";
                                if ($_SESSION['role'] == 'admin') {
                                    $sql_mk = "SELECT kode_mk, nama_mk FROM mata_kuliah ORDER BY nama_mk";
                                } else if ($_SESSION['role'] == 'dosen') {
                                    $nip_dosen_login = $_SESSION['nip'];
                                    $sql_mk = "SELECT mk.kode_mk, mk.nama_mk FROM mata_kuliah mk JOIN dosen_pengampu dp ON mk.kode_mk = dp.kode_matkul WHERE dp.nip_dosen = '$nip_dosen_login' ORDER BY mk.nama_mk";
                                }
                                $hasil_mk = mysqli_query($koneksi, $sql_mk);

                                if (mysqli_num_rows($hasil_mk) > 0) {
                                    while ($mk = mysqli_fetch_assoc($hasil_mk)) {
                                        echo "<option value='" . $mk['kode_mk'] . "'>" . htmlspecialchars($mk['kode_mk'] . " - " . $mk['nama_mk']) . "</option>";
                                    }
                                } else if ($_SESSION['role'] == 'dosen') {
                                    echo "<option value='' disabled>Anda belum ditugaskan mengampu mata kuliah apapun.</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="nilai_huruf" class="block text-sm font-medium text-gray-700 mb-2">Nilai Huruf</label>
                        <input type="text" id="nilai_huruf" name="nilai_huruf" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3" placeholder="Contoh: A, B, C" required>
                        <p class="mt-1 text-sm text-gray-500">Masukkan nilai dalam huruf kapital (A-E).</p>
                    </div>

                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            Simpan Nilai
                        </button>
                        <a href="index.php" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            Kembali ke Home
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
include 'footer.php';
// Tutup koneksi di akhir
mysqli_close($koneksi);
?>
