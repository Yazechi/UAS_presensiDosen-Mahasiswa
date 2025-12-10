<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
	<div class="container-fluid">
		<a class="navbar-brand" href="#">Sistem Presensi - <?= ucfirst($_SESSION['role'] ?? 'User') ?></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav me-auto">
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
				</li>
				
				<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_user.php') ? 'active' : '' ?>" href="kelola_user.php">Kelola User</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_forum.php') ? 'active' : '' ?>" href="kelola_forum.php">Kelola Forum</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_jadwal.php') ? 'active' : '' ?>" href="kelola_jadwal.php">Kelola Jadwal</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_libur.php') ? 'active' : '' ?>" href="kelola_libur.php">Kelola Libur</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
						Laporan
					</a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="data_absensi.php">Data Absensi</a></li>
						<li><a class="dropdown-item" href="laporan_dosen.php">Laporan Dosen</a></li>
						<li><a class="dropdown-item" href="laporan_mahasiswa.php">Laporan Mahasiswa</a></li>
					</ul>
				</li>
				<?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'dosen'): ?>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'absensi_harian.php') ? 'active' : '' ?>" href="absensi_harian.php">Absensi Harian</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'histori_absensi.php') ? 'active' : '' ?>" href="histori_absensi.php">Histori Absensi</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_forum.php') ? 'active' : '' ?>" href="kelola_forum.php">Kelola Forum</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_jadwal.php') ? 'active' : '' ?>" href="kelola_jadwal.php">Kelola Jadwal</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kelola_mahasiswa.php') ? 'active' : '' ?>" href="kelola_mahasiswa.php">Kelola Mahasiswa</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'laporan_absensi.php') ? 'active' : '' ?>" href="laporan_absensi.php">Laporan Mahasiswa</a>
				</li>
				<?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'mahasiswa'): ?>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'forum.php') ? 'active' : '' ?>" href="forum.php">Forum Saya</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'join_forum.php') ? 'active' : '' ?>" href="join_forum.php">Join Forum</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'absensi.php') ? 'active' : '' ?>" href="absensi.php">Absensi</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'histori_absensi.php') ? 'active' : '' ?>" href="histori_absensi.php">Histori Absensi</a>
				</li>
				<?php endif; ?>
			</ul>
			<ul class="navbar-nav">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
						<?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?>
					</a>
					<ul class="dropdown-menu dropdown-menu-end">
						<li><a class="dropdown-item" href="<?= ($_SERVER['DOCUMENT_ROOT'] ?? '') ?>/SistemPresensi/logout.php">Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
