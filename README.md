<<<<<<< HEAD
# Sistem Presensi Dosen dan Mahasiswa

## 📋 Deskripsi Project
Project ini adalah sistem presensi berbasis web untuk Dosen dan Mahasiswa yang dibuat sebagai tugas UAS. Sistem ini memungkinkan admin, dosen, dan mahasiswa untuk mengelola dan melakukan absensi secara online.

## 👥 Role Pengguna

### 1. Admin
Admin memiliki akses penuh untuk mengelola sistem:
- ✅ Melihat laporan absensi dari mahasiswa dan dosen
- ✅ Menambah atau menghapus jadwal absensi bagi mahasiswa
- ✅ Menambah atau menghapus forum untuk dosen dan mahasiswa
- ✅ Mengelola hari libur (dosen tidak wajib absen di hari libur)
- ✅ Melihat rekap kehadiran dosen harian

### 2. Dosen
Dosen dapat mengelola kelas dan mahasiswa:
- ✅ **Melakukan absensi harian (MANDATORY)** - wajib absen setiap hari kerja (Senin-Jumat)
- ✅ **Tidak perlu absen di weekend (Sabtu & Minggu) dan hari libur nasional**
- ✅ Menambahkan atau menghapus jadwal absensi untuk mahasiswa
- ✅ Mengubah status mahasiswa (Hadir, Absen, Izin/Sakit)
- ✅ Melihat data mahasiswa yang sudah absen
- ✅ Menambah atau menghapus forum pembelajaran untuk mahasiswa
- ✅ Melihat histori absensi diri sendiri

### 3. Mahasiswa
Mahasiswa dapat melakukan absensi dan melihat riwayat:
- ✅ Melakukan absensi pada forum yang sudah ditetapkan dosen atau admin
- ✅ Melihat data histori absensi
- ✅ Melihat dosen yang melakukan absensi

## 🛠️ Teknologi yang Digunakan
- **Backend**: PHP Native
- **Database**: MySQL
- **Frontend**: HTML, CSS, Bootstrap 5
- **Server**: XAMPP (Apache + MySQL)

## 📁 Struktur Database

### Tabel: users
Menyimpan data semua pengguna (Admin, Dosen, Mahasiswa)
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- username (VARCHAR)
- password (VARCHAR) - gunakan MD5 atau password_hash()
- nama_lengkap (VARCHAR)
- email (VARCHAR)
- role (ENUM: 'admin', 'dosen', 'mahasiswa')
- nim_nip (VARCHAR) - untuk identitas mahasiswa/dosen
- created_at (TIMESTAMP)
```

### Tabel: forum
Menyimpan data forum/kelas pembelajaran
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- nama_forum (VARCHAR)
- deskripsi (TEXT)
- dosen_id (INT, FOREIGN KEY ke users)
- kode_forum (VARCHAR) - kode unik untuk join forum
- created_at (TIMESTAMP)
```

### Tabel: jadwal_absensi
Menyimpan jadwal absensi
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- forum_id (INT, FOREIGN KEY ke forum)
- tanggal (DATE)
- waktu_mulai (TIME)
- waktu_selesai (TIME)
- status (ENUM: 'aktif', 'selesai')
- created_by (INT, FOREIGN KEY ke users)
- created_at (TIMESTAMP)
```

### Tabel: absensi
Menyimpan data absensi mahasiswa
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- jadwal_id (INT, FOREIGN KEY ke jadwal_absensi)
- mahasiswa_id (INT, FOREIGN KEY ke users)
- status (ENUM: 'hadir', 'absen', 'izin', 'sakit')
- waktu_absen (DATETIME)
- keterangan (TEXT)
- created_at (TIMESTAMP)
```

### Tabel: forum_mahasiswa
Relasi many-to-many antara forum dan mahasiswa
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- forum_id (INT, FOREIGN KEY ke forum)
- mahasiswa_id (INT, FOREIGN KEY ke users)
- tanggal_join (TIMESTAMP)
```

### Tabel: absensi_dosen
Menyimpan data absensi harian dosen (MANDATORY)
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- dosen_id (INT, FOREIGN KEY ke users)
- tanggal (DATE)
- waktu_absen (DATETIME)
- status (ENUM: 'hadir', 'izin', 'sakit', 'alpha')
- keterangan (TEXT)
- created_at (TIMESTAMP)
- UNIQUE: dosen_id + tanggal (1 dosen hanya bisa absen 1x per hari)
```

