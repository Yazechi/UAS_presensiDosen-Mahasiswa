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
