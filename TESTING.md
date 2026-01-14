# Testing Scenarios - SchaleSys

Berikut adalah alur pengujian (Test Flows) yang disarankan untuk memastikan stabilitas sistem SchaleSys.

## 1. Authentication & Security
- [ ] **Login**:
    - Login sebagai Admin.
    - Login sebagai Dosen.
    - Login sebagai Mahasiswa.
    - Login dengan kredensial salah (harus muncul error).
- [ ] **Logout**:
    - Logout dari Dashboard.
    - Logout dari halaman list (Mahasiswa/Dosen/Matkul).
    - Logout dari halaman form (Create/Edit).
- [ ] **Protection (RBAC)**:
    - Coba akses halaman `/dosen` atau `/mahasiswa` saat login sebagai **Mahasiswa** (seharusnya redirect atau 403).
    - Coba akses `/matakuliah/create` sebagai user non-admin.

## 2. Modul Admin (CRUD Master Data)
### a. Data Mahasiswa (`/mahasiswa`)
- [ ] **Create**: Tambah mahasiswa baru. Pastikan NIM unik. Upload foto (jika ada).
- [ ] **Read**: Cek apakah data muncul di tabel. Cek pagination (jika ada).
- [ ] **Update**: Edit data mahasiswa. Pastikan kolom **NIM** terkunci (readonly) dan tidak bisa diubah.
- [ ] **Delete**: Hapus mahasiswa. Pastikan ada konfirmasi sebelum hapus.
- [ ] **Navigation**: Cek tombol "Back to Dashboard" dan Logo Clickable.

### b. Data Dosen (`/dosen`)
- [ ] **Create**: Tambah dosen baru. Validasi input wajib.
- [ ] **Read**: List dosen tampil benar.
- [ ] **Update**: Edit profile dosen. Pastikan **NIP** terkunci.
- [ ] **Delete**: Hapus data dosen.

### c. Data Mata Kuliah (`/matakuliah`)
- [ ] **Create**: Tambah matkul. Pastikan **Kode MK** unik. Validasi SKS (1-6) dan Semester (1-8).
- [ ] **Read**: List mata kuliah tampil dengan jadwal dan ruangan.
- [ ] **Update**: Edit info matkul. Pastikan **Kode MK** terkunci.
- [ ] **Delete**: Hapus mata kuliah.

## 3. Modul Mahasiswa
- [ ] **Dashboard**: Cek informasi ringkasan (IPK, SKS diambil).
- [ ] **KRS (Kartu Rencana Studi)** (`/krs`):
    - **Input KRS**: Pilih mata kuliah yang tersedia. Cek clash jadwal (jika fitur ada).
    - **View KRS**: Lihat daftar matkul yang sudah diambil.
    - **Hapus Matkul**: Batalkan matkul dari KRS.
- [ ] **Transkrip / Hasil Studi**: Cek apakah nilai tampil sesuai.

## 4. Modul Dosen
- [ ] **Dashboard**: Ringkasan jadwal mengajar.
- [ ] **Input Nilai** (`/nilai`):
    - Pilih mata kuliah yang diajar.
    - Input nilai mahasiswa.
    - Simpan dan pastikan nilai terupdate di sisi mahasiswa.

## 5. UI/UX & Responsiveness
- [ ] **Mobile View**: Buka di layar hp/kecil. Cek apakah tabel bisa di-scroll, menu navigasi (burger menu) berfungsi.
- [ ] **Clickable Elements**: Pastikan Logo SchaleSys di kiri atas selalu kembali ke Dashboard.
- [ ] **Consistency**: Cek konsistensi warna border, style tombol, dan font di semua halaman.
