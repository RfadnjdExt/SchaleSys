<?php
// 1. Tentukan siapa yang boleh akses halaman ini
// 1. Tentukan siapa yang boleh akses: Admin & Dosen (Diatur di acl_config.php)

// 2. Panggil penjaga
include 'auth_guard.php';

// 3. Sertakan file koneksi.php
include 'koneksi.php';

// --- LOGIKA NOTIFIKASI BARU ---
$pesan = '';
$pesan_tipe = '';

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'update_sukses':
            $pesan = "Data mahasiswa berhasil diperbarui.";
            $pesan_tipe = "success";
            break;
        case 'hapus_sukses':
            $pesan = "Data mahasiswa berhasil dihapus.";
            $pesan_tipe = "success";
            break;
        case 'hapus_gagal':
            if (isset($_GET['alasan']) && $_GET['alasan'] == 'nilai_ada') {
                $pesan = "Gagal menghapus! Mahasiswa masih memiliki data nilai.";
            } else {
                $pesan = "Gagal menghapus data mahasiswa.";
            }
            $pesan_tipe = "danger";
            break;
    }
}

$page_title = "Data Mahasiswa";
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
            <h1 class="text-3xl font-bold text-gray-900">📋 Daftar Mahasiswa</h1>
            <a href="index.php" class="mt-4 sm:mt-0 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition duration-300">
                ← Kembali ke Home
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Foto</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">NIM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Fakultas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Prodi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Angkatan</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-100 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        // Konfigurasi Pagination
                        $limit = 20; 
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $page = $page < 1 ? 1 : $page;
                        $start = ($page - 1) * $limit;

                        $sql_count = "SELECT COUNT(nim) AS total FROM mahasiswa";
                        $result_count = mysqli_query($koneksi, $sql_count);
                        $row_count = mysqli_fetch_assoc($result_count);
                        $total_pages = ceil($row_count['total'] / $limit);

                        $sql = "SELECT nim, nama_mahasiswa, fakultas, prodi, angkatan, foto FROM mahasiswa ORDER BY nim ASC LIMIT $start, $limit";
                        $hasil = mysqli_query($koneksi, $sql);

                        if (mysqli_num_rows($hasil) > 0) {
                            while ($data = mysqli_fetch_assoc($hasil)) {
                                echo "<tr class='hover:bg-gray-50 transition-colors duration-150'>";
                                
                                $foto_url = !empty($data['foto']) ? $data['foto'] : 'https://via.placeholder.com/50';
                                echo "<td class='px-6 py-4 whitespace-nowrap'>
                                        <div class='flex-shrink-0 h-10 w-10'>
                                            <img class='h-10 w-10 rounded-full object-cover border border-gray-300 shadow-sm' src='{$foto_url}' alt='Foto'>
                                        </div>
                                      </td>";

                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($data['nim']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . htmlspecialchars($data['nama_mahasiswa']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($data['fakultas']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($data['prodi']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($data['angkatan']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-right text-sm font-medium'>";
                                        if ($_SESSION['role'] == 'admin') {
                                            echo "<a href='edit_mahasiswa.php?nim=" . $data['nim'] . "' class='text-indigo-600 hover:text-indigo-900 mr-4'>Edit</a>
                                                  <a href='hapus_mahasiswa.php?nim=" . $data['nim'] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"PERINGATAN: Menghapus mahasiswa hanya bisa jika ia tidak memiliki data nilai. Lanjutkan?\");'>
                                                      Hapus
                                                  </a>";
                                        }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='px-6 py-4 text-center text-sm text-gray-500'>Tidak ada data mahasiswa.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="mt-6 flex justify-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        Previous
                    </a>
                <?php endif; ?>

                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-gray-50 text-sm font-medium text-gray-700">
                    Page <?php echo $page; ?> of <?php echo $total_pages; ?>
                </span>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        Next
                    </a>
                <?php endif; ?>
            </nav>
        </div>
        <?php endif; ?>

        <?php mysqli_close($koneksi); ?>
    </div>


<?php include 'footer.php'; ?>
