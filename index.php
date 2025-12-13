<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Presensi Dosen & Mahasiswa</title>
    <link href="/SistemPresensi/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/SistemPresensi/assets/css/style.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        .landing-hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
        }
        .hero-content {
            text-align: center;
            max-width: 800px;
            position: relative;
            z-index: 1;
        }
        /* Carousel styles moved to assets/css/style.css */
        .hero-content h1 {
            font-size: 52px;
            font-weight: 800;
            color: white;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            line-height: 1.2;
        }
        .hero-content p {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 40px;
            line-height: 1.6;
        }
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .hero-btn {
            padding: 14px 36px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .hero-btn.primary {
            background: white;
            color: #667eea;
        }
        .hero-btn.primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .hero-btn.secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
        }
        .hero-btn.secondary:hover {
            background: white;
            color: #667eea;
        }
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 36px;
            }
            .hero-content p {
                font-size: 16px;
            }
            .hero-illustration { display: none; }
        }
    </style>
</head>

<body>
    <!-- Hero Section (Bootstrap Carousel) -->
    <section class="landing-hero">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/SistemPresensi/assets/img/hero-image.png" class="d-block w-100" alt="Ilustrasi Sistem Presensi 1">
                    <div class="carousel-caption">
                        <h1>Sistem Presensi Dosen & Mahasiswa</h1>
                        <p>Kelola absensi dosen dan mahasiswa dengan mudah, cepat, dan efisien. Sistem terintegrasi untuk monitoring kehadiran secara real-time.</p>
                        <div class="hero-buttons">
                            <a href="login.php" class="hero-btn primary">Login Sekarang</a>
                            <a href="register.php" class="hero-btn secondary">Daftar Akun</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/SistemPresensi/assets/img/hero-image2.jpg" class="d-block w-100" alt="Ilustrasi Sistem Presensi 2">
                    <div class="carousel-caption">
                        <h1>Sistem Presensi Terintegrasi</h1>
                        <p>Kelola jadwal, forum, dan laporan kehadiran secara realtime dari satu dashboard.</p>
                        <div class="hero-buttons">
                            <a href="login.php" class="hero-btn primary">Login Sekarang</a>
                            <a href="register.php" class="hero-btn secondary">Daftar Akun</a>
                        </div>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Features Section -->
    <section style="padding: 80px 0; background: #f7fafc;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="font-size: 36px; font-weight: 700; color: #2d3748; margin-bottom: 15px;">Fitur Unggulan</h2>
                <p style="font-size: 18px; color: #718096;">Sistem presensi yang lengkap untuk kebutuhan kampus Anda</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 30px;">
                <div class="card" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 12px; padding: 30px; text-align: center; transition: all 0.3s ease;">
                    <img src="/SistemPresensi/assets/img/admin.svg" alt="Admin" class="feature-icon">
                    <h5 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 15px;">Dashboard Admin</h5>
                    <p style="color: #718096; line-height: 1.6;">Kelola jadwal, forum, hari libur, dan monitoring absensi dosen & mahasiswa</p>
                </div>
                <div class="card" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 12px; padding: 30px; text-align: center; transition: all 0.3s ease;">
                    <img src="/SistemPresensi/assets/img/lecturer.svg" alt="Dosen" class="feature-icon">
                    <h5 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 15px;">Absensi Dosen</h5>
                    <p style="color: #718096; line-height: 1.6;">Dosen wajib absen harian (Senin-Jumat), kelola forum & mahasiswa dengan mudah</p>
                </div>
                <div class="card" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 12px; padding: 30px; text-align: center; transition: all 0.3s ease;">
                    <img src="/SistemPresensi/assets/img/student.svg" alt="Mahasiswa" class="feature-icon">
                    <h5 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 15px;">Absensi Mahasiswa</h5>
                    <p style="color: #718096; line-height: 1.6;">Mahasiswa dapat absen sesuai jadwal dan melihat histori kehadiran</p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
                <div class="card" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 12px; padding: 30px; text-align: center; transition: all 0.3s ease;">
                    <div style="font-size: 60px; margin-bottom: 20px;">📊</div>
                    <h5 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 15px;">Laporan Real-time</h5>
                    <p style="color: #718096; line-height: 1.6;">Monitor kehadiran dosen dan mahasiswa dengan laporan detail</p>
                </div>
                <div class="card" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 12px; padding: 30px; text-align: center; transition: all 0.3s ease;">
                    <div style="font-size: 60px; margin-bottom: 20px;">📅</div>
                    <h5 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 15px;">Kelola Jadwal</h5>
                    <p style="color: #718096; line-height: 1.6;">Atur jadwal absensi dan hari libur dengan fleksibel</p>
                </div>
                <div class="card" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 12px; padding: 30px; text-align: center; transition: all 0.3s ease;">
                    <div style="font-size: 60px; margin-bottom: 20px;">🔒</div>
                    <h5 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 15px;">Aman & Terpercaya</h5>
                    <p style="color: #718096; line-height: 1.6;">Sistem keamanan berlapis dengan autentikasi user</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section style="padding: 80px 0; background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-align: center;">
        <div class="container">
            <h2 style="font-size: 42px; font-weight: 700; margin-bottom: 20px;">Siap Memulai?</h2>
            <p style="font-size: 20px; margin-bottom: 35px; opacity: 0.95;">Bergabunglah dengan sistem presensi modern untuk kampus Anda</p>
            <a href="login.php" class="hero-btn primary">Mulai Sekarang</a>
        </div>
    </section>

    <!-- Footer -->
    <footer style="padding: 30px 0; background: #2c3e50; color: white; text-align: center;">
        <div class="container">
            <p style="margin: 0; font-size: 15px;">&copy; 2024 Sistem Presensi Dosen & Mahasiswa. All rights reserved.</p>
        </div>
    </footer>

    <script src="/SistemPresensi/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
