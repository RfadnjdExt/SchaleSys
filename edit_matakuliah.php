<?php
// 1. Panggil penjaga dan koneksi
include 'auth_guard.php';
include 'koneksi.php';
include 'csrf_helper.php';

// Inisialisasi variabel
$pesan = '';
$pesan_tipe = '';
$kode_mk = '';
$nama_mk = '';
$sks = '';
$semester = '';

// --- BAGIAN 1: PROSES UPDATE (JIKA FORM DISUBMIT) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verifikasi CSRF Token
    verify_csrf_token();

    // Inisialisasi variabel jadwal
    $hari = '';
    $jam_mulai = '';
    $jam_selesai = '';
    $ruangan = '';

    // Ambil semua data dari form
    $kode_mk = mysqli_real_escape_string($koneksi, $_POST['kode_mk']);
    $nama_mk = mysqli_real_escape_string($koneksi, $_POST['nama_mk']);
    $sks = (int)$_POST['sks'];
    $semester = (int)$_POST['semester'];
    
    // Data Jadwal
    $hari = mysqli_real_escape_string($koneksi, $_POST['hari']);
    $jam_mulai = mysqli_real_escape_string($koneksi, $_POST['jam_mulai']);
    $jam_selesai = mysqli_real_escape_string($koneksi, $_POST['jam_selesai']);
    $ruangan = mysqli_real_escape_string($koneksi, $_POST['ruangan']);

    // Validasi
    if (!empty($kode_mk) && !empty($nama_mk) && $sks > 0 && $semester > 0) {
        
        // Buat kueri UPDATE
        $sql = "UPDATE mata_kuliah 
                SET nama_mk = '$nama_mk', 
                    sks = $sks, 
                    semester = $semester,
                    hari = '$hari',
                    jam_mulai = '$jam_mulai',
                    jam_selesai = '$jam_selesai',
                    ruangan = '$ruangan'
                WHERE kode_mk = '$kode_mk'";
        
        // Jalankan kueri
        if (mysqli_query($koneksi, $sql)) {
            header("Location: tampil_matakuliah.php?status=update_sukses");
            exit;
        } else {
            $pesan = "Error saat mengupdate data: " . mysqli_error($koneksi);
            $pesan_tipe = "danger";
        }
    } else {
        $pesan = "Semua kolom wajib diisi dengan benar.";
        $pesan_tipe = "warning";
    }
} 
// --- BAGIAN 2: TAMPILKAN FORM (JIKA HALAMAN DIBUKA BIASA) ---
else if (isset($_GET['kode']) && !empty($_GET['kode'])) {
    
    $kode_mk = mysqli_real_escape_string($koneksi, $_GET['kode']);
    
    // Ambil data MK yang sekarang berdasarkan Kode MK
    $sql_current = "SELECT * FROM mata_kuliah WHERE kode_mk = '$kode_mk'";
    $hasil_current = mysqli_query($koneksi, $sql_current);
    
    if (mysqli_num_rows($hasil_current) == 1) {
        $data = mysqli_fetch_assoc($hasil_current);
        $nama_mk = $data['nama_mk'];
        $sks = $data['sks'];
        $semester = $data['semester'];
        // Jadwal
        $hari = $data['hari'];
        $jam_mulai = $data['jam_mulai'];
        $jam_selesai = $data['jam_selesai'];
        $ruangan = $data['ruangan'];
    } else {
        die("Error: Data mata kuliah tidak ditemukan.");
    }

} else {
    die("Error: Kode mata kuliah tidak valid atau tidak disediakan.");
}


$page_title = "Edit Data Mata Kuliah";
include 'header.php'; 
?>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Data Mata Kuliah
                </h1>
            </div>
            
            <div class="p-8">
                <?php if ($pesan) { 
                     $alertClass = $pesan_tipe == 'success' ? 'bg-green-100 border-green-400 text-green-700' : 
                                  ($pesan_tipe == 'warning' ? 'bg-yellow-100 border-yellow-400 text-yellow-700' : 'bg-red-100 border-red-400 text-red-700');
                    echo "<div class='{$alertClass} border px-4 py-3 rounded relative mb-6' role='alert'>
                            <span class='block sm:inline'>{$pesan}</span>
                          </div>";
                } ?>

                <form action="edit_matakuliah.php" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h5 class="text-md font-semibold text-gray-800 mb-4 uppercase tracking-wide border-b border-gray-200 pb-2">Informasi Mata Kuliah</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="kode_mk" class="block text-sm font-medium text-gray-700 mb-2">Kode Mata Kuliah</label>
                                <input type="text" id="kode_mk" name="kode_mk" class="w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 shadow-sm py-2 px-3 focus:ring-0 focus:border-gray-300" value="<?php echo htmlspecialchars($kode_mk); ?>" readonly>
                                <p class="mt-1 text-xs text-gray-400">Tidak dapat diubah.</p>
                            </div>
                            <div>
                                <label for="nama_mk" class="block text-sm font-medium text-gray-700 mb-2">Nama Mata Kuliah</label>
                                <input type="text" id="nama_mk" name="nama_mk" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($nama_mk); ?>" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="sks" class="block text-sm font-medium text-gray-700 mb-2">SKS</label>
                                <input type="number" id="sks" name="sks" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($sks); ?>" min="1" max="6" required>
                            </div>
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                                <input type="number" id="semester" name="semester" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($semester); ?>" min="1" max="8" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h5 class="text-md font-semibold text-gray-800 mb-4 uppercase tracking-wide border-b border-gray-200 pb-2">Jadwal Perkuliahan</h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="hari" class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                                <select id="hari" name="hari" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3 bg-white" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <?php
                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                    foreach ($days as $day) {
                                        $selected = ($day == $hari) ? 'selected' : '';
                                        echo "<option value='$day' $selected>$day</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                                <input type="time" id="jam_mulai" name="jam_mulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($jam_mulai); ?>" required>
                            </div>
                            <div>
                                <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai</label>
                                <input type="time" id="jam_selesai" name="jam_selesai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($jam_selesai); ?>" required>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="ruangan" class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                            <input type="text" id="ruangan" name="ruangan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($ruangan); ?>" required>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                            Update Data
                        </button>
                        <a href="tampil_matakuliah.php" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
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