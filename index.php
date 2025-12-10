<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Presensi Dosen & Mahasiswa</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .feature-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">Sistem Presensi Dosen & Mahasiswa</h1>
                    <p class="lead mb-4">Kelola absensi dosen dan mahasiswa dengan mudah, cepat, dan efisien. Sistem terintegrasi untuk monitoring kehadiran secara real-time.</p>
                    <div class="d-flex gap-3">
                        <a href="login.php" class="btn btn-light btn-lg px-4">Login Sekarang</a>
                        <a href="register.php" class="btn btn-outline-light btn-lg px-4">Daftar Akun</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://via.placeholder.com/500x400/667eea/ffffff?text=Sistem+Presensi" class="img-fluid rounded shadow-lg" alt="Sistem Presensi">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Fitur Unggulan</h2>
                <p class="text-muted">Sistem presensi yang lengkap untuk kebutuhan kampus Anda</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card shadow-sm border-0 p-4 text-center">
                        <div class="feature-icon text-primary">👨‍💼</div>
                        <h5 class="fw-bold">Dashboard Admin</h5>
                        <p class="text-muted">Kelola jadwal, forum, hari libur, dan monitoring absensi dosen & mahasiswa</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card shadow-sm border-0 p-4 text-center">
                        <div class="feature-icon text-success">👨‍🏫</div>
                        <h5 class="fw-bold">Absensi Dosen</h5>
                        <p class="text-muted">Dosen wajib absen harian (Senin-Jumat), kelola forum & mahasiswa dengan mudah</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card shadow-sm border-0 p-4 text-center">
                        <div class="feature-icon text-info">👨‍🎓</div>
                        <h5 class="fw-bold">Absensi Mahasiswa</h5>
                        <p class="text-muted">Mahasiswa dapat absen sesuai jadwal dan melihat histori kehadiran</p>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="card feature-card shadow-sm border-0 p-4 text-center">
                        <div class="feature-icon text-warning">📊</div>
                        <h5 class="fw-bold">Laporan Real-time</h5>
                        <p class="text-muted">Monitor kehadiran dosen dan mahasiswa dengan laporan detail</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card shadow-sm border-0 p-4 text-center">
                        <div class="feature-icon text-danger">📅</div>
                        <h5 class="fw-bold">Kelola Jadwal</h5>
                        <p class="text-muted">Atur jadwal absensi dan hari libur dengan fleksibel</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card shadow-sm border-0 p-4 text-center">
                        <div class="feature-icon text-secondary">🔒</div>
                        <h5 class="fw-bold">Aman & Terpercaya</h5>
                        <p class="text-muted">Sistem keamanan berlapis dengan autentikasi user</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Siap Memulai?</h2>
            <p class="lead mb-4">Bergabunglah dengan sistem presensi modern untuk kampus Anda</p>
            <a href="login.php" class="btn btn-light btn-lg px-5">Mulai Sekarang</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-0">&copy; 2024 Sistem Presensi Dosen & Mahasiswa. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
=======
<?php
include 'config.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        if (password_verify($password, $data['password'])) {
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['nama'] = $data['nama_lengkap'];

            if ($data['role'] == 'admin') header("Location: admin/dashboard.php");
            else if ($data['role'] == 'dosen') header("Location: dosen/dashboard.php");
            else if ($data['role'] == 'mahasiswa') header("Location: mahasiswa/dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Presensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 400px;">
        <div class="text-center mb-4">
            <img src="https://via.placeholder.com/100" class="rounded-circle mb-3" alt="Logo">
            <h3>Login Sistem</h3>
        </div>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <small>Belum punya akun? <a href="register.php">Daftar disini</a></small>
        </div>
    </div>
</body>

>>>>>>> 45dcb6cd97da2ba7dc48c44310df4950700fda78
</html>