<?php
session_start();

require_once '../config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

// Cek apakah user login & role sesuai
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$nama = $_SESSION['nama_lengkap'];

// Statistik
$total_kehadiran = getCountByField($conn, 'attendance', 'mahasiswa_id', $user_id);
$forum_diikuti   = getCountByField($conn, 'forum_mahasiswa', 'mahasiswa_id', $user_id);
$jadwal_aktif    = getCountByField($conn, 'jadwal_absensi', 'status', 'aktif');
?>

<div class="container mt-4">

    <h1>Dashboard Mahasiswa</h1>
    <p>Selamat datang, <strong><?= htmlspecialchars($nama) ?></strong></p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h6>Total Kehadiran</h6>
                <h2><?= $total_kehadiran ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h6>Forum Diikuti</h6>
                <h2><?= $forum_diikuti ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h6>Jadwal Aktif</h6>
                <h2><?= $jadwal_aktif ?></h2>
            </div>
        </div>
    </div>

</div>

<?php require '../includes/footer.php'; ?>