### Tabel: hari_libur
Menyimpan data hari libur nasional (dosen tidak wajib absen)
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- tanggal (DATE, UNIQUE)
- keterangan (VARCHAR) - nama libur
- created_by (INT, FOREIGN KEY ke users)
- created_at (TIMESTAMP)
```
**Catatan:** Weekend (Sabtu & Minggu) otomatis dianggap hari libur, tidak perlu diinput ke tabel ini.

## 📂 Struktur File Project

```
SistemPresensi/
│
├── config.php                 # Konfigurasi database
├── database.sql              # File SQL untuk membuat database
├── index.php                 # Halaman landing page
├── register.php              # Halaman registrasi
├── login.php                 # Halaman login
├── logout.php                # Proses logout
│
├── admin/
│   ├── dashboard.php         # Dashboard admin
│   ├── laporan_mahasiswa.php # Laporan absensi mahasiswa
│   ├── laporan_dosen.php     # Laporan absensi dosen
│   ├── kelola_jadwal.php     # Mengelola jadwal absensi mahasiswa
│   ├── kelola_forum.php      # Mengelola forum
│   ├── kelola_libur.php      # Mengelola hari libur
│   └── kelola_user.php       # Mengelola user (opsional)
│
├── dosen/
│   ├── dashboard.php         # Dashboard dosen
│   ├── absensi_harian.php    # Absensi harian dosen (MANDATORY)
│   ├── histori_absensi.php   # Histori absensi dosen
│   ├── kelola_jadwal.php     # Mengelola jadwal absensi mahasiswa
│   ├── kelola_mahasiswa.php  # Melihat & ubah status mahasiswa
│   ├── kelola_forum.php      # Mengelola forum pembelajaran
│   └── laporan_absensi.php   # Laporan absensi mahasiswa
│
├── mahasiswa/
│   ├── dashboard.php         # Dashboard mahasiswa
│   ├── absensi.php           # Halaman melakukan absensi
│   ├── histori_absensi.php   # Melihat riwayat absensi
│   ├── forum.php             # Melihat forum yang diikuti
│   └── join_forum.php        # Join forum baru
│
├── assets/
│   ├── css/
│   │   └── style.css         # Custom CSS
│   ├── js/
│   │   └── script.js         # Custom JavaScript
│   └── img/
│       └── placeholder/      # Folder untuk placeholder images
│
└── includes/
    ├── header.php            # Header template dengan navbar sidebar
    ├── footer.php            # Footer template
    ├── navbar.php            # Navbar bootstrap (opsional)
    └── functions.php         # Fungsi-fungsi helper
```

### 📌 Struktur Header & Footer

#### Penggunaan Header dan Footer
Setiap halaman dashboard harus menggunakan struktur berikut:

```php
<?php
// 1. Include config dan functions
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

// 2. Cek login dan role
cekLogin();
cekRole('admin'); // atau 'dosen' atau 'mahasiswa'

// 3. Logic PHP untuk halaman ini
$data = mysqli_query($conn, "SELECT * FROM table");

// 4. Include header (otomatis load CSS & navbar)
include __DIR__ . '/../includes/header.php';
?>

<!-- 5. Konten halaman -->
<div class="container">
    <h1>Judul Halaman</h1>
    <!-- konten lainnya -->
</div>

<?php 
// 6. Include footer (otomatis load JavaScript)
include __DIR__ . '/../includes/footer.php'; 
?>
```

#### Fitur Header.php
- ✅ Otomatis load Bootstrap CSS dari `assets/vendor/bootstrap/css/`
- ✅ Otomatis load custom CSS dari `assets/css/style.css`
- ✅ Sidebar navigation dengan role-based menu
- ✅ Header bar dengan nama user dan tombol logout
- ✅ Responsive design untuk mobile

#### Fitur Footer.php
- ✅ Otomatis load Bootstrap JS dari `assets/vendor/bootstrap/js/`
- ✅ Display tanggal otomatis (hari, tanggal, bulan, tahun)
- ✅ Footer copyright
- ✅ Closing tags HTML

#### Navbar Dinamis Berdasarkan Role

**Admin Menu:**
- Dashboard
- Kelola User
- Kelola Forum
- Kelola Jadwal
- Kelola Libur
- Data Absensi
- Laporan Dosen
- Laporan Mahasiswa

**Dosen Menu:**
- Dashboard
- Absensi Harian
- Histori Absensi
- Kelola Forum
- Kelola Jadwal
- Kelola Mahasiswa
- Laporan Mahasiswa

**Mahasiswa Menu:**
- Dashboard
- Forum Saya
- Join Forum
- Absensi
- Histori Absensi

#### Styling Navbar
Header menggunakan sidebar fixed dengan:
- Gradient background (purple-blue)
- Active menu highlighting
- Icon emoji untuk setiap menu
- Logout button di bottom sidebar
- Responsive untuk mobile devices
```

## 🚀 Cara Setup Project

### 1. Persiapan Environment
- Install XAMPP (sudah terinstall)
- Pastikan Apache dan MySQL sudah running di XAMPP Control Panel

