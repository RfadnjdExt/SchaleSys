-- Database Schema for Sistem Akademik (PostgreSQL Version)

-- 1. Table: dosen
CREATE TABLE IF NOT EXISTS dosen (
  nip VARCHAR(20) NOT NULL,
  nama_dosen VARCHAR(100) NOT NULL,
  email VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (nip)
);

-- 2. Table: mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
  nim VARCHAR(20) NOT NULL,
  nama_mahasiswa VARCHAR(100) NOT NULL,
  prodi VARCHAR(100) NOT NULL,
  angkatan INTEGER NOT NULL,
  dosen_wali_id VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (nim),
  CONSTRAINT fk_mahasiswa_dosen FOREIGN KEY (dosen_wali_id) REFERENCES dosen (nip) ON DELETE SET NULL ON UPDATE CASCADE
);

-- 3. Table: users
CREATE TABLE IF NOT EXISTS users (
  id_user SERIAL NOT NULL,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL,
  role VARCHAR(20) NOT NULL CHECK (role IN ('admin', 'dosen', 'mahasiswa')),
  nim VARCHAR(20) DEFAULT NULL,
  nip VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (id_user),
  CONSTRAINT unique_username UNIQUE (username),
  CONSTRAINT fk_users_dosen FOREIGN KEY (nip) REFERENCES dosen (nip) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_users_mahasiswa FOREIGN KEY (nim) REFERENCES mahasiswa (nim) ON DELETE CASCADE ON UPDATE CASCADE
);

-- 4. Table: mata_kuliah
CREATE TABLE IF NOT EXISTS mata_kuliah (
  kode_mk VARCHAR(20) NOT NULL,
  nama_mk VARCHAR(100) NOT NULL,
  sks INTEGER NOT NULL,
  semester INTEGER NOT NULL,
  hari VARCHAR(10) DEFAULT NULL, -- Senin, Selasa, dst
  jam_mulai TIME DEFAULT NULL,
  jam_selesai TIME DEFAULT NULL,
  ruangan VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (kode_mk)
);

-- 5. Table: semester_config
CREATE TABLE IF NOT EXISTS semester_config (
  id SERIAL NOT NULL,
  nama_semester VARCHAR(50) NOT NULL,
  is_aktif INTEGER DEFAULT 0, -- 0 or 1
  PRIMARY KEY (id),
  CONSTRAINT unique_nama_semester UNIQUE (nama_semester)
);

-- 6. Table: krs
CREATE TABLE IF NOT EXISTS krs (
  id_krs SERIAL NOT NULL,
  nim_mahasiswa VARCHAR(20) NOT NULL,
  kode_matkul VARCHAR(20) NOT NULL,
  semester VARCHAR(50) NOT NULL,
  tanggal_ambil DATE DEFAULT CURRENT_DATE,
  PRIMARY KEY (id_krs),
  CONSTRAINT unique_krs UNIQUE (nim_mahasiswa, kode_matkul, semester),
  CONSTRAINT fk_krs_mahasiswa FOREIGN KEY (nim_mahasiswa) REFERENCES mahasiswa (nim) ON DELETE CASCADE,
  CONSTRAINT fk_krs_matkul FOREIGN KEY (kode_matkul) REFERENCES mata_kuliah (kode_mk) ON DELETE CASCADE
);

-- 7. Table: nilai
CREATE TABLE IF NOT EXISTS nilai (
  id_nilai SERIAL NOT NULL,
  nim_mahasiswa VARCHAR(20) NOT NULL,
  kode_matkul VARCHAR(20) NOT NULL,
  nilai_huruf CHAR(2) NOT NULL,
  PRIMARY KEY (id_nilai),
  CONSTRAINT fk_nilai_mahasiswa FOREIGN KEY (nim_mahasiswa) REFERENCES mahasiswa (nim) ON DELETE CASCADE,
  CONSTRAINT fk_nilai_matkul FOREIGN KEY (kode_matkul) REFERENCES mata_kuliah (kode_mk) ON DELETE CASCADE
);

-- 8. Table: dosen_pengampu
CREATE TABLE IF NOT EXISTS dosen_pengampu (
  id_pengampu SERIAL NOT NULL,
  nip_dosen VARCHAR(20) NOT NULL,
  kode_matkul VARCHAR(20) NOT NULL,
  PRIMARY KEY (id_pengampu),
  CONSTRAINT fk_pengampu_dosen FOREIGN KEY (nip_dosen) REFERENCES dosen (nip) ON DELETE CASCADE,
  CONSTRAINT fk_pengampu_matkul FOREIGN KEY (kode_matkul) REFERENCES mata_kuliah (kode_mk) ON DELETE CASCADE
);

-- Data Seeding

-- Default Admin User (Password: admin123)
-- Using ON CONFLICT (idempotent insert)
INSERT INTO users (username, password, nama_lengkap, role, nim, nip) 
VALUES ('admin', '$2y$10$8.0/H2.0/H2.0/H2.0/H2.0/H2.0/H2.0/H2.0/H2.0/H2.', 'Administrator Utama', 'admin', NULL, NULL)
ON CONFLICT (username) DO NOTHING;

-- Default Semester
INSERT INTO semester_config (nama_semester, is_aktif) 
VALUES ('Ganjil 2024/2025', 1)
ON CONFLICT (nama_semester) DO UPDATE SET is_aktif = 1;
