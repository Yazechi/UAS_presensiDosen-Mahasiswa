<<<<<<< HEAD
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('dosen');

$dosen_id = $_SESSION['user_id'];
$nama = $_SESSION['nama'];

$status_absen = getStatusAbsensiDosenHariIni($conn, $dosen_id);

$total_forum = $conn->query("SELECT COUNT(*) as total FROM forums WHERE dosen_id='$dosen_id'")->fetch_assoc()['total'];
$total_mahasiswa = $conn->query("SELECT COUNT(DISTINCT fm.mahasiswa_id) as total FROM forum_mahasiswa fm JOIN forums f ON fm.forum_id = f.id WHERE f.dosen_id='$dosen_id'")->fetch_assoc()['total'];
$jadwal_aktif = $conn->query("SELECT COUNT(*) as total FROM jadwal_absensi ja JOIN forums f ON ja.forum_id = f.id WHERE f.dosen_id='$dosen_id' AND ja.status='aktif'")->fetch_assoc()['total'];

$persentase_kehadiran = hitungPersentaseKehadiranDosen($conn, $dosen_id);

include __DIR__ . '/../includes/header.php';
?>

<div class="dashboard-wrapper">
  <div class="container">
    <div class="dashboard-header">
      <h1>Dashboard Dosen</h1>
      <p>Selamat datang, <strong><?= htmlspecialchars($nama) ?></strong></p>
    </div>

    <?php if (!$status_absen['sudah_absen'] && !$status_absen['is_libur']): ?>
    <div class="alert alert-warning">
      <strong>Perhatian!</strong> Anda belum melakukan absensi hari ini. <a href="absensi_harian.php" class="alert-link">Absen sekarang</a>
    </div>
    <?php elseif ($status_absen['is_libur']): ?>
    <div class="alert alert-info">
      <strong>Info:</strong> <?= $status_absen['message'] ?>
    </div>
    <?php else: ?>
    <div class="alert alert-success">
      <strong>Selamat!</strong> Anda sudah absen hari ini pada <?= date('H:i', strtotime($status_absen['data']['waktu_absen'])) ?>
    </div>
    <?php endif; ?>

    <div class="stats-row">
      <div class="stat-card">
        <h6>Total Forum</h6>
        <p class="stat-value"><?= $total_forum ?></p>
      </div>
      <div class="stat-card">
        <h6>Total Mahasiswa</h6>
        <p class="stat-value"><?= $total_mahasiswa ?></p>
      </div>
      <div class="stat-card">
        <h6>Jadwal Aktif</h6>
        <p class="stat-value"><?= $jadwal_aktif ?></p>
      </div>
      <div class="stat-card">
        <h6>Kehadiran Bulan Ini</h6>
        <p class="stat-value"><?= $persentase_kehadiran ?>%</p>
      </div>
    </div>

    <div class="menu-section">
      <h5>Menu Dosen</h5>
      <div class="menu-grid">
        <div class="menu-card">
          <h6>Absensi Harian</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Lakukan absensi harian (wajib Senin-Jumat)</p>
          <a href="absensi_harian.php" class="menu-btn">Absen Sekarang</a>
        </div>
        <div class="menu-card">
          <h6>Kelola Forum</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Kelola forum pembelajaran Anda</p>
          <a href="kelola_forum.php" class="menu-btn">Kelola Forum</a>
        </div>
        <div class="menu-card">
          <h6>Kelola Jadwal</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Kelola jadwal absensi mahasiswa</p>
          <a href="kelola_jadwal.php" class="menu-btn">Kelola Jadwal</a>
        </div>
        <div class="menu-card">
          <h6>Kelola Mahasiswa</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Kelola status absensi mahasiswa</p>
          <a href="kelola_mahasiswa.php" class="menu-btn">Kelola Mahasiswa</a>
        </div>
        <div class="menu-card">
          <h6>Histori Absensi Saya</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Lihat riwayat absensi harian Anda</p>
          <a href="histori_absensi.php" class="menu-btn">Lihat Histori</a>
        </div>
        <div class="menu-card">
          <h6>Laporan Absensi Mahasiswa</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Lihat laporan kehadiran mahasiswa</p>
          <a href="laporan_absensi.php" class="menu-btn">Lihat Laporan</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
=======
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
>>>>>>> 45dcb6cd97da2ba7dc48c44310df4950700fda78
