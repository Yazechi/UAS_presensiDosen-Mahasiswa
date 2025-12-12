<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sistem Presensi - <?= ucfirst($_SESSION['role'] ?? 'User') ?></title>
	<link rel="stylesheet" href="/SistemPresensi/assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/SistemPresensi/assets/css/style.css">
</head>
<body>
	<header class="app-header">
		<div class="header-content">
			<div class="header-left">
				<h1 class="system-title">Sistem Presensi - <?= ucfirst($_SESSION['role'] ?? 'User') ?></h1>
			</div>
			<div class="header-right">
				<div class="profile-section">
					<span class="profile-name"><?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?></span>
					<a href="/SistemPresensi/logout.php" class="logout-btn">Keluar</a>
				</div>
			</div>
		</div>
	</header>

	<aside class="app-sidebar">
		<nav class="sidebar-nav">
			<ul class="nav-menu">
				<li class="nav-item">
					<a href="dashboard.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : '' ?>">
						📊 Dashboard
					</a>
				</li>
				
				<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
				<li class="nav-item">
					<a href="kelola_user.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_user.php') ? 'active' : '' ?>">
						👥 Kelola User
					</a>
				</li>
				<li class="nav-item">
					<a href="kelola_forum.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_forum.php') ? 'active' : '' ?>">
						💬 Kelola Forum
					</a>
				</li>
				<li class="nav-item">
					<a href="kelola_jadwal.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_jadwal.php') ? 'active' : '' ?>">
						📅 Kelola Jadwal
					</a>
				</li>
				<li class="nav-item">
					<a href="kelola_libur.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_libur.php') ? 'active' : '' ?>">
						🏖️ Kelola Libur
					</a>
				</li>
				<li class="nav-item">
					<a href="data_absensi.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'data_absensi.php') ? 'active' : '' ?>">
						📊 Data Absensi
					</a>
				</li>
				<li class="nav-item">
					<a href="laporan_dosen.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'laporan_dosen.php') ? 'active' : '' ?>">
						📋 Laporan Dosen
					</a>
				</li>
				<li class="nav-item">
					<a href="laporan_mahasiswa.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'laporan_mahasiswa.php') ? 'active' : '' ?>">
						📋 Laporan Mahasiswa
					</a>
				</li>
				<?php endif; ?>

				<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'dosen'): ?>
				<li class="nav-item">
					<a href="forum.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'forum.php') ? 'active' : '' ?>">
						💬 Forum Saya
					</a>
				</li>
				<li class="nav-item">
					<a href="absensi_harian.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'absensi_harian.php') ? 'active' : '' ?>">
						✅ Absensi Harian
					</a>
				</li>
				<li class="nav-item">
					<a href="histori_absensi.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'histori_absensi.php') ? 'active' : '' ?>">
						📊 Histori Absensi
					</a>
				</li>
				<?php endif; ?>

				<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'mahasiswa'): ?>
				<li class="nav-item">
					<a href="forum.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'forum.php') ? 'active' : '' ?>">
						💬 Forum Saya
					</a>
				</li>
				<li class="nav-item">
					<a href="join_forum.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'join_forum.php') ? 'active' : '' ?>">
						➕ Join Forum
					</a>
				</li>
				<li class="nav-item">
					<a href="absensi.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'absensi.php') ? 'active' : '' ?>">
						✅ Absensi
					</a>
				</li>
				<li class="nav-item">
					<a href="histori_absensi.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'histori_absensi.php') ? 'active' : '' ?>">
						📊 Histori Absensi
					</a>
				</li>
				<?php endif; ?>
			</ul>
		</nav>

		<div class="sidebar-footer">
			<a href="/SistemPresensi/logout.php" class="sidebar-logout">🚪 Logout</a>
		</div>
	</aside>

