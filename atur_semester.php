<?php
$allowed_roles = ['admin'];
include 'auth_guard.php';
include 'koneksi.php';
include 'csrf_helper.php';

$page_title = "Manajemen Semester"; // Untuk header.php

$pesan = '';
$pesan_tipe = '';

// --- PROSES: TAMBAH SEMESTER ---
if (isset($_POST['tambah_semester'])) {
    
    // Verifikasi CSRF Token (untuk tambah semester)
    verify_csrf_token();
    
    $nama_baru = mysqli_real_escape_string($koneksi, $_POST['nama_semester']);
    if (!empty($nama_baru)) {
        $sql = "INSERT INTO semester_config (nama_semester, is_aktif) VALUES ('$nama_baru', 0)";
        if (mysqli_query($koneksi, $sql)) {
            $pesan = "Semester '$nama_baru' berhasil ditambahkan.";
            $pesan_tipe = "success";
        } else {
            $pesan = "Error: " . mysqli_error($koneksi);
            $pesan_tipe = "danger";
        }
    }
}

// --- PROSES: AKTIFKAN SEMESTER ---
if (isset($_POST['aktifkan_id'])) {
    
    // Verifikasi CSRF Token
    verify_csrf_token();

    $id_aktif = (int)$_POST['aktifkan_id'];
    
    // 1. Nonaktifkan semua
    mysqli_query($koneksi, "UPDATE semester_config SET is_aktif = 0");
    
    // 2. Aktifkan yang dipilih
    $sql_aktif = "UPDATE semester_config SET is_aktif = 1 WHERE id = $id_aktif";
    if (mysqli_query($koneksi, $sql_aktif)) {
        $pesan = "Semester aktif berhasil diperbarui.";
        $pesan_tipe = "success";
    } else {
        $pesan = "Error: " . mysqli_error($koneksi);
        $pesan_tipe = "danger";
    }
}

// --- AMBIL DATA SEMESTER ---
$hasil = mysqli_query($koneksi, "SELECT * FROM semester_config ORDER BY id DESC");
?>

<?php include 'header.php'; ?>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gray-800 px-6 py-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                    <span class="mr-2">⚙️</span> Atur Semester Aktif
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
                
                <!-- Form Tambah -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8 border border-gray-200">
                    <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Tambah Semester Baru</h5>
                    <form method="POST" class="flex gap-4">
                        <?php echo csrf_field(); ?>
                        <div class="flex-grow">
                            <input type="text" name="nama_semester" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-4" placeholder="Nama Semester (mis: Genap 2024/2025)" required>
                        </div>
                        <div>
                            <button type="submit" name="tambah_semester" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md shadow-sm transition-colors duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabel Daftar -->
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Semester</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = mysqli_fetch_assoc($hasil)): ?>
                                <tr class="<?php echo $row['is_aktif'] ? 'bg-green-50' : 'hover:bg-gray-50'; ?> transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium <?php echo $row['is_aktif'] ? 'text-green-900' : 'text-gray-900'; ?>">
                                            <?php echo htmlspecialchars($row['nama_semester']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if ($row['is_aktif']): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <svg class="w-2 h-2 mr-1.5 text-green-500 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                AKTIF
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Tidak Aktif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <?php if (!$row['is_aktif']): ?>
                                            <form method="POST" style="display:inline;">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="aktifkan_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="text-blue-600 hover:text-blue-900 border border-blue-200 hover:border-blue-300 bg-white px-3 py-1 rounded-md text-xs font-semibold shadow-sm transition-colors">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-green-600 font-semibold text-xs border border-green-200 bg-white px-3 py-1 rounded-md flex items-center justify-end w-max ml-auto shadow-sm cursor-default">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Terpilih
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
