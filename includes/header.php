<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sistem Presensi Dosen & Mahasiswa</title>
	<link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="layout-with-sidebar">
	<!-- Header -->
	<header class="app-header">
		<div class="header-content">
			<div class="header-left">
				<h1 class="system-title">Sistem Admin Presensi</h1>
			</div>
			<div class="header-right">
				<div class="profile-section">
					<span class="profile-name"><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></span>
					<a href="../logout.php" class="logout-btn" title="Logout">Keluar</a>
				</div>
			</div>
		</div>
	</header>

	<!-- Sidebar Navigation -->
	<aside class="app-sidebar">
		<nav class="sidebar-nav">
			<ul class="nav-menu">
				<li class="nav-item">
					<a href="dashboard.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : '' ?>">
						Dashboard
					</a>
				</li>
				<li class="nav-item">
					<a href="kelola_forum.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_forum.php') ? 'active' : '' ?>">
						Kelola Forum
					</a>
				</li>
				<li class="nav-item">
					<a href="kelola_jadwal.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_jadwal.php') ? 'active' : '' ?>">
						Kelola Jadwal
					</a>
				</li>
				<li class="nav-item">
					<a href="kelola_libur.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_libur.php') ? 'active' : '' ?>">
						Kelola Libur
					</a>
				</li>
				<li class="nav-item">
					<a href="kelola_user.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_user.php') ? 'active' : '' ?>">
						Kelola User
					</a>
				</li>
			</ul>
			<div class="sidebar-footer">
				<a href="../logout.php" class="logout-link">Logout</a>
			</div>
		</nav>
	</aside>

	<!-- Main Content Area -->
	<main class="app-main">