### 2. Setup Database
```bash
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Buat database baru dengan nama: sistem_presensi
3. Import file database.sql ke database yang baru dibuat
4. Atau jalankan query SQL yang ada di file database.sql
```

### 3. Konfigurasi Database
Edit file `config.php`:
```php
<?php
$host = 'localhost';
$dbname = 'sistem_presensi';
$username = 'root';
$password = ''; // kosongkan jika default XAMPP

$conn = mysqli_connect($host, $username, $password, $dbname);
?>
```

### 4. Akses Project
Buka browser dan akses:
```
http://localhost/SistemPresensi
```

## 👤 Default Login (setelah setup database)

### Admin
- Username: `admin`
- Password: `admin123`

### Dosen (contoh)
- Username: `dosen1`
- Password: `dosen123`

### Mahasiswa (contoh)
- Username: `mahasiswa1`
- Password: `mhs123`

## 📝 Tahapan Pengerjaan Project

### ✅ Tahap 1: Setup Awal (Sudah Dikerjakan)
- [x] Buat folder project di htdocs
- [x] Buat file config.php
- [x] Buat file database.sql
- [x] Buat file index.php
- [x] Buat file register.php

### 🔄 Tahap 2: Database & Authentication (Prioritas 1)
- [ ] Lengkapi file database.sql dengan semua tabel yang diperlukan
- [ ] Buat file login.php untuk semua role
- [ ] Buat file logout.php
- [ ] Buat sistem session untuk mengecek login
- [ ] Test login untuk masing-masing role

### 🔄 Tahap 3: Dashboard & Layout (Prioritas 2)
- [ ] Buat template header.php dan footer.php dengan Bootstrap
- [ ] Buat navbar.php yang berbeda untuk setiap role
- [ ] Buat dashboard admin (admin/dashboard.php)
- [ ] Buat dashboard dosen (dosen/dashboard.php)
- [ ] Buat dashboard mahasiswa (mahasiswa/dashboard.php)
- [ ] Tambahkan CSS custom di assets/css/style.css

### 🔄 Tahap 4: Fitur Admin (Prioritas 3)
- [ ] Halaman laporan absensi mahasiswa (admin/laporan_mahasiswa.php)
- [ ] Halaman laporan absensi dosen (admin/laporan_dosen.php)
- [ ] Halaman kelola jadwal (admin/kelola_jadwal.php)
  - Tambah jadwal
  - Hapus jadwal
  - Edit jadwal
- [ ] Halaman kelola forum (admin/kelola_forum.php)
  - Tambah forum
  - Hapus forum
  - Edit forum
- [ ] Halaman kelola hari libur (admin/kelola_libur.php)
  - Tambah hari libur
  - Hapus hari libur

### 🔄 Tahap 5: Fitur Dosen (Prioritas 4)
- [ ] **Halaman absensi harian dosen (dosen/absensi_harian.php) - PRIORITAS UTAMA**
  - Form absensi harian
  - Validasi: hanya bisa absen 1x per hari
  - Validasi: tidak bisa absen di weekend (Sabtu & Minggu)
  - Validasi: tidak bisa absen di hari libur nasional
  - Notifikasi jika belum absen hari ini (hanya di hari kerja)
- [ ] Halaman histori absensi dosen (dosen/histori_absensi.php)
- [ ] Halaman kelola jadwal absensi (dosen/kelola_jadwal.php)
- [ ] Halaman kelola mahasiswa (dosen/kelola_mahasiswa.php)
  - Lihat daftar mahasiswa
  - Ubah status absensi (Hadir, Absen, Izin, Sakit)
- [ ] Halaman laporan absensi mahasiswa (dosen/laporan_absensi.php)
- [ ] Halaman kelola forum (dosen/kelola_forum.php)

### 🔄 Tahap 6: Fitur Mahasiswa (Prioritas 5)
- [ ] Halaman join forum (mahasiswa/join_forum.php)
- [ ] Halaman melakukan absensi (mahasiswa/absensi.php)
  - Hanya bisa absen sesuai jadwal
  - Validasi waktu absensi
- [ ] Halaman histori absensi (mahasiswa/histori_absensi.php)
- [ ] Halaman lihat forum (mahasiswa/forum.php)

### 🔄 Tahap 7: Testing & Finishing (Prioritas 6)
- [ ] Test semua fitur untuk role Admin
- [ ] Test semua fitur untuk role Dosen
- [ ] Test semua fitur untuk role Mahasiswa
- [ ] Perbaiki bug yang ditemukan
- [ ] Rapikan tampilan UI
- [ ] Tambahkan alert/notifikasi untuk setiap aksi

## 💡 Tips Pengerjaan
### ⚠️ PENTING - Fitur Absensi Dosen:
1. **Dosen WAJIB absen setiap hari kerja (Senin-Jumat)** tanpa perlu join forum
2. **Dosen TIDAK PERLU absen di:**
   - Weekend: Sabtu & Minggu (otomatis terdeteksi)
   - Hari libur nasional (dari tabel `hari_libur`)
