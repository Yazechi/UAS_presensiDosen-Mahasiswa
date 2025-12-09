<?php
include '../config.php';
// Panggil functions.php untuk logika cek libur/absen
include '../functions.php'; 

// 1. Cek Login & Session
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'dosen') {
    
}

$dosen_id = $_SESSION['user_id'] ?? 1;
$nama_dosen = $_SESSION['nama'] ?? "Bapak/Ibu Dosen";
$hari_ini = date('Y-m-d');

// 2. LOGIKA NOTIFIKASI ABSENSI (Sesuai Checklist)
$info_libur = cekHariLibur($conn, $hari_ini);
$data_absen = getStatusAbsensiDosenHariIni($conn, $dosen_id);

$notif_class = "";
$notif_pesan = "";
$tampil_tombol_absen = false;

if ($info_libur) {
    // Jika Libur
    $notif_class = "alert-info";
    $notif_pesan = "<i class='bi bi-info-circle-fill'></i> Hari ini <b>" . $info_libur . "</b>. Anda tidak perlu absen.";
} elseif ($data_absen) {
    // Jika Sudah Absen
    $jam = date('H:i', strtotime($data_absen['waktu_absen'])); // Sesuaikan nama kolom DB teman
    $notif_class = "alert-success";
    $notif_pesan = "<i class='bi bi-check-circle-fill'></i> Terima kasih, Anda sudah absen hari ini pada pukul <b>$jam</b>.";
} else {
    // Jika BELUM Absen & Hari Kerja (PENTING)
    $notif_class = "alert-danger";
    $notif_pesan = "<i class='bi bi-exclamation-triangle-fill'></i> <b>PERHATIAN:</b> Anda belum melakukan absensi hari ini. Silahkan absen sebelum jam kerja berakhir!";
    $tampil_tombol_absen = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .card-menu { transition: transform 0.2s; cursor: pointer; }
        .card-menu:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">SISTEM PRESENSI</a>
        <span class="navbar-text text-white">
            Halo, <?= $nama_dosen; ?>
        </span>
    </div>
</nav>

<div class="container">
    
    <div class="alert <?= $notif_class; ?> shadow-sm d-flex justify-content-between align-items-center" role="alert">
        <div><?= $notif_pesan; ?></div>
        <?php if($tampil_tombol_absen): ?>
            <a href="absensi_harian.php" class="btn btn-danger btn-sm fw-bold">ABSEN SEKARANG</a>
        <?php endif; ?>
    </div>

    <div class="row mt-4">
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm card-menu text-center py-4" onclick="window.location='absensi_harian.php'">
                <div class="card-body">
                    <i class="bi bi-fingerprint text-primary display-4"></i>
                    <h5 class="card-title mt-3">Absensi Harian</h5>
                    <p class="card-text text-muted small">Lakukan absensi kehadiran Anda setiap hari kerja.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm card-menu text-center py-4" onclick="window.location='kelola_jadwal.php'">
                <div class="card-body">
                    <i class="bi bi-calendar-week text-success display-4"></i>
                    <h5 class="card-title mt-3">Kelola Jadwal</h5>
                    <p class="card-text text-muted small">Buat jadwal pertemuan agar mahasiswa bisa absen.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm card-menu text-center py-4" onclick="window.location='laporan_absensi.php'">
                <div class="card-body">
                    <i class="bi bi-file-earmark-text text-warning display-4"></i>
                    <h5 class="card-title mt-3">Laporan Mahasiswa</h5>
                    <p class="card-text text-muted small">Lihat rekap kehadiran mahasiswa di kelas Anda.</p>
                </div>
            </div>
        </div>

         <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm card-menu text-center py-4" onclick="window.location='kelola_mahasiswa.php'">
                <div class="card-body">
                    <i class="bi bi-people text-info display-4"></i>
                    <h5 class="card-title mt-3">Data Mahasiswa</h5>
                    <p class="card-text text-muted small">Lihat daftar mahasiswa dan edit status kehadiran manual.</p>
                </div>
            </div>
        </div>

         <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm card-menu text-center py-4" onclick="window.location='kelola_forum.php'">
                <div class="card-body">
                    <i class="bi bi-collection text-secondary display-4"></i>
                    <h5 class="card-title mt-3">Kelola Kelas/Forum</h5>
                    <p class="card-text text-muted small">Buat mata kuliah atau forum diskusi baru.</p>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>