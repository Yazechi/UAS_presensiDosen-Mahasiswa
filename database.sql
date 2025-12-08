-- ============================================
-- DATABASE SISTEM PRESENSI DOSEN & MAHASISWA
-- Project UAS - Simple Version
-- ============================================

CREATE DATABASE IF NOT EXISTS db_presensi_uas;
USE db_presensi_uas;

-- ============================================
-- TABEL 1: USERS (Admin, Dosen, Mahasiswa)
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    nim_nip VARCHAR(20),
    role ENUM('admin', 'dosen', 'mahasiswa') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABEL 2: FORUMS (Kelas/Forum Pembelajaran)
-- ============================================
CREATE TABLE forums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_forum VARCHAR(20) NOT NULL UNIQUE,
    judul VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    dosen_id INT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dosen_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- TABEL 3: FORUM_MAHASISWA (Relasi Forum & Mahasiswa)
-- ============================================
CREATE TABLE forum_mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    forum_id INT NOT NULL,
    mahasiswa_id INT NOT NULL,
    tanggal_join TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (forum_id) REFERENCES forums(id) ON DELETE CASCADE,
    FOREIGN KEY (mahasiswa_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- TABEL 4: JADWAL ABSENSI
-- ============================================
CREATE TABLE jadwal_absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    forum_id INT NOT NULL,
    tanggal DATE NOT NULL,
    waktu_mulai TIME NOT NULL,
    waktu_selesai TIME NOT NULL,
    topik VARCHAR(200),
    status ENUM('aktif', 'selesai') DEFAULT 'aktif',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (forum_id) REFERENCES forums(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- TABEL 5: ATTENDANCE (Data Absensi Mahasiswa)
-- ============================================
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jadwal_id INT NOT NULL,
    mahasiswa_id INT NOT NULL,
    status ENUM('hadir', 'izin', 'sakit', 'alpha') DEFAULT 'alpha',
    waktu_absen DATETIME,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jadwal_id) REFERENCES jadwal_absensi(id) ON DELETE CASCADE,
    FOREIGN KEY (mahasiswa_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- DATA SAMPLE UNTUK TESTING
-- ============================================

-- Sample Users (Password semua: password)
INSERT INTO users (username, password, nama_lengkap, email, nim_nip, role) VALUES
-- Admin
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Sistem', 'admin@univ.ac.id', 'ADM001', 'admin'),

-- Dosen
('dosen1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Ahmad Hidayat', 'ahmad@univ.ac.id', '198501012010', 'dosen'),
('dosen2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Nurjanah, M.Kom', 'siti@univ.ac.id', '198702022012', 'dosen'),

-- Mahasiswa
('mhs001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Andi Pratama', 'andi@student.ac.id', '2021001', 'mahasiswa'),
('mhs002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dewi Lestari', 'dewi@student.ac.id', '2021002', 'mahasiswa'),
('mhs003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rizki Ramadhan', 'rizki@student.ac.id', '2021003', 'mahasiswa');

-- Sample Forums
INSERT INTO forums (kode_forum, judul, deskripsi, dosen_id, created_by) VALUES
('WEB2024', 'Pemrograman Web', 'Kelas Pemrograman Web Semester Ganjil 2024', 2, 2),
('DB2024', 'Basis Data', 'Kelas Basis Data Semester Ganjil 2024', 3, 3);

-- Mahasiswa Join ke Forum
INSERT INTO forum_mahasiswa (forum_id, mahasiswa_id) VALUES
(1, 4), (1, 5), (1, 6),  -- 3 mahasiswa di forum Pemrograman Web
(2, 4), (2, 5);           -- 2 mahasiswa di forum Basis Data

-- Sample Jadwal Absensi
INSERT INTO jadwal_absensi (forum_id, tanggal, waktu_mulai, waktu_selesai, topik, created_by) VALUES
(1, '2024-12-09', '08:00:00', '10:00:00', 'Pengenalan HTML & CSS', 2),
(1, '2024-12-16', '08:00:00', '10:00:00', 'JavaScript Dasar', 2),
(2, '2024-12-10', '10:00:00', '12:00:00', 'Pengenalan Database', 3);

-- Sample Attendance (1 mahasiswa sudah absen)
INSERT INTO attendance (jadwal_id, mahasiswa_id, status, waktu_absen) VALUES
(1, 4, 'hadir', '2024-12-09 08:05:00');

-- ============================================
-- SELESAI - Database Simple untuk UAS
-- ============================================

-- CATATAN PENTING:
-- 1. Password semua user: password
-- 2. Login Admin: username = admin
-- 3. Login Dosen: username = dosen1 atau dosen2
-- 4. Login Mahasiswa: username = mhs001, mhs002, atau mhs003
-- 5. Total 5 tabel: users, forums, forum_mahasiswa, jadwal_absensi, attendance

