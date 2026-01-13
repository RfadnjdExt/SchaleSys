-- SCRIPT UNTUK MEMBUAT AKUN TEST DOSEN DAN MAHASISWA (PostgreSQL Version - CLEAN HASH)
-- Jalankan script ini di Database (Supabase SQL Editor / pgAdmin)

-- Password untuk semua akun di bawah adalah: admin123
-- Hash: $2b$10$qmBZKXS8fdd8uzA/lyVPkOPopjXlzekmK7xJsWmkHnSmKmTfpsLE6 (VERIFIED 60-CHAR)

-- 1. Buat Data Profil DOSEN Dummy (NIP: D001)
INSERT INTO dosen (nip, nama_dosen, email)
VALUES ('D001', 'Dr. Dosen Test', 'dosen@test.com')
ON CONFLICT (nip) DO UPDATE SET 
    nama_dosen = EXCLUDED.nama_dosen,
    email = EXCLUDED.email;

-- 2. Buat Akun Login DOSEN (Username: dosen1)
INSERT INTO users (username, password, nama_lengkap, role, nip)
VALUES ('dosen1', '$2b$10$qmBZKXS8fdd8uzA/lyVPkOPopjXlzekmK7xJsWmkHnSmKmTfpsLE6', 'Dr. Dosen Test', 'dosen', 'D001')
ON CONFLICT (username) DO UPDATE SET 
    password = EXCLUDED.password,
    role = EXCLUDED.role,
    nip = EXCLUDED.nip;

-- 3. Buat Data Profil MAHASISWA Dummy (NIM: M001)
INSERT INTO mahasiswa (nim, nama_mahasiswa, prodi, angkatan)
VALUES ('M001', 'Mahasiswa Test', 'Teknik Informatika', 2023)
ON CONFLICT (nim) DO UPDATE SET 
    nama_mahasiswa = EXCLUDED.nama_mahasiswa,
    prodi = EXCLUDED.prodi,
    angkatan = EXCLUDED.angkatan;

-- 4. Buat Akun Login MAHASISWA (Username: mhs1)
INSERT INTO users (username, password, nama_lengkap, role, nim)
VALUES ('mhs1', '$2b$10$qmBZKXS8fdd8uzA/lyVPkOPopjXlzekmK7xJsWmkHnSmKmTfpsLE6', 'Mahasiswa Test', 'mahasiswa', 'M001')
ON CONFLICT (username) DO UPDATE SET 
    password = EXCLUDED.password,
    role = EXCLUDED.role,
    nim = EXCLUDED.nim;

-- FIX: Juga update akun Admin default agar bisa login
UPDATE users SET password = '$2b$10$qmBZKXS8fdd8uzA/lyVPkOPopjXlzekmK7xJsWmkHnSmKmTfpsLE6' WHERE username = 'admin';
