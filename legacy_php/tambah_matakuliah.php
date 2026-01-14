<?php
// Letakkan di baris paling pertama, SEBELUM 'include koneksi.php'
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
    $kode_mk = $_POST['kode_mk'];
    $nama_mk = $_POST['nama_mk'];
    $sks = (int)$_POST['sks']; 
    $semester = (int)$_POST['semester'];
    
    // Data Jadwal
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $ruangan = $_POST['ruangan'];

    // Validasi dasar
    if (!empty($kode_mk) && !empty($nama_mk) && $sks > 0 && $semester > 0) {
        
        try {
            // Buat kueri INSERT
            $sql = "INSERT INTO mata_kuliah (kode_mk, nama_mk, sks, semester, hari, jam_mulai, jam_selesai, ruangan) 
                    VALUES (:kode, :nama, :sks, :smt, :hari, :mulai, :selesai, :ruang)";
            
            $stmt = $koneksi->prepare($sql);
            
            // Jalankan kueri
            if ($stmt->execute([
                ':kode' => $kode_mk,
                ':nama' => $nama_mk,
                ':sks' => $sks,
                ':smt' => $semester,
                ':hari' => $hari,
                ':mulai' => $jam_mulai,
                ':selesai' => $jam_selesai,
                ':ruang' => $ruangan
            ])) {
                $pesan = "Mata kuliah baru berhasil ditambahkan!";
                $pesan_tipe = "success";
            }
        } catch (PDOException $e) {
            // Error handling
            if ($e->getCode() == 23000) { // Integrity constraint violation (Duplicate key)
                $pesan = "Error: Kode MK '$kode_mk' sudah terdaftar. Gunakan kode lain.";
            } else {
                $pesan = "Error: " . $e->getMessage();
            }
            $pesan_tipe = "danger";
        }

    } else {
        $pesan = "Semua kolom wajib diisi dengan benar.";
        $pesan_tipe = "warning";
    }
}

$page_title = "Tambah Mata Kuliah Baru";
include 'header.php'; 
?>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Form Tambah Mata Kuliah Baru
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

                <form action="tambah_matakuliah.php" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h5 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Informasi Mata Kuliah</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="kode_mk" class="block text-sm font-medium text-gray-700 mb-2">Kode Mata Kuliah</label>
                                <input type="text" id="kode_mk" name="kode_mk" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 px-3" placeholder="Contoh: IF101" required>
                            </div>
                            <div>
                                <label for="nama_mk" class="block text-sm font-medium text-gray-700 mb-2">Nama Mata Kuliah</label>
                                <input type="text" id="nama_mk" name="nama_mk" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 px-3" placeholder="Contoh: Algoritma" required>
                            </div>
 
                            <div>
                                <label for="sks" class="block text-sm font-medium text-gray-700 mb-2">SKS</label>
                                <input type="number" id="sks" name="sks" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 px-3" min="1" max="6" required>
                            </div>
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                                <input type="number" id="semester" name="semester" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 px-3" min="1" max="8" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h5 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Jadwal Perkuliahan</h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                            <div>
                                <label for="hari" class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                                <select id="hari" name="hari" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 px-3 bg-white" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>
                            </div>
                            <div>
                                <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                                <input type="time" id="jam_mulai" name="jam_mulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 px-3" required>
                            </div>
                            <div>
                                <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai</label>
                                <input type="time" id="jam_selesai" name="jam_selesai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 px-3" required>
                            </div>
                        </div>
                        
                        <div>
                            <label for="ruangan" class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                            <input type="text" id="ruangan" name="ruangan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 px-3" placeholder="Contoh: R.204" required>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            Simpan Mata Kuliah
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
<?php // No close needed
?>