3. **Validasi penting untuk absensi dosen:**
   - Cek apakah hari ini weekend (Sabtu/Minggu)
   - Cek apakah hari ini adalah hari libur nasional (query ke tabel `hari_libur`)
   - Cek apakah dosen sudah absen hari ini (UNIQUE constraint: dosen_id + tanggal)
   - Tampilkan notifikasi di dashboard jika dosen belum absen hari ini
4. **Contoh implementasi (sudah ada di functions.php):**
   ```php
   // Cek weekend
   $day_of_week = date('N', strtotime($tanggal)); // 1=Senin, 7=Minggu
   if ($day_of_week == 6 || $day_of_week == 7) {
       // Sabtu atau Minggu = libur
   }
   
   // Cek hari libur nasional
   $query = "SELECT * FROM hari_libur WHERE tanggal = '$today'";
   
   // Cek sudah absen
   $query = "SELECT * FROM absensi_dosen 
             WHERE dosen_id = $dosen_id AND tanggal = '$today'";
   ```
5. **Gunakan fungsi helper yang sudah tersedia:**
   - `cekHariLibur($conn, $tanggal)` - otomatis cek weekend & hari libur
   - `getStatusAbsensiDosenHariIni($conn, $dosen_id)` - get status lengkapJika ada result = sudah absen, disable tombol absen
   ```

### Untuk yang Mengerjakan Backend:
1. **Mulai dari struktur database** - pastikan relasi antar tabel sudah benar
2. **Gunakan prepared statement** untuk keamanan query SQL
3. **Buat fungsi helper** di includes/functions.php untuk code yang sering dipakai
4. **Validasi input** dari user sebelum masuk ke database
5. **Gunakan session** untuk menyimpan data login user
6. **Fungsi helper yang perlu dibuat:**
   - `cekHariLibur($tanggal)` - return true/false
   - `cekDosenSudahAbsen($dosen_id, $tanggal)` - return true/false
   - `hitungPersentaseKehadiran($user_id, $role)` - return persentase

### Untuk yang Mengerjakan Frontend:
1. **Gunakan Bootstrap 5** untuk mempercepat styling
2. **Buat template** yang konsisten (header, navbar, footer)
3. **Gunakan class Bootstrap** seperti: container, card, table, btn, form-control
4. **Responsive design** - pastikan tampilan bagus di mobile dan desktop
5. **Placeholder images** - gunakan https://via.placeholder.com/ atau https://placehold.co/
6. **Notifikasi penting di dashboard dosen:**
   - Badge merah jika belum absen hari ini
   - Badge hijau jika sudah absen
   - Info jika hari libur

### Contoh Penggunaan Placeholder Image:
```html
<img src="https://via.placeholder.com/150" alt="Profile">
<img src="https://placehold.co/600x400" alt="Banner">
```

## 🎨 Panduan Styling dengan Bootstrap

### Struktur Halaman Umum:
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Presensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <!-- Konten halaman di sini -->
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

### Komponen Bootstrap yang Sering Dipakai:
- **Card**: untuk kotak informasi
- **Table**: untuk menampilkan data
- **Form**: untuk input data
- **Button**: untuk tombol aksi
- **Modal**: untuk popup konfirmasi
- **Alert**: untuk notifikasi

## 🔒 Keamanan Dasar

1. **Password Hashing**: Gunakan `password_hash()` dan `password_verify()`
   ```php
   // Saat register
   $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   
   // Saat login
   if (password_verify($password, $hashed_password)) {
       // Login berhasil
   }
   ```

2. **Session Management**: Selalu cek session di setiap halaman
   ```php
   session_start();
   if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
       header('Location: ../login.php');
       exit();
   }
   ```

3. **SQL Injection Prevention**: Gunakan prepared statement
   ```php
   $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
   $stmt->bind_param("s", $username);
   $stmt->execute();
   ```

## 🤝 Pembagian Tugas (Saran)

### Anggota 1: Database & Authentication
- Setup database lengkap (7 tabel)
- Sistem login/logout
- Sistem session
- File config.php
- File functions.php (helper functions)

### Anggota 2: Fitur Admin
- Dashboard admin
- Laporan absensi dosen & mahasiswa
- CRUD jadwal absensi
- CRUD forum
- **CRUD hari libur (BARU)**

### Anggota 3: Fitur Dosen - Absensi Harian
- Dashboard dosen
- **Sistem absensi harian dosen (PRIORITAS TINGGI)**
- **Histori absensi dosen**
- **Validasi hari libur**
- Notifikasi belum absen

### Anggota 4: Fitur Dosen - Kelola Mahasiswa
- Kelola jadwal absensi mahasiswa
- Kelola status absensi mahasiswa
- Kelola forum pembelajaran
- Laporan absensi mahasiswa

### Anggota 5: Fitur Mahasiswa
- Dashboard mahasiswa
- Sistem absensi mahasiswa
- Histori absensi
- Join forum

## Troubleshooting

### Error: Connection Failed
- Cek apakah MySQL di XAMPP sudah running
- Pastikan kredensial di config.php benar

### Error: Session Not Working
- Pastikan `session_start()` ada di awal file PHP
- Cek permission folder session PHP

### Error: Cannot Redirect
- Pastikan tidak ada output sebelum `header()`
- Gunakan `exit()` setelah `header()`

### Error: Bootstrap Tidak Muncul
- Cek koneksi internet (jika menggunakan CDN)
- Atau download Bootstrap dan simpan di folder assets

## Resources & Referensi

- **Bootstrap 5 Documentation**: https://getbootstrap.com/docs/5.3/
- **PHP Manual**: https://www.php.net/manual/en/
- **MySQL Documentation**: https://dev.mysql.com/doc/
- **W3Schools PHP**: https://www.w3schools.com/php/

=======
# Sistem Presensi Dosen dan Mahasiswa

## 📋 Deskripsi Project
Project ini adalah sistem presensi berbasis web untuk Dosen dan Mahasiswa yang dibuat sebagai tugas UAS. Sistem ini memungkinkan admin, dosen, dan mahasiswa untuk mengelola dan melakukan absensi secara online.

## 👥 Role Pengguna

### 1. Admin
Admin memiliki akses penuh untuk mengelola sistem:
- ✅ Melihat laporan absensi dari mahasiswa dan dosen
- ✅ Menambah atau menghapus jadwal absensi bagi mahasiswa
- ✅ Menambah atau menghapus forum untuk dosen dan mahasiswa
- ✅ Mengelola hari libur (dosen tidak wajib absen di hari libur)
- ✅ Melihat rekap kehadiran dosen harian

### 2. Dosen
Dosen dapat mengelola kelas dan mahasiswa:
- ✅ **Melakukan absensi harian (MANDATORY)** - wajib absen setiap hari kerja (Senin-Jumat)
- ✅ **Tidak perlu absen di weekend (Sabtu & Minggu) dan hari libur nasional**
- ✅ Menambahkan atau menghapus jadwal absensi untuk mahasiswa
- ✅ Mengubah status mahasiswa (Hadir, Absen, Izin/Sakit)
- ✅ Melihat data mahasiswa yang sudah absen
- ✅ Menambah atau menghapus forum pembelajaran untuk mahasiswa
- ✅ Melihat histori absensi diri sendiri

### 3. Mahasiswa
Mahasiswa dapat melakukan absensi dan melihat riwayat:
- ✅ Melakukan absensi pada forum yang sudah ditetapkan dosen atau admin
- ✅ Melihat data histori absensi
- ✅ Melihat dosen yang melakukan absensi

## 🛠️ Teknologi yang Digunakan
- **Backend**: PHP Native
- **Database**: MySQL
- **Frontend**: HTML, CSS, Bootstrap 5
- **Server**: XAMPP (Apache + MySQL)

## 📁 Struktur Database

### Tabel: users
Menyimpan data semua pengguna (Admin, Dosen, Mahasiswa)
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- username (VARCHAR)
- password (VARCHAR) - gunakan MD5 atau password_hash()
- nama_lengkap (VARCHAR)
- email (VARCHAR)
- role (ENUM: 'admin', 'dosen', 'mahasiswa')
- nim_nip (VARCHAR) - untuk identitas mahasiswa/dosen
- created_at (TIMESTAMP)
```

