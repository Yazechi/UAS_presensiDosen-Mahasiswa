<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('mahasiswa');

$user_id = $_SESSION['user_id'];
$nama = $_SESSION['nama'];

$total_kehadiran = $conn->query("SELECT COUNT(*) as total FROM attendance WHERE mahasiswa_id='$user_id' AND status='hadir'")->fetch_assoc()['total'];
$forum_diikuti = $conn->query("SELECT COUNT(*) as total FROM forum_mahasiswa WHERE mahasiswa_id='$user_id'")->fetch_assoc()['total'];
$jadwal_aktif = $conn->query("SELECT COUNT(*) as total FROM jadwal_absensi WHERE status='aktif'")->fetch_assoc()['total'];

include __DIR__ . '/../includes/header.php';
?>

<main class="app-main">
  <div class="container-fluid">
    <div class="dashboard-header">
      <h1>Dashboard Mahasiswa</h1>
      <p>Selamat datang, <strong><?= htmlspecialchars($nama) ?></strong></p>
    </div>

    <div class="stats-row">
      <div class="stat-card">
        <h6>Total Kehadiran</h6>
        <p class="stat-value"><?= $total_kehadiran ?></p>
      </div>
      <div class="stat-card">
        <h6>Forum Diikuti</h6>
        <p class="stat-value"><?= $forum_diikuti ?></p>
      </div>
      <div class="stat-card">
        <h6>Jadwal Aktif</h6>
        <p class="stat-value"><?= $jadwal_aktif ?></p>
      </div>
    </div>

    <div class="menu-section">
      <h5>Menu Mahasiswa</h5>
      <div class="menu-grid">
        <div class="menu-card">
          <h6>Forum Saya</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Lihat forum yang sudah Anda ikuti</p>
          <a href="forum.php" class="menu-btn">Lihat Forum</a>
        </div>
        <div class="menu-card">
          <h6>Absensi</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Lakukan absensi pada jadwal yang aktif</p>
          <a href="absensi.php" class="menu-btn">Absen Sekarang</a>
        </div>
        <div class="menu-card">
          <h6>Histori Absensi</h6>
          <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">Lihat riwayat kehadiran Anda</p>
          <a href="histori_absensi.php" class="menu-btn">Lihat Histori</a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
