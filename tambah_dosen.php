<?php
// 1. Panggil penjaga dan koneksi
include 'auth_guard.php';
include 'koneksi.php';

$pesan = '';
$pesan_tipe = '';

// Cek jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama_dosen = mysqli_real_escape_string($koneksi, $_POST['nama_dosen']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);

    // Validasi dasar
    if (!empty($nip) && !empty($nama_dosen)) {
        
        // Buat kueri INSERT
        $sql = "INSERT INTO dosen (nip, nama_dosen, email) 
                VALUES ('$nip', '$nama_dosen', '$email')";
        
        // Jalankan kueri
        if (mysqli_query($koneksi, $sql)) {
            $pesan = "Dosen baru berhasil ditambahkan!";
            $pesan_tipe = "success";
        } else {
            // Cek jika error karena duplikat NIP
            if (mysqli_errno($koneksi) == 1062) {
                $pesan = "Error: NIP '$nip' sudah terdaftar. Gunakan NIP lain.";
            } else {
                $pesan = "Error: " . mysqli_error($koneksi);
            }
            $pesan_tipe = "danger";
        }

    } else {
        $pesan = "NIP dan Nama Dosen wajib diisi.";
        $pesan_tipe = "warning";
    }
}

$page_title = "Tambah Dosen Baru";
include 'header.php'; 
?>



    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-6 py-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                   <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Form Tambah Dosen Baru
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

                <form action="tambah_dosen.php" method="POST" class="space-y-6">
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" id="nip" name="nip" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3" placeholder="Contoh: 19850101..." required>
                    </div>

                    <div>
                        <label for="nama_dosen" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap Dosen (beserta gelar)</label>
                        <input type="text" id="nama_dosen" name="nama_dosen" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3" required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email (Opsional)</label>
                        <input type="email" id="email" name="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3" placeholder="dosen@kampus.ac.id">
                    </div>

                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            Simpan Data Dosen
                        </button>
                        <a href="tampil_dosen.php" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            Batal & Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<?php mysqli_close($koneksi); ?>