### Tabel: forum
Menyimpan data forum/kelas pembelajaran
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- nama_forum (VARCHAR)
- deskripsi (TEXT)
- dosen_id (INT, FOREIGN KEY ke users)
- kode_forum (VARCHAR) - kode unik untuk join forum
- created_at (TIMESTAMP)
```

### Tabel: jadwal_absensi
Menyimpan jadwal absensi
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- forum_id (INT, FOREIGN KEY ke forum)
- tanggal (DATE)
- waktu_mulai (TIME)
- waktu_selesai (TIME)
- status (ENUM: 'aktif', 'selesai')
- created_by (INT, FOREIGN KEY ke users)
- created_at (TIMESTAMP)
```

### Tabel: absensi
Menyimpan data absensi mahasiswa
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- jadwal_id (INT, FOREIGN KEY ke jadwal_absensi)
- mahasiswa_id (INT, FOREIGN KEY ke users)
- status (ENUM: 'hadir', 'absen', 'izin', 'sakit')
- waktu_absen (DATETIME)
- keterangan (TEXT)
- created_at (TIMESTAMP)
```

### Tabel: forum_mahasiswa
Relasi many-to-many antara forum dan mahasiswa
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- forum_id (INT, FOREIGN KEY ke forum)
- mahasiswa_id (INT, FOREIGN KEY ke users)
- tanggal_join (TIMESTAMP)
```

