CREATE DATABASE IF NOT EXISTS db_presensi_uas;
USE db_presensi_uas;

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
CREATE TABLE forum_mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    forum_id INT NOT NULL,
    mahasiswa_id INT NOT NULL,
    tanggal_join TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (forum_id) REFERENCES forums(id) ON DELETE CASCADE,
    FOREIGN KEY (mahasiswa_id) REFERENCES users(id) ON DELETE CASCADE
);
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

CREATE TABLE absensi_dosen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dosen_id INT NOT NULL,
    tanggal DATE NOT NULL,
    waktu_absen DATETIME,
    status ENUM('hadir', 'izin', 'sakit', 'alpha') DEFAULT 'alpha',
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dosen_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_dosen_tanggal (dosen_id, tanggal)
);

CREATE TABLE hari_libur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL UNIQUE,
    keterangan VARCHAR(200) NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
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

-- Sample Hari Libur
INSERT INTO hari_libur (tanggal, keterangan, created_by) VALUES
('2024-12-25', 'Hari Natal', 1),
('2025-01-01', 'Tahun Baru', 1),
('2025-03-31', 'Hari Raya Idul Fitri', 1),
('2025-04-01', 'Cuti Bersama Idul Fitri', 1);

-- Sample Absensi Dosen (beberapa hari)
INSERT INTO absensi_dosen (dosen_id, tanggal, waktu_absen, status) VALUES
(2, '2024-12-09', '2024-12-09 07:30:00', 'hadir'),
(3, '2024-12-09', '2024-12-09 07:45:00', 'hadir'),
(2, '2024-12-10', '2024-12-10 07:35:00', 'hadir');

-- ============================================
-- SELESAI - Database Simple untuk UAS
-- ============================================

-- CATATAN PENTING:
-- 1. Password semua user: password
-- 2. Login Admin: username = admin
-- 3. Login Dosen: username = dosen1 atau dosen2
-- 4. Login Mahasiswa: username = mhs001, mhs002, atau mhs003
-- 5. Total 7 tabel: users, forums, forum_mahasiswa, jadwal_absensi, attendance, absensi_dosen, hari_libur
-- 6. Dosen wajib absen setiap hari (kecuali hari libur) tanpa perlu join forum
-- 7. Admin dapat melihat laporan absensi dosen dan mahasiswa
