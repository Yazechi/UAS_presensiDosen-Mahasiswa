<?php
// admin/laporan_dosen.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
cekLogin(); cekRole('admin');

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$where = [];
$params = []; $types = '';

if ($from != '') { $where[] = "tanggal >= ?"; $params[] = $from; $types .= 's'; }
if ($to != '') { $where[] = "tanggal <= ?"; $params[] = $to; $types .= 's'; }

$sql = "SELECT ad.*, u.nama_lengkap FROM absensi_dosen ad JOIN users u ON ad.dosen_id = u.id";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY tanggal DESC";

$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
  <div class="container">
    <div class="page-header">
      <h3>Laporan Absensi Dosen</h3>
      <a href="dashboard.php" class="btn-back">← Kembali</a>
    </div>

    <div class="form-card">
      <h5>🔍 Filter Data</h5>
      <form class="row g-2" method="get">
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
            <th>Dosen</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Waktu Absen</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; while($r = $res->fetch_assoc()): ?>
          <tr>
            <td><?=$i++?></td>
            <td><strong><?=htmlspecialchars($r['nama_lengkap'])?></strong></td>
            <td><?=htmlspecialchars(formatTanggalIndo($r['tanggal']))?></td>
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
            <td><?=htmlspecialchars($r['waktu_absen'] ?? '-')?></td>
            <td><?=htmlspecialchars($r['keterangan'] ?? '-')?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
