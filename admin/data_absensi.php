<?php
// admin/data_absensi.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
cekLogin(); cekRole('admin');

$jadwal_id = $_GET['jadwal_id'] ?? '';
$forum_id = $_GET['forum_id'] ?? '';

// Ambil daftar forum
$forums = $conn->query("SELECT id, judul AS nama_forum FROM forums ORDER BY judul");

// Query jadwal absensi
$jadwal_query = "SELECT j.*, f.judul AS nama_forum FROM jadwal_absensi j LEFT JOIN forums f ON j.forum_id = f.id WHERE 1=1";
if ($forum_id) {
		$jadwal_query .= " AND j.forum_id = " . intval($forum_id);
}
$jadwal_query .= " ORDER BY j.tanggal DESC";
$jadwals = $conn->query($jadwal_query);

// Query data absensi untuk jadwal tertentu
$absensi_data = null;
if ($jadwal_id) {
		$stmt = $conn->prepare(
				"SELECT a.*, u.nama_lengkap, j.tanggal, j.waktu_mulai, j.waktu_selesai, f.judul as nama_forum
				FROM attendance a
				LEFT JOIN users u ON a.mahasiswa_id = u.id
				LEFT JOIN jadwal_absensi j ON a.jadwal_id = j.id
				LEFT JOIN forums f ON j.forum_id = f.id
				WHERE a.jadwal_id = ?
				ORDER BY u.nama_lengkap"
		);
		$stmt->bind_param("i", $jadwal_id);
		$stmt->execute();
		$absensi_data = $stmt->get_result();
    
		// Ambil info jadwal
		$stmt2 = $conn->prepare("SELECT j.*, f.judul FROM jadwal_absensi j LEFT JOIN forums f ON j.forum_id = f.id WHERE j.id = ?");
		$stmt2->bind_param("i", $jadwal_id);
		$stmt2->execute();
		$jadwal_info = $stmt2->get_result()->fetch_assoc();
}

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
	<div class="container">
    <div class="page-header">
      <h3>Data Absensi</h3>
      <a href="dashboard.php" class="btn-back">← Kembali</a>
    </div>		<div class="form-card">
			<h5>🔍 Filter Jadwal</h5>
			<form method="get" class="row g-2">
				<div class="col-md-6">
					<label>Forum</label>
					<select name="forum_id" class="form-control" onchange="this.form.submit()">
						<option value="">-- Semua Forum --</option>
						<?php 
						$forums = $conn->query("SELECT id, judul AS nama_forum FROM forums ORDER BY judul");
						while($f=$forums->fetch_assoc()): ?>
							<option value="<?=$f['id']?>" <?=($forum_id==$f['id'])?'selected':''?>><?=htmlspecialchars($f['nama_forum'])?></option>
						<?php endwhile; ?>
					</select>
				</div>
			</form>
		</div>

		<div class="table-card">
			<h5 class="mb-3">📅 Daftar Jadwal Absensi</h5>
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Forum</th>
						<th>Tanggal</th>
						<th>Waktu</th>
						<th>Topik</th>
						<th>Status</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=1; while($j = $jadwals->fetch_assoc()): ?>
					<tr>
						<td><?=$i++?></td>
						<td><strong><?=htmlspecialchars($j['nama_forum'])?></strong></td>
						<td><?=htmlspecialchars(formatTanggalIndo($j['tanggal']))?></td>
						<td><?=htmlspecialchars(formatWaktu($j['waktu_mulai']))?> - <?=htmlspecialchars(formatWaktu($j['waktu_selesai']))?></td>
						<td><?=htmlspecialchars($j['topik'] ?? '-')?></td>
						<td>
							<?php if($j['status'] == 'aktif'): ?>
								<span class="badge bg-success">Aktif</span>
							<?php else: ?>
								<span class="badge bg-secondary">Selesai</span>
							<?php endif; ?>
						</td>
						<td>
							<a href="data_absensi.php?jadwal_id=<?=$j['id']?>&forum_id=<?=$forum_id?>" class="btn btn-primary btn-sm">Lihat</a>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>

		<!-- Data Absensi Detail -->
		<?php if($jadwal_id && $absensi_data): ?>
		<div class="table-card mt-4">
			<h5 class="mb-3">
				📊 Data Absensi: <strong><?=htmlspecialchars($jadwal_info['judul'] ?? $jadwal_info['nama_forum'] ?? '')?></strong>
				<br>
				<small class="text-muted"><?=htmlspecialchars(formatTanggalIndo($jadwal_info['tanggal']))?> 
					(<?=htmlspecialchars(getNamaHari($jadwal_info['tanggal']))?>) 
					- <?=htmlspecialchars(formatWaktu($jadwal_info['waktu_mulai']))?> s.d <?=htmlspecialchars(formatWaktu($jadwal_info['waktu_selesai']))?></small>
			</h5>
      
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Mahasiswa</th>
						<th>Status</th>
						<th>Waktu Absen</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=1; while($a = $absensi_data->fetch_assoc()): ?>
					<tr>
						<td><?=$i++?></td>
						<td><strong><?=htmlspecialchars($a['nama_lengkap'])?></strong></td>
						<td>
							<?php if($a['status'] == 'hadir'): ?>
								<span class="badge bg-success">✅ Hadir</span>
							<?php elseif($a['status'] == 'izin'): ?>
								<span class="badge bg-warning text-dark">📄 Izin</span>
							<?php elseif($a['status'] == 'sakit'): ?>
								<span class="badge bg-danger">🏥 Sakit</span>
							<?php else: ?>
								<span class="badge bg-dark">❌ Alpha</span>
							<?php endif; ?>
						</td>
						<td><?=htmlspecialchars($a['waktu_absen'] ? date('H:i', strtotime($a['waktu_absen'])) : '-')?></td>
						<td><?=htmlspecialchars($a['keterangan'] ?? '-')?></td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
