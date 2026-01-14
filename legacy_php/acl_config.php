<?php
/**
 * ACL (Access Control List) Configuration
 * 
 * Defines which roles are allowed to access which files.
 * Format: 'filename.php' => ['role1', 'role2']
 */

return [
    // --- PUBLIK (Tidak perlu login, ditangani auth_guard dengan pengecualian khusus atau tidak di-include) ---
    // 'login.php' => [], // Tidak dilindungi auth_guard
    // 'logout.php' => [], // Tidak dilindungi auth_guard

    // --- DASHBOARD & UMUM (Semua yang login) ---
    'index.php' => ['admin', 'dosen', 'mahasiswa'],
    'dashboard.php' => ['admin', 'dosen', 'mahasiswa'],
    
    // --- ADMIN ONLY ---
    'tambah_dosen.php' => ['admin'],
    'edit_dosen.php' => ['admin'],
    'hapus_dosen.php' => ['admin'],
    
    'tambah_matakuliah.php' => ['admin'],
    'edit_matakuliah.php' => ['admin'],
    'hapus_matakuliah.php' => ['admin'],
    
    'tambah_mahasiswa.php' => ['admin'],
    'edit_mahasiswa.php' => ['admin'],
    'hapus_mahasiswa.php' => ['admin'],
    
    'assign_dosen.php' => ['admin'],
    'atur_semester.php' => ['admin'],
    'setup_jadwal.php' => ['admin'],
    'tampil_dosen.php' => ['admin'],
    
    'buat_admin_hash.php' => ['admin'], // Utilitas keamanan
    
    // --- ADMIN & DOSEN ---
    'tampil_mahasiswa.php' => ['admin', 'dosen'],
    'tampil_matakuliah.php' => ['admin', 'dosen'],
    'tambah_nilai.php' => ['admin', 'dosen'],
    'edit_nilai.php' => ['admin', 'dosen'],
    'hapus_nilai.php' => ['admin', 'dosen'],
    
    // --- DOSEN ONLY ---
    'dosen_wali.php' => ['dosen'],
    
    // --- MAHASISWA ONLY ---
    'input_krs.php' => ['mahasiswa'],
    'tampil_krs.php' => ['mahasiswa'],
    'tampil_transkrip.php' => ['mahasiswa'], // Biasanya mahasiswa melihat transkrip sendiri.
                                              // NOTE: Jika Dosen Wali melihat transkrip, logika di file php-nya
                                              // mungkin perlu disesuaikan atau file ini dibuat shared.
                                              // Namun berdasarkan kode dosen_wali.php, ada link ke tampil_transkrip.php?nim=...
                                              // Jadi kita harus izinkan dosen juga.
                                          
];