### Tabel: absensi_dosen
Menyimpan data absensi harian dosen (MANDATORY)
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- dosen_id (INT, FOREIGN KEY ke users)
- tanggal (DATE)
- waktu_absen (DATETIME)
- status (ENUM: 'hadir', 'izin', 'sakit', 'alpha')
- keterangan (TEXT)
- created_at (TIMESTAMP)
- UNIQUE: dosen_id + tanggal (1 dosen hanya bisa absen 1x per hari)
```

### Tabel: hari_libur
Menyimpan data hari libur nasional (dosen tidak wajib absen)
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- tanggal (DATE, UNIQUE)
- keterangan (VARCHAR) - nama libur
- created_by (INT, FOREIGN KEY ke users)
- created_at (TIMESTAMP)
```
**Catatan:** Weekend (Sabtu & Minggu) otomatis dianggap hari libur, tidak perlu diinput ke tabel ini.

## 📂 Struktur File Project

```
SistemPresensi/
│
├── config.php                 # Konfigurasi database
├── database.sql              # File SQL untuk membuat database
├── index.php                 # Halaman landing page
├── register.php              # Halaman registrasi
├── login.php                 # Halaman login
├── logout.php                # Proses logout
│
├── admin/
│   ├── dashboard.php         # Dashboard admin
│   ├── laporan_mahasiswa.php # Laporan absensi mahasiswa
│   ├── laporan_dosen.php     # Laporan absensi dosen
│   ├── kelola_jadwal.php     # Mengelola jadwal absensi mahasiswa
│   ├── kelola_forum.php      # Mengelola forum
│   ├── kelola_libur.php      # Mengelola hari libur
│   └── kelola_user.php       # Mengelola user (opsional)
│
├── dosen/
│   ├── dashboard.php         # Dashboard dosen
│   ├── absensi_harian.php    # Absensi harian dosen (MANDATORY)
│   ├── histori_absensi.php   # Histori absensi dosen
│   ├── kelola_jadwal.php     # Mengelola jadwal absensi mahasiswa
│   ├── kelola_mahasiswa.php  # Melihat & ubah status mahasiswa
│   ├── kelola_forum.php      # Mengelola forum pembelajaran
│   └── laporan_absensi.php   # Laporan absensi mahasiswa
│
├── mahasiswa/
│   ├── dashboard.php         # Dashboard mahasiswa
│   ├── absensi.php           # Halaman melakukan absensi
│   ├── histori_absensi.php   # Melihat riwayat absensi
│   ├── forum.php             # Melihat forum yang diikuti
│   └── join_forum.php        # Join forum baru
│
├── assets/
│   ├── css/
│   │   └── style.css         # Custom CSS
│   ├── js/
│   │   └── script.js         # Custom JavaScript
│   └── img/
│       └── placeholder/      # Folder untuk placeholder images
│
└── includes/
    ├── header.php            # Header template
    ├── footer.php            # Footer template
    ├── navbar.php            # Navbar untuk setiap role
    └── functions.php         # Fungsi-fungsi helper
```

## 🚀 Cara Setup Project

### 1. Persiapan Environment
- Install XAMPP (sudah terinstall)
- Pastikan Apache dan MySQL sudah running di XAMPP Control Panel

### 2. Setup Database
```bash
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Buat database baru dengan nama: sistem_presensi
3. Import file database.sql ke database yang baru dibuat
4. Atau jalankan query SQL yang ada di file database.sql
```

### 3. Konfigurasi Database
Edit file `config.php`:
```php
<?php
$host = 'localhost';
$dbname = 'sistem_presensi';
$username = 'root';
$password = ''; // kosongkan jika default XAMPP

$conn = mysqli_connect($host, $username, $password, $dbname);
?>
```

### 4. Akses Project
Buka browser dan akses:
```
http://localhost/SistemPresensi
```

## 👤 Default Login (setelah setup database)

### Admin
- Username: `admin`
- Password: `admin123`

### Dosen (contoh)
- Username: `dosen1`
- Password: `dosen123`

### Mahasiswa (contoh)
- Username: `mahasiswa1`
- Password: `mhs123`

## 📝 Tahapan Pengerjaan Project

### ✅ Tahap 1: Setup Awal (Sudah Dikerjakan)
- [x] Buat folder project di htdocs
- [x] Buat file config.php
- [x] Buat file database.sql
- [x] Buat file index.php
- [x] Buat file register.php

