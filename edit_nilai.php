<?php
// 1. Tentukan siapa yang boleh akses: Admin & Dosen
$allowed_roles = ['admin', 'dosen'];
include 'auth_guard.php';
include 'koneksi.php';

// Inisialisasi variabel
$pesan = '';
$pesan_tipe = '';
$id_nilai = 0;
$current_nim = '';
$current_mk = '';
$current_nilai = '';
$nip_dosen_login = ($_SESSION['role'] == 'dosen') ? $_SESSION['nip'] : '';

// --- BAGIAN 1: PROSES UPDATE (JIKA FORM DISUBMIT) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil semua data dari form
    $id_nilai = (int)$_POST['id_nilai'];
    $nim_mahasiswa = $_POST['nim_mahasiswa'];
    $kode_matkul = $_POST['kode_matkul'];
    $nilai_huruf = strtoupper($_POST['nilai_huruf']);

    // --- Validasi Keamanan POST (Dosen hanya boleh update MK yang diampu) ---
    $is_authorized_to_post = false;
    if ($_SESSION['role'] == 'admin') {
        $is_authorized_to_post = true;
    } else if ($_SESSION['role'] == 'dosen') {
        try {
            $stmt_check = $koneksi->prepare("SELECT * FROM dosen_pengampu WHERE nip_dosen = ? AND kode_matkul = ?");
            $stmt_check->execute([$nip_dosen_login, $kode_matkul]);
            if ($stmt_check->rowCount() > 0) {
                $is_authorized_to_post = true;
            }
        } catch (PDOException $e) {
             // Handle error
        }
    }
    // --- Akhir Validasi Keamanan POST ---

    if ($is_authorized_to_post && !empty($nim_mahasiswa) && !empty($kode_matkul) && !empty($nilai_huruf) && $id_nilai > 0) {
        
        try {
            // Buat kueri UPDATE
            $sql = "UPDATE nilai SET nim_mahasiswa = :nim, kode_matkul = :mk, nilai_huruf = :nilai 
                    WHERE id_nilai = :id";
            
            $stmt_update = $koneksi->prepare($sql);
            if ($stmt_update->execute([
                ':nim' => $nim_mahasiswa,
                ':mk' => $kode_matkul,
                ':nilai' => $nilai_huruf,
                ':id' => $id_nilai
            ])) {
                header("Location: tampil_transkrip.php?nim=" . $nim_mahasiswa . "&status=update_sukses");
                exit;
            }
        } catch (PDOException $e) {
            $pesan = "Error saat mengupdate data: " . $e->getMessage(); 
            $pesan_tipe = "danger";
        }
    } else if (!$is_authorized_to_post) {
        $pesan = "Akses ditolak: Anda tidak berhak mengubah nilai untuk mata kuliah tersebut."; $pesan_tipe = "danger";
    } else {
        $pesan = "Semua kolom wajib diisi."; $pesan_tipe = "warning";
    }

    // Isi variabel 'current' dari data POST agar form tetap terisi
    $current_nim = $nim_mahasiswa;
    $current_mk = $kode_matkul;
    $current_nilai = $nilai_huruf;

} 
// --- BAGIAN 2: TAMPILKAN FORM (JIKA HALAMAN DIBUKA BIASA) ---
else if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_nilai = (int)$_GET['id'];
    
    // --- Validasi Keamanan GET (Dosen hanya boleh load MK yang diampu) ---
    $sql_auth_check = "";
    $params_auth = [];

    if ($_SESSION['role'] == 'admin') {
        // Admin boleh load apa saja
        $sql_auth_check = "SELECT * FROM nilai WHERE id_nilai = ?";
        $params_auth = [$id_nilai];
    } else if ($_SESSION['role'] == 'dosen') {
        // Dosen hanya boleh load nilai dari MK yang diampu
        $sql_auth_check = "SELECT n.* FROM nilai n
                           JOIN dosen_pengampu dp ON n.kode_matkul = dp.kode_matkul
                           WHERE n.id_nilai = ? AND dp.nip_dosen = ?";
        $params_auth = [$id_nilai, $nip_dosen_login];
    }

    try {
        $stmt_current = $koneksi->prepare($sql_auth_check);
        $stmt_current->execute($params_auth);
        $data_nilai = $stmt_current->fetch(PDO::FETCH_ASSOC);

        if ($data_nilai) {
            $current_nim = $data_nilai['nim_mahasiswa'];
            $current_mk = $data_nilai['kode_matkul'];
            $current_nilai = $data_nilai['nilai_huruf'];
        } else {
            // Gagal otorisasi atau data tidak ada
            $_SESSION['error_message'] = "Maaf, Anda tidak memiliki hak akses untuk mengedit nilai tersebut atau data tidak ditemukan.";
            header("Location: index.php");
            exit;
        }
    } catch (PDOException $e) {
        die("Error fetching data: " . $e->getMessage());
    }

} else {
    die("Error: ID nilai tidak valid atau tidak disediakan.");
}

