<nav class="bg-white border-b border-gray-200 shadow-sm no-print" x-data="{ mobileMenuOpen: false }">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      
      <!-- Brand -->
      <div class="flex-shrink-0 flex items-center">
        <a href="index.php" class="flex items-center gap-3 group">
            <img src="assets/img/logo.png" alt="SIAKAD Logo" class="h-10 w-auto group-hover:scale-110 transition-transform duration-200">
            <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-700 to-teal-600">
                SIAKAD
            </span>
        </a>
      </div>

      <!-- Desktop Menu -->
      <div class="hidden md:block">
        <div class="ml-10 flex items-baseline space-x-1">
          <a href="index.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Home</a>

          <?php // ------------------- BLOK ADMIN & DOSEN ------------------- ?>
          <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'dosen'): ?>
            <a href="dashboard.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Dashboard</a>
            <a href="tampil_mahasiswa.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Mahasiswa</a>
            <a href="tampil_matakuliah.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Mata Kuliah</a>
          <?php endif; ?>

          <?php // ------------------- BLOK ADMIN SAJA ------------------- ?>
          <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="tampil_dosen.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Dosen</a>
            
            <!-- Dropdown Input Data -->
            <div class="relative inline-block text-left" x-data="{ open: false }">
              <button @click="open = !open" @click.away="open = false" type="button" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium inline-flex items-center transition-colors">
                <span>Input Data</span>
                <svg class="ml-2 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                <div class="py-1">
                  <a href="tambah_mahasiswa.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Tambah Mahasiswa</a>
                  <a href="tambah_matakuliah.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Tambah Mata Kuliah</a>
                  <a href="tambah_dosen.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Tambah Dosen</a>
                  <div class="border-t border-gray-100 my-1"></div>
                  <a href="tambah_nilai.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Input Nilai</a>
                  <a href="assign_dosen.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Atur Dosen Pengampu</a>
                  <div class="border-t border-gray-100 my-1"></div>
                  <a href="atur_semester.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">⚙️ Atur Semester</a>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php // ------------------- BLOK DOSEN SAJA ------------------- ?>
          <?php if ($_SESSION['role'] == 'dosen'): ?>
            <a href="dosen_wali.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Mahasiswa Wali</a>
            <a href="tambah_nilai.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Input Nilai</a>
          <?php endif; ?>

          <?php // ------------------- BLOK MAHASISWA SAJA ------------------- ?>
          <?php if ($_SESSION['role'] == 'mahasiswa'): ?>
            <a href="input_krs.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Input KRS</a>
            <a href="tampil_krs.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Lihat KRS</a>
            <a href="tampil_transkrip.php?nim=<?php echo htmlspecialchars($_SESSION['nim']); ?>" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Transkrip</a>
          <?php endif; ?>

        </div>
      </div>

      <!-- User Menu -->
      <div class="hidden md:block">
        <div class="ml-4 flex items-center md:ml-6">
            <div class="relative ml-3" x-data="{ userOpen: false }">
                <div>
                    <button @click="userOpen = !userOpen" @click.away="userOpen = false" type="button" class="max-w-xs bg-white rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 p-1 pr-3 border border-gray-200 hover:bg-gray-50 transition-colors" aria-expanded="false" aria-haspopup="true">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold mr-2 shadow-sm">
                             <?php echo substr($_SESSION['nama_lengkap'], 0, 1); ?>
                        </div>
                        <span class="text-gray-700 text-sm font-medium">Hi, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></span>
                    </button>
                </div>
                <!-- Dropdown -->
                <div x-show="userOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Sign out</a>
                </div>
            </div>
        </div>
      </div>

      <!-- Mobile menu button -->
      <div class="-mr-2 flex md:hidden">
        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
          <span class="sr-only">Open main menu</span>
          <!-- Heroicon name: menu -->
          <svg x-show="!mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <!-- Heroicon name: x -->
          <svg x-show="mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div x-show="mobileMenuOpen" class="md:hidden bg-white border-t border-gray-200">
    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
        <a href="index.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Home</a>
        
        <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'dosen'): ?>
            <a href="dashboard.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
            <a href="tampil_mahasiswa.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Mahasiswa</a>
            <a href="tampil_matakuliah.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Mata Kuliah</a>
        <?php endif; ?>

        <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="tampil_dosen.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Dosen</a>
            <a href="tambah_mahasiswa.php" class="text-gray-500 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium pl-6">↳ Tambah Mahasiswa</a>
            <a href="tambah_matakuliah.php" class="text-gray-500 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium pl-6">↳ Tambah Matkul</a>
            <a href="tambah_dosen.php" class="text-gray-500 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium pl-6">↳ Tambah Dosen</a>
            <a href="atur_semester.php" class="text-gray-500 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium pl-6">↳ Atur Semester</a>
        <?php endif; ?>

        <?php if ($_SESSION['role'] == 'mahasiswa'): ?>
            <a href="input_krs.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Input KRS</a>
            <a href="tampil_krs.php" class="text-gray-600 hover:bg-gray-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Lihat KRS</a>
        <?php endif; ?>
    </div>
    <div class="pt-4 pb-3 border-t border-gray-200">
      <div class="flex items-center px-5">
        <div class="flex-shrink-0">
           <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-sm">
                <?php echo substr($_SESSION['nama_lengkap'], 0, 1); ?>
           </div>
        </div>
        <div class="ml-3">
          <div class="text-base font-medium leading-none text-gray-800"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></div>
          <div class="text-sm font-medium leading-none text-gray-500 mt-1"><?php echo ucfirst($_SESSION['role']); ?></div>
        </div>
      </div>
      <div class="mt-3 px-2 space-y-1">
        <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-blue-600 hover:bg-gray-50">Sign out</a>
      </div>
    </div>
  </div>
</nav>