### 🔄 Tahap 2: Database & Authentication (Prioritas 1)
- [ ] Lengkapi file database.sql dengan semua tabel yang diperlukan
- [ ] Buat file login.php untuk semua role
- [ ] Buat file logout.php
- [ ] Buat sistem session untuk mengecek login
- [ ] Test login untuk masing-masing role

### 🔄 Tahap 3: Dashboard & Layout (Prioritas 2)
- [ ] Buat template header.php dan footer.php dengan Bootstrap
- [ ] Buat navbar.php yang berbeda untuk setiap role
- [ ] Buat dashboard admin (admin/dashboard.php)
- [ ] Buat dashboard dosen (dosen/dashboard.php)
- [ ] Buat dashboard mahasiswa (mahasiswa/dashboard.php)
- [ ] Tambahkan CSS custom di assets/css/style.css

### 🔄 Tahap 4: Fitur Admin (Prioritas 3)
- [ ] Halaman laporan absensi mahasiswa (admin/laporan_mahasiswa.php)
- [ ] Halaman laporan absensi dosen (admin/laporan_dosen.php)
- [ ] Halaman kelola jadwal (admin/kelola_jadwal.php)
  - Tambah jadwal
  - Hapus jadwal
  - Edit jadwal
- [ ] Halaman kelola forum (admin/kelola_forum.php)
  - Tambah forum
  - Hapus forum
  - Edit forum
- [ ] Halaman kelola hari libur (admin/kelola_libur.php)
  - Tambah hari libur
  - Hapus hari libur

### 🔄 Tahap 5: Fitur Dosen (Prioritas 4)
- [ ] **Halaman absensi harian dosen (dosen/absensi_harian.php) - PRIORITAS UTAMA**
  - Form absensi harian
  - Validasi: hanya bisa absen 1x per hari
  - Validasi: tidak bisa absen di weekend (Sabtu & Minggu)
  - Validasi: tidak bisa absen di hari libur nasional
  - Notifikasi jika belum absen hari ini (hanya di hari kerja)
- [ ] Halaman histori absensi dosen (dosen/histori_absensi.php)
- [ ] Halaman kelola jadwal absensi (dosen/kelola_jadwal.php)
- [ ] Halaman kelola mahasiswa (dosen/kelola_mahasiswa.php)
  - Lihat daftar mahasiswa
  - Ubah status absensi (Hadir, Absen, Izin, Sakit)
- [ ] Halaman laporan absensi mahasiswa (dosen/laporan_absensi.php)
- [ ] Halaman kelola forum (dosen/kelola_forum.php)

### 🔄 Tahap 6: Fitur Mahasiswa (Prioritas 5)
- [ ] Halaman join forum (mahasiswa/join_forum.php)
- [ ] Halaman melakukan absensi (mahasiswa/absensi.php)
  - Hanya bisa absen sesuai jadwal
  - Validasi waktu absensi
- [ ] Halaman histori absensi (mahasiswa/histori_absensi.php)
- [ ] Halaman lihat forum (mahasiswa/forum.php)

### 🔄 Tahap 7: Testing & Finishing (Prioritas 6)
- [ ] Test semua fitur untuk role Admin
- [ ] Test semua fitur untuk role Dosen
- [ ] Test semua fitur untuk role Mahasiswa
- [ ] Perbaiki bug yang ditemukan
- [ ] Rapikan tampilan UI
- [ ] Tambahkan alert/notifikasi untuk setiap aksi

## 💡 Tips Pengerjaan
### ⚠️ PENTING - Fitur Absensi Dosen:
1. **Dosen WAJIB absen setiap hari kerja (Senin-Jumat)** tanpa perlu join forum
2. **Dosen TIDAK PERLU absen di:**
   - Weekend: Sabtu & Minggu (otomatis terdeteksi)
   - Hari libur nasional (dari tabel `hari_libur`)
3. **Validasi penting untuk absensi dosen:**
   - Cek apakah hari ini weekend (Sabtu/Minggu)
   - Cek apakah hari ini adalah hari libur nasional (query ke tabel `hari_libur`)
   - Cek apakah dosen sudah absen hari ini (UNIQUE constraint: dosen_id + tanggal)
   - Tampilkan notifikasi di dashboard jika dosen belum absen hari ini
4. **Contoh implementasi (sudah ada di functions.php):**
   ```php
   // Cek weekend
   $day_of_week = date('N', strtotime($tanggal)); // 1=Senin, 7=Minggu
   if ($day_of_week == 6 || $day_of_week == 7) {
       // Sabtu atau Minggu = libur
   }
   
   // Cek hari libur nasional
   $query = "SELECT * FROM hari_libur WHERE tanggal = '$today'";
   
   // Cek sudah absen
   $query = "SELECT * FROM absensi_dosen 
             WHERE dosen_id = $dosen_id AND tanggal = '$today'";
   ```
