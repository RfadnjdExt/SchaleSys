<?php
// 1. Panggil penjaga dan koneksi
// 1. Panggil penjaga dan koneksi (Hak admin diatur di acl_config.php)
include 'auth_guard.php';
include 'koneksi.php';

// --- LOGIKA NOTIFIKASI ---
$pesan = '';
$pesan_tipe = '';

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'update_sukses':
            $pesan = "Data dosen berhasil diperbarui.";
            $pesan_tipe = "success";
            break;
        case 'hapus_sukses':
            $pesan = "Data dosen berhasil dihapus.";
            $pesan_tipe = "success";
            break;
        case 'hapus_gagal':
            if (isset($_GET['alasan']) && $_GET['alasan'] == 'dosen_wali') {
                $pesan = "Gagal menghapus! Dosen ini masih terdaftar sebagai Dosen Wali untuk mahasiswa.";
            } else {
                $pesan = "Gagal menghapus data dosen.";
            }
            $pesan_tipe = "danger";
            break;
    }
}

// Ambil semua data dosen
$sql = "SELECT * FROM dosen ORDER BY nama_dosen";
$hasil = mysqli_query($koneksi, $sql);

$page_title = "Daftar Dosen";
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
            <h1 class="text-3xl font-bold text-gray-900">🧑‍🏫 Daftar Dosen</h1>
            <a href="tambah_dosen.php" class="mt-4 sm:mt-0 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 shadow-md">
                + Tambah Dosen
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">NIP</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Nama Dosen</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-100 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        if (mysqli_num_rows($hasil) > 0) {
                            while ($data = mysqli_fetch_assoc($hasil)) {
                                echo "<tr class='hover:bg-gray-50 transition-colors duration-150'>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . htmlspecialchars($data['nip']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>" . htmlspecialchars($data['nama_dosen']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($data['email']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-right text-sm font-medium'>
                                        <a href='edit_dosen.php?nip=" . $data['nip'] . "' class='text-indigo-600 hover:text-indigo-900 mr-4'>Edit</a>
                                        <a href='hapus_dosen.php?nip=" . $data['nip'] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"PERINGATAN: Menghapus dosen hanya bisa jika ia tidak terdaftar sebagai Dosen Wali. Lanjutkan?\");'>
                                            Hapus
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='px-6 py-4 text-center text-sm text-gray-500'>Tidak ada data dosen.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<?php mysqli_close($koneksi); ?>