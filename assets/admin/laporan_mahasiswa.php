<?php
// admin/laporan_mahasiswa.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
cekLogin(); cekRole('admin');

$forum_id = $_GET['forum_id'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$where = [];
$params = []; $types = '';

if ($forum_id != '') { $where[] = "j.forum_id = ?"; $params[] = $forum_id; $types .= 'i'; }
if ($from != '') { $where[] = "j.tanggal >= ?"; $params[] = $from; $types .= 's'; }
if ($to != '') { $where[] = "j.tanggal <= ?"; $params[] = $to; $types .= 's'; }

$sql = "SELECT f.judul AS nama_forum, u.nama_lengkap AS mahasiswa, a.status, a.waktu_absen, j.tanggal
        FROM attendance a
        JOIN jadwal_absensi j ON a.jadwal_id = j.id
        JOIN users u ON a.mahasiswa_id = u.id
        JOIN forums f ON j.forum_id = f.id";

if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY j.tanggal DESC, a.waktu_absen DESC";

$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$forums = $conn->query("SELECT id, judul AS nama_forum FROM forums ORDER BY judul");

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
  <div class="container">
    <div class="page-header">
      <h3>Laporan Absensi Mahasiswa</h3>
      <a href="dashboard.php" class="btn-back">← Kembali</a>
    </div>

    <div class="form-card">
      <h5>Filter Data</h5>
      <form class="row g-2" method="get">
        <div class="col-md-3">
          <label>Forum</label>
          <select name="forum_id" class="form-control">
            <option value="">-- Semua Forum --</option>
            <?php while($f=$forums->fetch_assoc()): ?>
              <option value="<?=$f['id']?>" <?=($forum_id==$f['id'])?'selected':''?>><?=htmlspecialchars($f['nama_forum'])?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label>Dari Tanggal</label>
          <input type="date" name="from" class="form-control" value="<?=$from?>">
        </div>
        <div class="col-md-3">
          <label>Sampai Tanggal</label>
          <input type="date" name="to" class="form-control" value="<?=$to?>">
        </div>
        <div class="col-md-3" style="display: flex; align-items: flex-end;">
          <button class="btn btn-primary">🔍 Tampilkan</button>
        </div>
      </form>
    </div>

    <div class="table-card">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Forum</th>
            <th>Mahasiswa</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Waktu Absen</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; while($r = $res->fetch_assoc()): ?>
          <tr>
            <td><?=$i++?></td>
            <td><strong><?=htmlspecialchars($r['nama_forum'])?></strong></td>
            <td><?=htmlspecialchars($r['mahasiswa'])?></td>
            <td>
              <?php if($r['status'] == 'hadir'): ?>
                <span class="badge bg-success">✅ Hadir</span>
              <?php elseif($r['status'] == 'izin'): ?>
                <span class="badge bg-warning text-dark">📄 Izin</span>
              <?php elseif($r['status'] == 'sakit'): ?>
                <span class="badge bg-danger">🏥 Sakit</span>
              <?php else: ?>
                <span class="badge bg-dark">❌ Alpha</span>
              <?php endif; ?>
            </td>
            <td><?=htmlspecialchars(formatTanggalIndo($r['tanggal']))?></td>
            <td><?=htmlspecialchars($r['waktu_absen'] ?? '-')?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
