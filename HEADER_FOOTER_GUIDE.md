# PANDUAN PENGGUNAAN HEADER, FOOTER & NAVBAR

## 📋 Overview
Sistem presensi ini menggunakan struktur header dan footer yang universal untuk semua role (Admin, Dosen, Mahasiswa). Semua asset CSS dan JavaScript hanya di-load melalui header.php untuk memastikan konsistensi.

---

## 🎯 Struktur File

### 1. Header.php (`includes/header.php`)
**Fungsi:**
- Load Bootstrap CSS dari `assets/vendor/bootstrap/css/bootstrap.min.css`
- Load Custom CSS dari `assets/css/style.css`
- Menampilkan top header bar dengan nama user dan tombol logout
- Menampilkan sidebar navigation dengan menu dinamis berdasarkan role
- Styling inline untuk layout responsif

**Fitur:**
- ✅ Fixed header di atas
- ✅ Fixed sidebar di kiri
- ✅ Menu aktif highlighting otomatis
- ✅ Icon emoji untuk setiap menu
- ✅ Responsive mobile-friendly
- ✅ Gradient background purple-blue

---

### 2. Footer.php (`includes/footer.php`)
**Fungsi:**
- Menutup tag `</main>` dari header
- Menampilkan footer copyright
- Menampilkan tanggal otomatis (hari, tanggal, bulan, tahun dalam bahasa Indonesia)
- Load Bootstrap JavaScript dari `assets/vendor/bootstrap/js/bootstrap.bundle.min.js`

**Fitur:**
- ✅ Auto-update tanggal dengan JavaScript
- ✅ Clean closing tags
- ✅ Bootstrap JS untuk dropdown & interactive components

---

### 3. Navbar.php (`includes/navbar.php`) - OPSIONAL
**Fungsi:**
- Alternative navbar dengan Bootstrap native navbar
- Bisa digunakan jika tidak ingin sidebar layout
- Dropdown menu untuk role admin

---

## 🔧 Cara Penggunaan di Setiap Halaman

### Template Standar untuk Semua File Dashboard

```php
<?php
// 1. WAJIB: Include config.php untuk koneksi database dan session
require_once __DIR__ . '/../config.php';

// 2. WAJIB: Include functions.php untuk helper functions
require_once __DIR__ . '/../includes/functions.php';

// 3. WAJIB: Cek apakah user sudah login
cekLogin();

// 4. WAJIB: Cek role user (admin/dosen/mahasiswa)
cekRole('admin'); // atau 'dosen' atau 'mahasiswa'

// 5. OPSIONAL: Logic PHP untuk halaman ini
// Query database, proses form, dll
$data = mysqli_query($conn, "SELECT * FROM users");
$statistik = mysqli_fetch_assoc($data);

// 6. WAJIB: Include header (auto load CSS & navbar)
include __DIR__ . '/../includes/header.php';
?>

<!-- 7. KONTEN HALAMAN MULAI DARI SINI -->
<div class="container-fluid">
    <h2>Judul Halaman</h2>
    
    <!-- Card, table, form, dll -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Statistik</h5>
                    <p>Data: <?= $statistik['total'] ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// 8. WAJIB: Include footer (auto load JS)
include __DIR__ . '/../includes/footer.php'; 
?>
```

---

## 📱 Menu Berdasarkan Role

### 👨‍💼 ADMIN
File location: `admin/*.php`

Menu yang tersedia:
1. 📊 **Dashboard** - `dashboard.php`
2. 👥 **Kelola User** - `kelola_user.php`
3. 💬 **Kelola Forum** - `kelola_forum.php`
4. 📅 **Kelola Jadwal** - `kelola_jadwal.php`
5. 🏖️ **Kelola Libur** - `kelola_libur.php`
6. 📋 **Data Absensi** - `data_absensi.php`
7. 📈 **Laporan Dosen** - `laporan_dosen.php`
8. 📊 **Laporan Mahasiswa** - `laporan_mahasiswa.php`

---

### 👨‍🏫 DOSEN
File location: `dosen/*.php`

Menu yang tersedia:
1. 📊 **Dashboard** - `dashboard.php`
2. ✅ **Absensi Harian** - `absensi_harian.php`
3. 📜 **Histori Absensi** - `histori_absensi.php`
4. 💬 **Kelola Forum** - `kelola_forum.php`
5. 📅 **Kelola Jadwal** - `kelola_jadwal.php`
6. 👨‍🎓 **Kelola Mahasiswa** - `kelola_mahasiswa.php`
7. 📊 **Laporan Mahasiswa** - `laporan_absensi.php`

---

### 👨‍🎓 MAHASISWA
File location: `mahasiswa/*.php`

Menu yang tersedia:
1. 📊 **Dashboard** - `dashboard.php`
2. 💬 **Forum Saya** - `forum.php`
3. ➕ **Join Forum** - `join_forum.php`
4. ✅ **Absensi** - `absensi.php`
5. 📜 **Histori Absensi** - `histori_absensi.php`

---

## 🎨 Kustomisasi Styling

### Mengubah Warna Header
Edit bagian style di `includes/header.php`:

```css
.app-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    /* Ubah gradient sesuai keinginan */
}
```

### Mengubah Lebar Sidebar
Edit variabel di `includes/header.php`:

```css
.app-sidebar {
    width: 250px; /* ubah sesuai kebutuhan */
}

.app-main, .app-footer {
    margin-left: 250px; /* sesuaikan dengan width sidebar */
}
```

---

## ⚠️ CATATAN PENTING

### ❌ JANGAN LAKUKAN INI:
```php
<!-- SALAH: Meload Bootstrap CSS/JS di file individual -->
<link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- SALAH: Membuat header/footer sendiri di file -->
<!DOCTYPE html>
<html>
<head>...</head>
<body>
```

### ✅ LAKUKAN INI:
```php
<!-- BENAR: Hanya include header dan footer -->
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
cekLogin();
cekRole('admin');
include __DIR__ . '/../includes/header.php';
?>

<!-- Konten Anda -->

<?php include __DIR__ . '/../includes/footer.php'; ?>
```

---

## 🔍 Troubleshooting

### Menu tidak muncul?
- Pastikan `$_SESSION['role']` sudah ter-set
- Cek `config.php` sudah di-include
- Cek `session_start()` sudah jalan

### CSS/JS tidak load?
- Pastikan path ke `assets/vendor/bootstrap/` benar
- Cek file Bootstrap ada di folder `assets/vendor/bootstrap/`
- Clear browser cache

### Sidebar tidak fixed?
- Pastikan tidak ada CSS conflict dari file lain
- Pastikan body tidak punya style yang override

---

## 📌 Best Practices

1. **Konsistensi**: Selalu gunakan `__DIR__` untuk path relatif
2. **Security**: Selalu cek login dan role di setiap halaman
3. **Clean Code**: Pisahkan logic PHP dari HTML
4. **Comments**: Beri komentar pada logic yang kompleks
5. **Testing**: Test setiap halaman untuk semua role

---

## 📞 Support
Jika ada masalah atau pertanyaan, silakan check:
- `README.md` untuk dokumentasi lengkap
- `includes/functions.php` untuk helper functions
- `config.php` untuk konfigurasi database