// --- BAGIAN 3: AMBIL DATA UNTUK DROPDOWN (DENGAN FILTER) ---
// Note: Variable lists need to be fetched with PDO too
$list_mhs = [];
$list_mk = [];

try {
    // Mahasiswa list
    $stmt_mhs = $koneksi->query("SELECT nim, nama_mahasiswa FROM mahasiswa ORDER BY nama_mahasiswa");
    $list_mhs = $stmt_mhs->fetchAll(PDO::FETCH_ASSOC);

    // Mata kuliah list logic
    $sql_mk = "";
    $params_mk = [];
    if ($_SESSION['role'] == 'admin') {
        $sql_mk = "SELECT kode_mk, nama_mk FROM mata_kuliah ORDER BY nama_mk";
    } else if ($_SESSION['role'] == 'dosen') {
        $sql_mk = "SELECT mk.kode_mk, mk.nama_mk 
                   FROM mata_kuliah mk
                   JOIN dosen_pengampu dp ON mk.kode_mk = dp.kode_matkul
                   WHERE dp.nip_dosen = ?
                   ORDER BY mk.nama_mk";
        $params_mk = [$nip_dosen_login];
    }
    
    if ($sql_mk) {
        $stmt_mk = $koneksi->prepare($sql_mk);
        $stmt_mk->execute($params_mk);
        $list_mk = $stmt_mk->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    // Handle error fetching lists
}

?>

$page_title = "Edit Nilai Mahasiswa";
?>
<?php include 'header.php'; ?>

    <div class="max-w-xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Nilai Mahasiswa
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

                <form action="edit_nilai.php" method="POST" class="space-y-6">
                    <input type="hidden" name="id_nilai" value="<?php echo $id_nilai; ?>">

                    <div>
                        <label for="nim_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">Mahasiswa</label>
                        <select id="nim_mahasiswa" name="nim_mahasiswa" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3 bg-white" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            <?php
                                foreach ($list_mhs as $mhs) {
                                    $nim = htmlspecialchars($mhs['nim']);
                                    $nama = htmlspecialchars($mhs['nama_mahasiswa']);
                                    $selected = ($nim == $current_nim) ? 'selected' : '';
                                    echo "<option value='{$nim}' {$selected}>{$nim} - {$nama}</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="kode_matkul" class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah</label>
                        <select id="kode_matkul" name="kode_matkul" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3 bg-white" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php
                                foreach ($list_mk as $mk) {
                                    $kode_mk = htmlspecialchars($mk['kode_mk']);
                                    $nama_mk = htmlspecialchars($mk['nama_mk']);
                                    $selected = ($kode_mk == $current_mk) ? 'selected' : '';
                                    echo "<option value='{$kode_mk}' {$selected}>{$kode_mk} - {$nama_mk}</option>";
                                }
                                if (empty($list_mk) && $_SESSION['role'] == 'dosen') {
                                    echo "<option value='' disabled>Anda tidak ditugaskan mengampu mata kuliah apapun.</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="nilai_huruf" class="block text-sm font-medium text-gray-700 mb-2">Nilai Huruf</label>
                        <input type="text" id="nilai_huruf" name="nilai_huruf" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 py-2 px-3" value="<?php echo htmlspecialchars($current_nilai); ?>" required>
                    </div>

                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                            Update Nilai
                        </button>
                        <a href="tampil_transkrip.php?nim=<?php echo $current_nim; ?>" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<?php // No close needed ?>