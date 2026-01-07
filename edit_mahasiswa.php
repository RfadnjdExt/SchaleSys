<?php
// 1. Panggil penjaga dan koneksi
// 1. Panggil penjaga dan koneksi
include 'auth_guard.php';
include 'koneksi.php';
include 'csrf_helper.php';

// Inisialisasi variabel
$pesan = '';
$pesan_tipe = '';
$nim = '';
$nama_mahasiswa = '';
$fakultas = '';
$prodi = '';
$angkatan = '';
$foto = '';
$current_dosen_wali = '';

// --- BAGIAN 1: PROSES UPDATE (JIKA FORM DISUBMIT) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verifikasi CSRF Token
    verify_csrf_token();
    
    // Ambil semua data dari form
    $nim = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $nama_mahasiswa = mysqli_real_escape_string($koneksi, $_POST['nama_mahasiswa']);
    $fakultas = mysqli_real_escape_string($koneksi, $_POST['fakultas']);
    $prodi = mysqli_real_escape_string($koneksi, $_POST['prodi']);
    $angkatan = (int)$_POST['angkatan'];
    $foto = mysqli_real_escape_string($koneksi, $_POST['foto']);
    $dosen_wali_id = mysqli_real_escape_string($koneksi, $_POST['dosen_wali_id']);
    $dosen_wali_sql = !empty($dosen_wali_id) ? "'$dosen_wali_id'" : "NULL";

    // Validasi
    if (!empty($nim) && !empty($nama_mahasiswa) && !empty($prodi) && $angkatan > 0 && !empty($fakultas)) {
        
        // Buat kueri UPDATE
        $sql = "UPDATE mahasiswa 
                SET nama_mahasiswa = '$nama_mahasiswa', 
                    fakultas = '$fakultas',
                    prodi = '$prodi', 
                    angkatan = $angkatan,
                    dosen_wali_id = $dosen_wali_sql,
                    foto = '$foto'
                WHERE nim = '$nim'";
        
        // Jalankan kueri
        if (mysqli_query($koneksi, $sql)) {
            // Jika berhasil, redirect kembali ke daftar mahasiswa
            header("Location: tampil_mahasiswa.php?status=update_sukses");
            exit;
        } else {
            $pesan = "Error saat mengupdate data: " . mysqli_error($koneksi);
            $pesan_tipe = "danger";
        }
    } else {
        $pesan = "Semua kolom (kecuali Dosen Wali) wajib diisi.";
        $pesan_tipe = "warning";
    }
} 
// --- BAGIAN 2: TAMPILKAN FORM (JIKA HALAMAN DIBUKA BIASA) ---
else if (isset($_GET['nim']) && !empty($_GET['nim'])) {
    
    $nim = mysqli_real_escape_string($koneksi, $_GET['nim']);
    
    // Ambil data mahasiswa yang sekarang berdasarkan NIM
    $sql_current = "SELECT * FROM mahasiswa WHERE nim = '$nim'";
    $hasil_current = mysqli_query($koneksi, $sql_current);
    
    if (mysqli_num_rows($hasil_current) == 1) {
        $data = mysqli_fetch_assoc($hasil_current);
        $nama_mahasiswa = $data['nama_mahasiswa'];
        $fakultas = $data['fakultas'];
        $prodi = $data['prodi'];
        $angkatan = $data['angkatan'];
        $foto = $data['foto'];
        $current_dosen_wali = $data['dosen_wali_id'];
    } else {
        die("Error: Data mahasiswa tidak ditemukan.");
    }

} else {
    die("Error: NIM mahasiswa tidak valid atau tidak disediakan.");
}

// Ambil data dosen untuk dropdown (selalu diperlukan)
$hasil_dosen = mysqli_query($koneksi, "SELECT nip, nama_dosen FROM dosen ORDER BY nama_dosen");


$page_title = "Edit Data Mahasiswa";
include 'header.php'; 
?>

    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit Data Mahasiswa
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

                <form action="edit_mahasiswa.php" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">NIM (Nomor Induk Mahasiswa)</label>
                        <input type="text" id="nim" name="nim" class="w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 shadow-sm py-2 px-3 focus:ring-0 focus:border-gray-300" value="<?php echo htmlspecialchars($nim); ?>" readonly>
                        <p class="mt-1 text-sm text-gray-500">NIM tidak dapat diubah.</p>
                    </div>
                    
                    <div>
                        <label for="nama_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="nama_mahasiswa" name="nama_mahasiswa" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($nama_mahasiswa); ?>" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fakultas" class="block text-sm font-medium text-gray-700 mb-2">Fakultas</label>
                            <input type="text" id="fakultas" name="fakultas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($fakultas); ?>" required>
                        </div>

                        <div>
                            <label for="prodi" class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                            <input type="text" id="prodi" name="prodi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($prodi); ?>" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="angkatan" class="block text-sm font-medium text-gray-700 mb-2">Angkatan</label>
                        <input type="number" id="angkatan" name="angkatan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($angkatan); ?>" min="2000" max="<?php echo date('Y') + 1; ?>" required>
                    </div>

                    <div>
                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">URL Foto (Opsional)</label>
                        <input type="text" id="foto" name="foto" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($foto); ?>" placeholder="https://...">
                    </div>

                    <div>
                        <label for="dosen_wali_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Dosen Wali (Opsional)</label>
                        <select id="dosen_wali_id" name="dosen_wali_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3 bg-white">
                            <option value="">-- Tidak Ada --</option>
                            <?php
                                while ($dosen = mysqli_fetch_assoc($hasil_dosen)) {
                                    $nip = htmlspecialchars($dosen['nip']);
                                    $nama_dosen = htmlspecialchars($dosen['nama_dosen']);
                                    $selected = ($nip == $current_dosen_wali) ? 'selected' : '';
                                    echo "<option value='{$nip}' {$selected}>{$nama_dosen}</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                            Update Data
                        </button>
                        <a href="tampil_mahasiswa.php" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<?php
mysqli_close($koneksi);
?>