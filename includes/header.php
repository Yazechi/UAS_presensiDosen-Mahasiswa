<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sistem Presensi - <?= ucfirst($_SESSION['role'] ?? 'User') ?></title>
	<link rel="stylesheet" href="<?= ($_SERVER['DOCUMENT_ROOT'] ?? '') ?>/SistemPresensi/assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= ($_SERVER['DOCUMENT_ROOT'] ?? '') ?>/SistemPresensi/assets/css/style.css">
	<style>
		body {
			margin: 0;
			padding: 0;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}
		.app-header {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			padding: 15px 30px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			z-index: 1000;
		}
		.header-content {
			display: flex;
			justify-content: space-between;
			align-items: center;
			max-width: 100%;
		}
		.system-title {
			font-size: 1.5rem;
			margin: 0;
			font-weight: 600;
		}
		.profile-section {
			display: flex;
			align-items: center;
			gap: 15px;
		}
		.profile-name {
			font-weight: 500;
		}
		.logout-btn {
			background: rgba(255,255,255,0.2);
			color: white;
			padding: 8px 20px;
			border-radius: 5px;
			text-decoration: none;
			transition: all 0.3s;
		}
		.logout-btn:hover {
			background: rgba(255,255,255,0.3);
			color: white;
		}
		.app-sidebar {
			position: fixed;
			top: 70px;
			left: 0;
			width: 250px;
			height: calc(100vh - 70px);
			background: #2c3e50;
			overflow-y: auto;
			box-shadow: 2px 0 10px rgba(0,0,0,0.1);
		}
		.sidebar-nav {
			padding: 20px 0;
		}
		.nav-menu {
			list-style: none;
			padding: 0;
			margin: 0;
		}
		.nav-item {
			margin: 0;
		}
		.nav-link {
			display: block;
			padding: 15px 25px;
			color: #ecf0f1;
			text-decoration: none;
			transition: all 0.3s;
			border-left: 3px solid transparent;
		}
		.nav-link:hover {
			background: rgba(255,255,255,0.1);
			border-left-color: #3498db;
			color: white;
		}
		.nav-link.active {
			background: rgba(255,255,255,0.15);
			border-left-color: #3498db;
			color: white;
			font-weight: 600;
		}
		.sidebar-footer {
			position: absolute;
			bottom: 0;
			left: 0;
			right: 0;
			padding: 20px 25px;
			border-top: 1px solid rgba(255,255,255,0.1);
		}
		.logout-link {
			display: block;
			padding: 10px;
			background: #e74c3c;
			color: white;
			text-align: center;
			text-decoration: none;
			border-radius: 5px;
			transition: all 0.3s;
		}
		.logout-link:hover {
			background: #c0392b;
			color: white;
		}
		.app-main {
			margin-left: 250px;
			margin-top: 70px;
			padding: 30px;
			min-height: calc(100vh - 120px);
			background: #f8f9fa;
		}
		.app-footer {
			margin-left: 250px;
			padding: 15px 30px;
			background: #2c3e50;
			color: white;
			text-align: center;
		}
		.app-footer p {
			margin: 5px 0;
			font-size: 0.9rem;
		}
		@media (max-width: 768px) {
			.app-sidebar {
				width: 100%;
				height: auto;
				position: relative;
				top: 0;
			}
			.app-main, .app-footer {
				margin-left: 0;
			}
			.sidebar-footer {
				position: relative;
			}
		}
	</style>
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
					<a href="<?= ($_SERVER['DOCUMENT_ROOT'] ?? '') ?>/SistemPresensi/logout.php" class="logout-btn">Keluar</a>
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
						📋 Data Absensi
					</a>
				</li>
				<li class="nav-item">
					<a href="laporan_dosen.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'laporan_dosen.php') ? 'active' : '' ?>">
						📈 Laporan Dosen
					</a>
				</li>
				<li class="nav-item">
					<a href="laporan_mahasiswa.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'laporan_mahasiswa.php') ? 'active' : '' ?>">
						📊 Laporan Mahasiswa
					</a>
				</li>
				<?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'dosen'): ?>
				<li class="nav-item">
					<a href="absensi_harian.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'absensi_harian.php') ? 'active' : '' ?>">
						✅ Absensi Harian
					</a>
				</li>
				<li class="nav-item">
					<a href="histori_absensi.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'histori_absensi.php') ? 'active' : '' ?>">
						📜 Histori Absensi
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
					<a href="kelola_mahasiswa.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_mahasiswa.php') ? 'active' : '' ?>">
						👨‍🎓 Kelola Mahasiswa
					</a>
				</li>
				<li class="nav-item">
					<a href="laporan_absensi.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'laporan_absensi.php') ? 'active' : '' ?>">
						📊 Laporan Mahasiswa
					</a>
				</li>
				<?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'mahasiswa'): ?>
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
						📜 Histori Absensi
					</a>
				</li>
				<?php endif; ?>
			</ul>
			<div class="sidebar-footer">
				<a href="<?= ($_SERVER['DOCUMENT_ROOT'] ?? '') ?>/SistemPresensi/logout.php" class="logout-link">🚪 Logout</a>
			</div>
		</nav>
	</aside>

	<main class="app-main">

