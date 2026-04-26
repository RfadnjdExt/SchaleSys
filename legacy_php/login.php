<?php
// --- BAGIAN 1: LOGIKA PHP ---

// Mulai session di baris paling atas
session_start();

// 1. Cek jika pengguna sudah login, langsung redirect ke index.php
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Sertakan file koneksi
include 'koneksi.php';
include 'csrf_helper.php';

$error_message = '';

// 2. Cek jika form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifikasi CSRF Token
    verify_csrf_token();
    
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi dasar
    if (!empty($username) && !empty($password)) {
        
        try {
            // GUNAKAN PDO PREPARED STATEMENT
            $stmt = $koneksi->prepare("SELECT id_user, username, nama_lengkap, password, role, nim, nip FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // 4. Cek apakah username ditemukan
            if ($user_data) {
                
                // 5. Verifikasi password yang di-hash
                if (password_verify($password, $user_data['password'])) {
                    
                    // 6. Jika password benar, simpan SEMUA data ke session
                    $_SESSION['user_id'] = $user_data['id_user'];
                    $_SESSION['username'] = $user_data['username'];
                    $_SESSION['nama_lengkap'] = $user_data['nama_lengkap'];
                    $_SESSION['role'] = $user_data['role']; 
                    $_SESSION['nim'] = $user_data['nim']; 
                    $_SESSION['nip'] = $user_data['nip']; 
    
                    // 7. Redirect ke halaman utama (index.php)
                    header("Location: index.php");
                    exit;
    
                } else {
                    $error_message = "Username atau password salah.";
                }
            } else {
                $error_message = "Username atau password salah.";
            }
        } catch (PDOException $e) {
             $error_message = "Terjadi kesalahan database: " . $e->getMessage();
        }

    } else {
        $error_message = "Username dan password wajib diisi.";
    }
}

// Tutup koneksi (Opsional di PDO, otomatis tertutup saat script selesai)
// $koneksi = null;
?>

<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Akademik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-md w-full space-y-8">
        <div>
            <img class="mx-auto h-24 w-auto" src="assets/img/logo.png" alt="SIAKAD Logo">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Sistem Informasi Akademik
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Silakan masuk menggunakan akun Anda
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="login.php" method="POST">
             <?php echo csrf_field(); ?>
            <div class="rounded-md shadow-sm -space-y-px">
                <?php
                // Tampilkan pesan error jika ada
                if ($error_message) {
                    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4' role='alert'>
                            <span class='block sm:inline'>$error_message</span>
                          </div>";
                }
                ?>
                <div>
                    <label for="username" class="sr-only">Username</label>
                    <input id="username" name="username" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Username">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Password">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <!-- Heroicon name: lock-closed -->
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Sign in
                </button>
            </div>
            
            <div class="text-center mt-4 text-xs text-gray-400">
                &copy; 2026 Sistem Informasi Akademik
            </div>
        </form>
    </div>

</body>
</html>