5. **Gunakan fungsi helper yang sudah tersedia:**
   - `cekHariLibur($conn, $tanggal)` - otomatis cek weekend & hari libur
   - `getStatusAbsensiDosenHariIni($conn, $dosen_id)` - get status lengkapJika ada result = sudah absen, disable tombol absen
   ```

### Untuk yang Mengerjakan Backend:
1. **Mulai dari struktur database** - pastikan relasi antar tabel sudah benar
2. **Gunakan prepared statement** untuk keamanan query SQL
3. **Buat fungsi helper** di includes/functions.php untuk code yang sering dipakai
4. **Validasi input** dari user sebelum masuk ke database
5. **Gunakan session** untuk menyimpan data login user
6. **Fungsi helper yang perlu dibuat:**
   - `cekHariLibur($tanggal)` - return true/false
   - `cekDosenSudahAbsen($dosen_id, $tanggal)` - return true/false
   - `hitungPersentaseKehadiran($user_id, $role)` - return persentase

### Untuk yang Mengerjakan Frontend:
1. **Gunakan Bootstrap 5** untuk mempercepat styling
2. **Buat template** yang konsisten (header, navbar, footer)
3. **Gunakan class Bootstrap** seperti: container, card, table, btn, form-control
4. **Responsive design** - pastikan tampilan bagus di mobile dan desktop
5. **Placeholder images** - gunakan https://via.placeholder.com/ atau https://placehold.co/
6. **Notifikasi penting di dashboard dosen:**
   - Badge merah jika belum absen hari ini
   - Badge hijau jika sudah absen
   - Info jika hari libur

### Contoh Penggunaan Placeholder Image:
```html
<img src="https://via.placeholder.com/150" alt="Profile">
<img src="https://placehold.co/600x400" alt="Banner">
```

## 🎨 Panduan Styling dengan Bootstrap

### Struktur Halaman Umum:
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Presensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <!-- Konten halaman di sini -->
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

### Komponen Bootstrap yang Sering Dipakai:
- **Card**: untuk kotak informasi
- **Table**: untuk menampilkan data
- **Form**: untuk input data
- **Button**: untuk tombol aksi
- **Modal**: untuk popup konfirmasi
- **Alert**: untuk notifikasi

## 🔒 Keamanan Dasar

1. **Password Hashing**: Gunakan `password_hash()` dan `password_verify()`
   ```php
   // Saat register
   $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   
   // Saat login
   if (password_verify($password, $hashed_password)) {
       // Login berhasil
   }
   ```

2. **Session Management**: Selalu cek session di setiap halaman
   ```php
   session_start();
   if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
       header('Location: ../login.php');
       exit();
   }
   ```

3. **SQL Injection Prevention**: Gunakan prepared statement
   ```php
   $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
   $stmt->bind_param("s", $username);
   $stmt->execute();
   ```

## 🤝 Pembagian Tugas (Saran)

### Anggota 1: Database & Authentication
- Setup database lengkap (7 tabel)
- Sistem login/logout
- Sistem session
- File config.php
- File functions.php (helper functions)

### Anggota 2: Fitur Admin
- Dashboard admin
- Laporan absensi dosen & mahasiswa
- CRUD jadwal absensi
- CRUD forum
- **CRUD hari libur (BARU)**

### Anggota 3: Fitur Dosen - Absensi Harian
- Dashboard dosen
- **Sistem absensi harian dosen (PRIORITAS TINGGI)**
- **Histori absensi dosen**
- **Validasi hari libur**
- Notifikasi belum absen

### Anggota 4: Fitur Dosen - Kelola Mahasiswa
- Kelola jadwal absensi mahasiswa
- Kelola status absensi mahasiswa
- Kelola forum pembelajaran
- Laporan absensi mahasiswa

### Anggota 5: Fitur Mahasiswa
- Dashboard mahasiswa
- Sistem absensi mahasiswa
- Histori absensi
- Join forum

## Troubleshooting

### Error: Connection Failed
- Cek apakah MySQL di XAMPP sudah running
- Pastikan kredensial di config.php benar

### Error: Session Not Working
- Pastikan `session_start()` ada di awal file PHP
- Cek permission folder session PHP

### Error: Cannot Redirect
- Pastikan tidak ada output sebelum `header()`
- Gunakan `exit()` setelah `header()`

### Error: Bootstrap Tidak Muncul
- Cek koneksi internet (jika menggunakan CDN)
- Atau download Bootstrap dan simpan di folder assets

## Resources & Referensi

- **Bootstrap 5 Documentation**: https://getbootstrap.com/docs/5.3/
- **PHP Manual**: https://www.php.net/manual/en/
- **MySQL Documentation**: https://dev.mysql.com/doc/
- **W3Schools PHP**: https://www.w3schools.com/php/

>>>>>>> 45dcb6cd97da2ba7dc48c44310df4950700fda78
