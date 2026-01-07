<?php
// 1. Tentukan siapa yang boleh akses: HANYA Admin
$allowed_roles = ['admin'];
include 'auth_guard.php'; 
include 'koneksi.php';

$pesan = '';
$pesan_tipe = '';

// --- Logika TAMBAH Penugasan (jika form disubmit) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nip_dosen = mysqli_real_escape_string($koneksi, $_POST['nip_dosen']);
    $kode_matkul = mysqli_real_escape_string($koneksi, $_POST['kode_matkul']);

    if (!empty($nip_dosen) && !empty($kode_matkul)) {
        $sql_insert = "INSERT INTO dosen_pengampu (nip_dosen, kode_matkul) VALUES ('$nip_dosen', '$kode_matkul')";
        
        if (mysqli_query($koneksi, $sql_insert)) {
            $pesan = "Dosen berhasil ditugaskan ke mata kuliah.";
            $pesan_tipe = "success";
        } else if (mysqli_errno($koneksi) == 1062) { // Error duplikat
            $pesan = "Gagal: Dosen tersebut sudah ditugaskan ke mata kuliah ini.";
            $pesan_tipe = "warning";
        } else {
            $pesan = "Error: " . mysqli_error($koneksi);
            $pesan_tipe = "danger";
        }
    }
}

// --- Logika HAPUS Penugasan (jika ada parameter ?hapus=) ---
if (isset($_GET['hapus']) && !empty($_GET['hapus'])) {
    $id_pengampu = (int)$_GET['hapus'];
    $sql_delete = "DELETE FROM dosen_pengampu WHERE id_pengampu = $id_pengampu";
    
    if (mysqli_query($koneksi, $sql_delete)) {
        $pesan = "Penugasan berhasil dihapus.";
        $pesan_tipe = "success";
    }
}

// --- Ambil data untuk dropdown dan tabel ---
$hasil_dosen = mysqli_query($koneksi, "SELECT nip, nama_dosen FROM dosen ORDER BY nama_dosen");
$hasil_matkul = mysqli_query($koneksi, "SELECT kode_mk, nama_mk FROM mata_kuliah ORDER BY nama_mk");

// Ambil daftar penugasan yang sudah ada
$sql_pengampu = "SELECT dp.id_pengampu, d.nama_dosen, mk.nama_mk 
                 FROM dosen_pengampu dp
                 JOIN dosen d ON dp.nip_dosen = d.nip
                 JOIN mata_kuliah mk ON dp.kode_matkul = mk.kode_mk
                 ORDER BY d.nama_dosen, mk.nama_mk";
$hasil_pengampu = mysqli_query($koneksi, $sql_pengampu);

$page_title = "Atur Dosen Pengampu";
include 'header.php'; 
?>



    <div class="max-w-7xl mx-auto px-4 py-8">
        
        <?php
        if ($pesan) {
             $alertClass = $pesan_tipe == 'success' ? 'bg-green-100 border-green-400 text-green-700' : 
                          ($pesan_tipe == 'warning' ? 'bg-yellow-100 border-yellow-400 text-yellow-700' : 'bg-red-100 border-red-400 text-red-700');
            echo "<div class='{$alertClass} border px-4 py-3 rounded relative mb-6' role='alert'>
                    <span class='block sm:inline'>{$pesan}</span>
                  </div>";
        }
        ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden sticky top-8">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                        <h1 class="text-xl font-bold text-white">Tugaskan Dosen</h1>
                    </div>
                    <div class="p-6">
                        <form action="assign_dosen.php" method="POST" class="space-y-5">
                            <div>
                                <label for="nip_dosen" class="block text-sm font-medium text-gray-700 mb-2">Pilih Dosen</label>
                                <select id="nip_dosen" name="nip_dosen" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-white" required>
                                    <option value="">-- Pilih Dosen --</option>
                                    <?php
                                        while ($dosen = mysqli_fetch_assoc($hasil_dosen)) {
                                            echo "<option value='" . $dosen['nip'] . "'>" . htmlspecialchars($dosen['nama_dosen']) . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <label for="kode_matkul" class="block text-sm font-medium text-gray-700 mb-2">Pilih Mata Kuliah</label>
                                <select id="kode_matkul" name="kode_matkul" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 bg-white" required>
                                    <option value="">-- Pilih Mata Kuliah --</option>
                                    <?php
                                        while ($mk = mysqli_fetch_assoc($hasil_matkul)) {
                                            echo "<option value='" . $mk['kode_mk'] . "'>" . htmlspecialchars($mk['nama_mk']) . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Tugaskan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- List Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-bold text-gray-800">Daftar Dosen Pengampu Saat Ini</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dosen</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah Diampu</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                if (mysqli_num_rows($hasil_pengampu) > 0) {
                                    while ($data = mysqli_fetch_assoc($hasil_pengampu)) {
                                        echo "<tr class='hover:bg-gray-50 transition-colors'>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . htmlspecialchars($data['nama_dosen']) . "</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>" . htmlspecialchars($data['nama_mk']) . "</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap text-right text-sm font-medium'>
                                                <a href='assign_dosen.php?hapus=" . $data['id_pengampu'] . "' class='text-red-600 hover:text-red-900 flex justify-end items-center gap-1' onclick='return confirm(\"Yakin hapus penugasan ini?\");'>
                                                    <svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'></path></svg>
                                                    Hapus
                                                </a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='px-6 py-8 text-center text-gray-500 italic'>Belum ada dosen yang ditugaskan.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
<?php mysqli_close($koneksi); ?>