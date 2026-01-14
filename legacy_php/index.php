<?php
// 1. Tentukan siapa yang boleh akses halaman ini
// 1. Tentukan siapa yang boleh akses (Diatur di acl_config.php)

// 2. Panggil penjaga
include 'auth_guard.php'; 

// 3. Set Title dan Include Header
$page_title = "Dashboard - Sistem Akademik";
include 'header.php';
?>


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <?php
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION['error_message'])) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6' role='alert'>
                    <strong class='font-bold'>Error!</strong>
                    <span class='block sm:inline'>{$_SESSION['error_message']}</span>
                  </div>";
            unset($_SESSION['error_message']);
        }
        ?>

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 rounded-3xl p-8 md:p-12 mb-12 text-white shadow-2xl relative overflow-hidden">
             <!-- Decorative blob -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-blue-500 opacity-20 blur-3xl transform"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-purple-500 opacity-20 blur-3xl transform"></div>

            <div class="relative z-10">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">
                    Selamat Datang<span class="text-blue-400">.</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-300 max-w-2xl leading-relaxed">
                    Sistem Informasi Akademik Terpadu. Kelola data mahasiswa, nilai, dan mata kuliah dengan mudah, cepat, dan presisi.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                     <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'dosen'): ?>
                        <a href="dashboard.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 shadow-lg transform hover:-translate-y-1">
                            Buka Dashboard
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($_SESSION['role'] == 'mahasiswa'): ?>
                        <a href="tampil_krs.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 shadow-lg transform hover:-translate-y-1">
                            Lihat KRS Saya
                        </a>
                    <?php else: ?>
                        <a href="tampil_mahasiswa.php" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 border border-gray-600">
                            Lihat Mahasiswa
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php if ($_SESSION['role'] != 'mahasiswa'): ?>
             <!-- Card 1 (Admin/Dosen only) -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 border border-gray-100 flex flex-col h-full group">
                <div class="p-6 flex-grow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                        <!-- Icon User -->
                       <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Data Mahasiswa</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Melihat, mencari, dan mengelola daftar lengkap mahasiswa beserta biodata mereka.
                    </p>
                </div>
                <div class="p-6 pt-0 mt-auto">
                    <a href="tampil_mahasiswa.php" class="block w-full text-center py-2 px-4 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors duration-300">
                        Buka Halaman
                    </a>
                </div>
            </div>

            <!-- Card 2 (Admin/Dosen only) -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 border border-gray-100 flex flex-col h-full group">
                 <div class="p-6 flex-grow">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                        <!-- Icon Plus -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Input Nilai Baru</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Menambahkan data nilai baru untuk mahasiswa pada mata kuliah yang diambil.
                    </p>
                </div>
                <div class="p-6 pt-0 mt-auto">
                    <a href="tambah_nilai.php" class="block w-full text-center py-2 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-300 shadow-md">
                        Input Nilai
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Card 3: Transkrip (Adapted) -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 border border-gray-100 flex flex-col h-full group">
                 <div class="p-6 flex-grow">
                    <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center text-cyan-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                         <!-- Icon Document -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    
                    <?php if ($_SESSION['role'] == 'mahasiswa'): ?>
                         <h3 class="text-xl font-bold text-gray-800 mb-2">Transkrip & IPK Saya</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Lihat riwayat hasil studi dan perkembangan IPK Anda selama kuliah.
                        </p>
                    <?php else: ?>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Cek Transkrip & IPK</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Lihat riwayat akademik mahasiswa.
                        </p>
                    <?php endif; ?>

                    <?php if ($_SESSION['role'] != 'mahasiswa'): ?>
                    <form action="tampil_transkrip.php" method="GET" class="relative">
                        <input type="text" name="nim" class="w-full pl-4 pr-12 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" placeholder="Masukkan NIM..." required>
                        <button type="submit" class="absolute right-1 top-1 bottom-1 bg-cyan-600 hover:bg-cyan-700 text-white rounded-md px-3 transition-colors duration-300">
                           →
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
                
                 <?php if ($_SESSION['role'] == 'mahasiswa'): ?>
                <div class="p-6 pt-0 mt-auto">
                    <a href="tampil_transkrip.php" class="block w-full text-center py-2 px-4 bg-cyan-600 text-white font-semibold rounded-lg hover:bg-cyan-700 transition-colors duration-300 shadow-md">
                        Lihat Transkrip
                    </a>
                </div>
                <?php endif; ?>
            </div>

             <?php if ($_SESSION['role'] == 'mahasiswa'): ?>
             <!-- Card 4: KRS (Mahasiswa Only) -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 border border-gray-100 flex flex-col h-full group">
                 <div class="p-6 flex-grow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-110 transition-transform duration-300">
                         <!-- Icon Calendar/KRS -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">KRS On-line</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Rencanakan studi semester ini, pilih mata kuliah, dan lihat jadwal.
                    </p>
                </div>
                <div class="p-6 pt-0 mt-auto">
                    <a href="input_krs.php" class="block w-full text-center py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors duration-300 shadow-md">
                         Isi / Edit KRS
                    </a>
                     <div class="mt-2 text-center">
                        <a href="tampil_krs.php" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium hover:underline">Lihat KRS Saya &rarr;</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

<?php include 'footer.php'; ?>
