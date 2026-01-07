-- Database Schema for Sistem Akademik
-- Generated based on PHP source code analysis

SET FOREIGN_KEY_CHECKS=0;
START TRANSACTION;

-- 1. Table: dosen
-- References: tambah_dosen.php, assign_dosen.php
CREATE TABLE IF NOT EXISTS `dosen` (
  `nip` VARCHAR(20) NOT NULL,
  `nama_dosen` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`nip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Table: mahasiswa
-- References: tambah_mahasiswa.php, input_krs.php
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `nim` VARCHAR(20) NOT NULL,
  `nama_mahasiswa` VARCHAR(100) NOT NULL,
  `prodi` VARCHAR(100) NOT NULL,
  `angkatan` YEAR NOT NULL,
  `dosen_wali_id` VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (`nim`),
  KEY `fk_mahasiswa_dosen` (`dosen_wali_id`),
  CONSTRAINT `fk_mahasiswa_dosen` FOREIGN KEY (`dosen_wali_id`) REFERENCES `dosen` (`nip`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Table: users
-- References: login.php, auth_guard.php
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `role` ENUM('admin','dosen','mahasiswa') NOT NULL,
  `nim` VARCHAR(20) DEFAULT NULL,
  `nip` VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_users_mahasiswa` (`nim`),
  KEY `fk_users_dosen` (`nip`),
  CONSTRAINT `fk_users_dosen` FOREIGN KEY (`nip`) REFERENCES `dosen` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_users_mahasiswa` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Table: mata_kuliah
-- References: tambah_matakuliah.php, setup_jadwal.php
CREATE TABLE IF NOT EXISTS `mata_kuliah` (
  `kode_mk` VARCHAR(20) NOT NULL,
  `nama_mk` VARCHAR(100) NOT NULL,
  `sks` INT(11) NOT NULL,
  `semester` INT(11) NOT NULL,
  `hari` VARCHAR(10) DEFAULT NULL COMMENT 'Senin, Selasa, dst',
  `jam_mulai` TIME DEFAULT NULL,
  `jam_selesai` TIME DEFAULT NULL,
  `ruangan` VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (`kode_mk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Table: semester_config
-- References: config_semester.php, setup_semester.php
CREATE TABLE IF NOT EXISTS `semester_config` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_semester` VARCHAR(50) NOT NULL,
  `is_aktif` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_semester` (`nama_semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Table: krs
-- References: input_krs.php, setup_krs.php
CREATE TABLE IF NOT EXISTS `krs` (
  `id_krs` INT(11) NOT NULL AUTO_INCREMENT,
  `nim_mahasiswa` VARCHAR(20) NOT NULL,
  `kode_matkul` VARCHAR(20) NOT NULL,
  `semester` VARCHAR(50) NOT NULL,
  `tanggal_ambil` DATE DEFAULT CURRENT_DATE,
  PRIMARY KEY (`id_krs`),
  UNIQUE KEY `unique_krs` (`nim_mahasiswa`,`kode_matkul`,`semester`),
  KEY `fk_krs_mahasiswa` (`nim_mahasiswa`),
  KEY `fk_krs_matkul` (`kode_matkul`),
  CONSTRAINT `fk_krs_mahasiswa` FOREIGN KEY (`nim_mahasiswa`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE,
  CONSTRAINT `fk_krs_matkul` FOREIGN KEY (`kode_matkul`) REFERENCES `mata_kuliah` (`kode_mk`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Table: nilai
-- References: tambah_nilai.php
CREATE TABLE IF NOT EXISTS `nilai` (
  `id_nilai` INT(11) NOT NULL AUTO_INCREMENT,
  `nim_mahasiswa` VARCHAR(20) NOT NULL,
  `kode_matkul` VARCHAR(20) NOT NULL,
  `nilai_huruf` CHAR(2) NOT NULL,
  PRIMARY KEY (`id_nilai`),
  KEY `fk_nilai_mahasiswa` (`nim_mahasiswa`),
  KEY `fk_nilai_matkul` (`kode_matkul`),
  CONSTRAINT `fk_nilai_mahasiswa` FOREIGN KEY (`nim_mahasiswa`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE,
  CONSTRAINT `fk_nilai_matkul` FOREIGN KEY (`kode_matkul`) REFERENCES `mata_kuliah` (`kode_mk`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Table: dosen_pengampu
-- References: assign_dosen.php
CREATE TABLE IF NOT EXISTS `dosen_pengampu` (
  `id_pengampu` INT(11) NOT NULL AUTO_INCREMENT,
  `nip_dosen` VARCHAR(20) NOT NULL,
  `kode_matkul` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id_pengampu`),
  KEY `fk_pengampu_dosen` (`nip_dosen`),
  KEY `fk_pengampu_matkul` (`kode_matkul`),
  CONSTRAINT `fk_pengampu_dosen` FOREIGN KEY (`nip_dosen`) REFERENCES `dosen` (`nip`) ON DELETE CASCADE,
  CONSTRAINT `fk_pengampu_matkul` FOREIGN KEY (`kode_matkul`) REFERENCES `mata_kuliah` (`kode_mk`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Seeding

-- Default Admin User (Password: admin123)
-- Hash generated using password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `role`, `nim`, `nip`) VALUES
('admin', '$2y$10$8.0/H2.0/H2.0/H2.0/H2.0/H2.0/H2.0/H2.0/H2.0/H2.0/H2.', 'Administrator Utama', 'admin', NULL, NULL)
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Default Semester
INSERT INTO `semester_config` (`nama_semester`, `is_aktif`) VALUES
('Ganjil 2024/2025', 1)
ON DUPLICATE KEY UPDATE `is_aktif`=1;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
