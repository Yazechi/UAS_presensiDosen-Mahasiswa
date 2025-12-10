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
</html>