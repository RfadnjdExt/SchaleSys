<?php
// 1. Tentukan siapa yang boleh akses: HANYA Dosen
// 1. Tentukan siapa yang boleh akses (Diatur di acl_config.php)
include 'auth_guard.php'; 
include 'koneksi.php';

// 2. Ambil NIP Dosen yang sedang login dari Session
// Kita bisa yakin $_SESSION['nip'] ada karena auth_guard
// dan login.php sudah mengaturnya.
$nip_dosen_wali = $_SESSION['nip'];

// 3. Ambil data Dosen itu sendiri (untuk judul halaman)
$sql_dosen = "SELECT nama_dosen FROM dosen WHERE nip = '$nip_dosen_wali'";
$hasil_dosen = mysqli_query($koneksi, $sql_dosen);
$data_dosen = mysqli_fetch_assoc($hasil_dosen);
$nama_dosen = $data_dosen ? $data_dosen['nama_dosen'] : 'Dosen';

// 4. Ambil daftar mahasiswa perwalian
$sql_mahasiswa = "SELECT nim, nama_mahasiswa, prodi, angkatan 
                  FROM mahasiswa 
                  WHERE dosen_wali_id = '$nip_dosen_wali' 
                  ORDER BY angkatan DESC, nama_mahasiswa ASC";
$hasil_mahasiswa = mysqli_query($koneksi, $sql_mahasiswa);

$page_title = "Dasbor Dosen Wali";
include 'header.php'; 
?>

    <div class="max-w-7xl mx-auto px-4 py-8">
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-600 to-blue-700 px-6 py-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Dasbor Dosen Wali
                </h1>
                <p class="text-cyan-100 text-sm mt-1">Selamat datang, <?php echo htmlspecialchars($nama_dosen); ?></p>
            </div>
            
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 border-l-4 border-cyan-500 pl-3">Daftar Mahasiswa Perwalian Anda</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Total: <?php echo mysqli_num_rows($hasil_mahasiswa); ?> Mahasiswa
                    </span>
                </div>

                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider w-32">NIM</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Mahasiswa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Program Studi</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-24">Angkatan</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-40">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            if (mysqli_num_rows($hasil_mahasiswa) > 0) {
                                while ($data = mysqli_fetch_assoc($hasil_mahasiswa)) {
                                    echo "<tr class='hover:bg-gray-50 transition-colors'>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono'>{$data['nim']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$data['nama_mahasiswa']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>{$data['prodi']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500'>{$data['angkatan']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium'>
                                            <a href='tampil_transkrip.php?nim=" . $data['nim'] . "' class='inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors'>
                                                <svg class='w-4 h-4 mr-1' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'></path></svg>
                                                Lihat Transkrip
                                            </a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='px-6 py-8 text-center text-gray-500 italic'>Anda belum memiliki mahasiswa perwalian.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<?php mysqli_close($koneksi); ?>