<?php
// 1. Panggil penjaga dan koneksi
include 'auth_guard.php';
include 'koneksi.php';

// Inisialisasi variabel
$pesan = '';
$pesan_tipe = '';
$nip = '';
$nama_dosen = '';
$email = '';

// --- BAGIAN 1: PROSES UPDATE (JIKA FORM DISUBMIT) ---
// --- BAGIAN 1: PROSES UPDATE (JIKA FORM DISUBMIT) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil semua data dari form
    $nip = $_POST['nip'];
    $nama_dosen = $_POST['nama_dosen'];
    $email = $_POST['email'];

    // Validasi
    if (!empty($nip) && !empty($nama_dosen)) {
        
        try {
            // Buat kueri UPDATE
            $sql = "UPDATE dosen 
                    SET nama_dosen = :nama, 
                        email = :email
                    WHERE nip = :nip";
            
            $stmt = $koneksi->prepare($sql);
            $params = [
                ':nama' => $nama_dosen,
                ':email' => $email,
                ':nip' => $nip
            ];

            // Jalankan kueri
            if ($stmt->execute($params)) {
                header("Location: tampil_dosen.php?status=update_sukses");
                exit;
            }
        } catch (PDOException $e) {
            $pesan = "Error saat mengupdate data: " . $e->getMessage();
            $pesan_tipe = "danger";
        }
    } else {
        $pesan = "NIP dan Nama Dosen wajib diisi.";
        $pesan_tipe = "warning";
    }
} 
// --- BAGIAN 2: TAMPILKAN FORM (JIKA HALAMAN DIBUKA BIASA) ---
else if (isset($_GET['nip']) && !empty($_GET['nip'])) {
    
    $nip = $_GET['nip'];
    
    try {
        // Ambil data dosen yang sekarang berdasarkan NIP
        $stmt = $koneksi->prepare("SELECT * FROM dosen WHERE nip = ?");
        $stmt->execute([$nip]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            $nama_dosen = $data['nama_dosen'];
            $email = $data['email'];
        } else {
            die("Error: Data dosen tidak ditemukan.");
        }
    } catch (PDOException $e) {
        die("Error database: " . $e->getMessage());
    }

} else {
    die("Error: NIP dosen tidak valid atau tidak disediakan.");
}


$page_title = "Edit Data Dosen";
include 'header.php'; 
?>

    <div class="max-w-xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Data Dosen
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

                <form action="edit_dosen.php" method="POST" class="space-y-6">
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                        <input type="text" id="nip" name="nip" class="w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 shadow-sm py-2 px-3 focus:ring-0 focus:border-gray-300" value="<?php echo htmlspecialchars($nip); ?>" readonly>
                        <p class="mt-1 text-sm text-gray-500">NIP tidak dapat diubah.</p>
                    </div>
                    
                    <div>
                        <label for="nama_dosen" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap Dosen</label>
                        <input type="text" id="nama_dosen" name="nama_dosen" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($nama_dosen); ?>" required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email (Opsional)</label>
                        <input type="email" id="email" name="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($email); ?>">
                    </div>

                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                            Update Data
                        </button>
                        <a href="tampil_dosen.php" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<?php // No close needed ?>