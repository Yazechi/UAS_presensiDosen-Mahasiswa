<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('admin');

// Query untuk mendapatkan statistik
$total_dosen = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='dosen'")->fetch_assoc()['total'];
$total_mahasiswa = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='mahasiswa'")->fetch_assoc()['total'];
$total_jadwal = $conn->query("SELECT COUNT(*) as total FROM jadwal_absensi WHERE status='aktif'")->fetch_assoc()['total'];
$total_libur = $conn->query("SELECT COUNT(*) as total FROM hari_libur WHERE tanggal >= CURDATE()")->fetch_assoc()['total'];
$total_forum = $conn->query("SELECT COUNT(*) as total FROM forums")->fetch_assoc()['total'];
$absensi_hari_ini = $conn->query("SELECT COUNT(*) as total FROM absensi_dosen WHERE tanggal = CURDATE()")->fetch_assoc()['total'];

include __DIR__ . '/../includes/header.php';
?>

<div class="dashboard-wrapper">
  <div class="container">
    <div class="dashboard-header">
      <h1>Dashboard Admin</h1>
      <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></strong> | Kelola sistem presensi dengan mudah</p>
    </div>

    <!-- Statistik Cards -->
    <div class="stats-row">
      <div class="stat-card">
        <h6>Total Dosen</h6>
        <p class="stat-value"><?= $total_dosen ?></p>
      </div>
      <div class="stat-card">
        <h6>Total Mahasiswa</h6>
        <p class="stat-value"><?= $total_mahasiswa ?></p>
      </div>
      <div class="stat-card">
        <h6>Jadwal Aktif</h6>
        <p class="stat-value"><?= $total_jadwal ?></p>
      </div>
      <div class="stat-card">
        <h6>Total Forum</h6>
        <p class="stat-value"><?= $total_forum ?></p>
      </div>
      <div class="stat-card">
        <h6>Hari Libur Mendatang</h6>
        <p class="stat-value"><?= $total_libur ?></p>
      </div>
      <div class="stat-card">
        <h6>Absensi Dosen Hari Ini</h6>
        <p class="stat-value"><?= $absensi_hari_ini ?></p>
      </div>
    </div>

    <!-- Menu Laporan -->
    <div class="menu-section">
      <h5>Laporan & Monitoring</h5>
      <div class="menu-grid">
        <div class="menu-card">
          <h6>Laporan Absensi Dosen</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Lihat rekap kehadiran dosen harian dan filter berdasarkan tanggal</p>
          <a href="laporan_dosen.php" class="menu-btn">Buka Laporan</a>
        </div>
        <div class="menu-card">
          <h6>Laporan Absensi Mahasiswa</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Lihat rekap kehadiran mahasiswa per forum dan tanggal</p>
          <a href="laporan_mahasiswa.php" class="menu-btn">Buka Laporan</a>
        </div>
        <div class="menu-card">
          <h6>Data Absensi Detail</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Lihat detail absensi per jadwal dan mahasiswa</p>
          <a href="data_absensi.php" class="menu-btn">Lihat Data</a>
        </div>
      </div>
    </div>
  </div>
</div>
          <a href="laporan_dosen.php" class="menu-btn">Buka Laporan</a>
        </div>
        <div class="menu-card">
          <h6>Laporan Absensi Mahasiswa</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Lihat rekap kehadiran mahasiswa per forum dan tanggal</p>
          <a href="laporan_mahasiswa.php" class="menu-btn">Buka Laporan</a>
        </div>
        <div class="menu-card">
          <h6>Data Absensi Detail</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Lihat detail absensi per jadwal dan mahasiswa</p>
          <a href="data_absensi.php" class="menu-btn">Lihat Data</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>