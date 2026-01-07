<?php
// 1. Tentukan siapa yang boleh akses halaman ini
$allowed_roles = ['admin'];

// 2. Panggil penjaga (sekarang ia akan mengecek $allowed_roles)
include 'auth_guard.php';

// 3. Sertakan file koneksi.php
include 'koneksi.php';

// Inisialisasi variabel untuk pesan
$pesan = '';
$pesan_tipe = ''; // Untuk menentukan warna alert (sukses/gagal)

// Cek apakah form telah disubmit (method POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil dan bersihkan data dari form
    $nim = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $nama_mahasiswa = mysqli_real_escape_string($koneksi, $_POST['nama_mahasiswa']);
    $fakultas = mysqli_real_escape_string($koneksi, $_POST['fakultas']);
    $prodi = mysqli_real_escape_string($koneksi, $_POST['prodi']);
    $angkatan = mysqli_real_escape_string($koneksi, $_POST['angkatan']);
    $foto = mysqli_real_escape_string($koneksi, $_POST['foto']);
    $dosen_wali_id = mysqli_real_escape_string($koneksi, $_POST['dosen_wali_id']);

    // Validasi dasar (pastikan NIM, nama, prodi, dan angkatan tidak kosong)
    if (!empty($nim) && !empty($nama_mahasiswa) && !empty($prodi) && !empty($angkatan) && !empty($fakultas)) {
        
        // 1. Validasi Format NIM (Harus Angka)
        if (!is_numeric($nim)) {
            $pesan = "Gagal: NIM harus berupa angka.";
            $pesan_tipe = "warning";
        } 
        // 2. Validasi Panjang NIM (Misal min 5 karakter)
        else if (strlen($nim) < 5) {
            $pesan = "Gagal: NIM terlalu pendek (minimal 5 digit).";
            $pesan_tipe = "warning";
        }
        else {
            // Lanjut proses insert
        // Buat kueri INSERT
        // Jika dosen wali tidak dipilih (kosong), masukkan NULL
        $dosen_wali_sql = !empty($dosen_wali_id) ? "'$dosen_wali_id'" : "NULL";
        
        $sql = "INSERT INTO mahasiswa (nim, nama_mahasiswa, fakultas, prodi, angkatan, foto, dosen_wali_id) 
                VALUES ('$nim', '$nama_mahasiswa', '$fakultas', '$prodi', '$angkatan', '$foto', $dosen_wali_sql)";
        
        // Jalankan kueri
        if (mysqli_query($koneksi, $sql)) {
            $pesan = "Mahasiswa baru berhasil ditambahkan!";
            $pesan_tipe = "success";
        } else {
            // Cek jika error karena duplikat NIM
            if (mysqli_errno($koneksi) == 1062) {
                $pesan = "Error: NIM '$nim' sudah terdaftar. Gunakan NIM lain.";
            } else {
                $pesan = "Error: " . mysqli_error($koneksi);
            }
            $pesan_tipe = "danger";
        }
        
        } // End else validasi NIM

    } else {
        $pesan = "NIM, Nama, Prodi, dan Angkatan wajib diisi.";
        $pesan_tipe = "warning";
    }
}
// Set Title dan Include Header
$page_title = "Tambah Mahasiswa Baru";
include 'header.php'; 
?>

    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Form Pendaftaran Mahasiswa Baru
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

                <form action="tambah_mahasiswa.php" method="POST" class="space-y-6">
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">NIM (Nomor Induk Mahasiswa)</label>
                        <input type="text" id="nim" name="nim" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3" placeholder="Contoh: 202301001" required>
                    </div>

                    <div>
                        <label for="nama_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="nama_mahasiswa" name="nama_mahasiswa" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3" placeholder="Contoh: Budi Santoso" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fakultas" class="block text-sm font-medium text-gray-700 mb-2">Fakultas</label>
                            <input type="text" id="fakultas" name="fakultas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3" placeholder="Contoh: Ilmu Komputer" required>
                        </div>
                        <div>
                            <label for="prodi" class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                            <input type="text" id="prodi" name="prodi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3" placeholder="Contoh: Teknik Informatika" required>
                        </div>
                    </div>

                    <div>
                        <label for="angkatan" class="block text-sm font-medium text-gray-700 mb-2">Angkatan</label>
                        <input type="number" id="angkatan" name="angkatan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3" placeholder="Contoh: 2023" min="2000" max="<?php echo date('Y') + 1; ?>" required>
                    </div>

                    <div>
                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">URL Foto (Opsional)</label>
                        <input type="text" id="foto" name="foto" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3" placeholder="https://...">
                    </div>

                    <div>
                        <label for="dosen_wali_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Dosen Wali (Opsional)</label>
                        <select id="dosen_wali_id" name="dosen_wali_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-white">
                            <option value="">-- Tidak Ada --</option>
                            <?php
                                // Ambil data dosen untuk dropdown
                                $sql_dosen = "SELECT nip, nama_dosen FROM dosen ORDER BY nama_dosen";
                                $hasil_dosen = mysqli_query($koneksi, $sql_dosen);
                                while ($dosen = mysqli_fetch_assoc($hasil_dosen)) {
                                    echo "<option value='" . $dosen['nip'] . "'>" . htmlspecialchars($dosen['nama_dosen']) . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            Daftarkan Mahasiswa
                        </button>
                        <a href="index.php" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            Kembali ke Home
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<?php
// Tutup koneksi di akhir
mysqli_close($koneksi);
?>