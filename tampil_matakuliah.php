<?php
// 1. Panggil penjaga dan koneksi
// 1. Panggil penjaga dan koneksi (Hak akses diatur di acl_config.php)
include 'auth_guard.php';
include 'koneksi.php';

// --- LOGIKA NOTIFIKASI ---
$pesan = '';
$pesan_tipe = '';

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'update_sukses':
            $pesan = "Data mata kuliah berhasil diperbarui.";
            $pesan_tipe = "success";
            break;
        case 'hapus_sukses':
            $pesan = "Data mata kuliah berhasil dihapus.";
            $pesan_tipe = "success";
            break;
        case 'hapus_gagal':
            if (isset($_GET['alasan']) && $_GET['alasan'] == 'nilai_ada') {
                $pesan = "Gagal menghapus! Mata kuliah ini sudah diambil oleh mahasiswa.";
            } else {
                $pesan = "Gagal menghapus data mata kuliah.";
            }
            $pesan_tipe = "danger";
            break;
    }
}

// Ambil data mata kuliah
if ($_SESSION['role'] == 'dosen') {
    // Jika Dosen, hanya tampilkan mata kuliah yang diajarkan
    $nip = $_SESSION['nip'];
    $sql = "SELECT mk.* 
            FROM mata_kuliah mk
            JOIN dosen_pengampu dp ON mk.kode_mk = dp.kode_matkul
            WHERE dp.nip_dosen = '$nip'
            ORDER BY mk.semester, mk.nama_mk";
} else {
    // Jika Admin, tampilkan semua
    $sql = "SELECT * FROM mata_kuliah ORDER BY semester, nama_mk";
}
$hasil = mysqli_query($koneksi, $sql);

$page_title = "Daftar Mata Kuliah";
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

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">📚 Daftar Mata Kuliah</h1>
            <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="tambah_matakuliah.php" class="mt-4 sm:mt-0 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition duration-300 shadow-md">
                + Tambah Mata Kuliah
            </a>
            <?php endif; ?>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Kode MK</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Nama Mata Kuliah</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">SKS</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Semester</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Jadwal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Ruangan</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-100 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        if (mysqli_num_rows($hasil) > 0) {
                            while ($data = mysqli_fetch_assoc($hasil)) {
                                echo "<tr class='hover:bg-gray-50 transition-colors duration-150'>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . htmlspecialchars($data['kode_mk']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>" . htmlspecialchars($data['nama_mk']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($data['sks']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($data['semester']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($data['hari'] . " " . $data['jam_mulai'] . "-" . $data['jam_selesai']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($data['ruangan']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-right text-sm font-medium'>";
                                        if ($_SESSION['role'] == 'admin') {
                                            echo "<a href='edit_matakuliah.php?kode=" . $data['kode_mk'] . "' class='text-indigo-600 hover:text-indigo-900 mr-4'>Edit</a>
                                                  <a href='hapus_matakuliah.php?kode=" . $data['kode_mk'] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"PERINGATAN: Menghapus mata kuliah hanya bisa jika belum ada mahasiswa yang mengambil. Lanjutkan?\");'>
                                                      Hapus
                                                  </a>";
                                        }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='px-6 py-4 text-center text-sm text-gray-500'>Tidak ada data mata kuliah.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<?php mysqli_close($koneksi); ?>