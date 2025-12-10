<<<<<<< HEAD
<?php
// admin/kelola_jadwal.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
cekLogin(); cekRole('admin');

$msg = null;

// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_jadwal'])) {
  $forum_id = intval($_POST['forum_id']);
  $tanggal = $_POST['tanggal'];
  $w_mulai = $_POST['w_mulai'];
  $w_selesai = $_POST['w_selesai'];
  $topik = trim($_POST['topik'] ?? '');
  $created_by = $_SESSION['user_id'];

  $stmt = $conn->prepare("INSERT INTO jadwal_absensi (forum_id,tanggal,waktu_mulai,waktu_selesai,topik,created_by) VALUES (?,?,?,?,?,?)");
  $stmt->bind_param("issssi", $forum_id, $tanggal, $w_mulai, $w_selesai, $topik, $created_by);
    if ($stmt->execute()) $msg = "Jadwal berhasil dibuat.";
    else $msg = "Gagal: " . $conn->error;
}

// Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM jadwal_absensi WHERE id = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    header('Location: kelola_jadwal.php'); exit;
}

// Fetch
$forums = $conn->query("SELECT id, judul AS nama_forum FROM forums ORDER BY judul");
$jads = $conn->query("SELECT j.*, f.judul AS nama_forum FROM jadwal_absensi j LEFT JOIN forums f ON j.forum_id = f.id ORDER BY j.tanggal DESC, j.waktu_mulai");

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
  <div class="container">
    <div class="page-header">
      <h3>Kelola Jadwal Absensi</h3>
      <a href="dashboard.php" class="btn-back">← Kembali</a>
    </div>

    <?php if($msg): ?><div class="alert alert-info"><?=htmlspecialchars($msg)?></div><?php endif; ?>

    <div class="form-card">
      <h5>➕ Tambah Jadwal Absensi Baru</h5>
      <form method="post">
        <div class="row g-2">
          <div class="col-md-3">
            <select name="forum_id" class="form-control" required>
              <option value="">-- Pilih Forum --</option>
              <?php 
              $forums = $conn->query("SELECT id, judul AS nama_forum FROM forums ORDER BY judul");
              while($f = $forums->fetch_assoc()): ?>
                <option value="<?=$f['id']?>"><?=htmlspecialchars($f['nama_forum'])?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-2">
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="col-md-2">
            <input type="time" name="w_mulai" class="form-control" required>
          </div>
          <div class="col-md-2">
            <input type="time" name="w_selesai" class="form-control" required>
          </div>
          <div class="col-md-2">
            <input type="text" name="topik" class="form-control" placeholder="Topik (opsional)">
          </div>
          <div class="col-md-1">
            <button name="create_jadwal" class="btn btn-primary">Tambah</button>
          </div>
        </div>
      </form>
    </div>

    <div class="table-card">
      <table class="table">
        <thead><tr>
          <th>#</th>
          <th>Forum</th>
          <th>Tanggal</th>
          <th>Waktu</th>
          <th>Topik</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr></thead>
        <tbody>
          <?php $i=1; while($r = $jads->fetch_assoc()): ?>
            <tr>
              <td><?=$i++?></td>
              <td><strong><?=htmlspecialchars($r['nama_forum'])?></strong></td>
              <td><?=htmlspecialchars(formatTanggalIndo($r['tanggal']))?></td>
              <td><?=htmlspecialchars(formatWaktu($r['waktu_mulai']))?> - <?=htmlspecialchars(formatWaktu($r['waktu_selesai']))?></td>
              <td><?=htmlspecialchars($r['topik'] ?? '-')?></td>
              <td>
                <?php if($r['status'] == 'aktif'): ?>
                  <span class="badge bg-success">Aktif</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Selesai</span>
                <?php endif; ?>
              </td>
              <td>
                <a class="btn btn-danger btn-sm" href="kelola_jadwal.php?delete=<?=$r['id']?>" onclick="return confirm('Hapus jadwal?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
=======
<?php
// admin/kelola_jadwal.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
cekLogin(); cekRole('admin');

$msg = null;

// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_jadwal'])) {
  $forum_id = intval($_POST['forum_id']);
  $tanggal = $_POST['tanggal'];
  $w_mulai = $_POST['w_mulai'];
  $w_selesai = $_POST['w_selesai'];
  $topik = trim($_POST['topik'] ?? '');
  $created_by = $_SESSION['user_id'];

  $stmt = $conn->prepare("INSERT INTO jadwal_absensi (forum_id,tanggal,waktu_mulai,waktu_selesai,topik,created_by) VALUES (?,?,?,?,?,?)");
  $stmt->bind_param("issssi", $forum_id, $tanggal, $w_mulai, $w_selesai, $topik, $created_by);
    if ($stmt->execute()) $msg = "Jadwal berhasil dibuat.";
    else $msg = "Gagal: " . $conn->error;
}

// Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM jadwal_absensi WHERE id = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    header('Location: kelola_jadwal.php'); exit;
}

// Fetch
$forums = $conn->query("SELECT id, judul AS nama_forum FROM forums ORDER BY judul");
$jads = $conn->query("SELECT j.*, f.judul AS nama_forum FROM jadwal_absensi j LEFT JOIN forums f ON j.forum_id = f.id ORDER BY j.tanggal DESC, j.waktu_mulai");

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
  <div class="container">
    <div class="page-header">
      <h3>Kelola Jadwal Absensi</h3>
      <a href="dashboard.php" class="btn-back">← Kembali</a>
    </div>

    <?php if($msg): ?><div class="alert alert-info"><?=htmlspecialchars($msg)?></div><?php endif; ?>

    <div class="form-card">
      <h5>➕ Tambah Jadwal Absensi Baru</h5>
      <form method="post">
        <div class="row g-2">
          <div class="col-md-3">
            <select name="forum_id" class="form-control" required>
              <option value="">-- Pilih Forum --</option>
              <?php 
              $forums = $conn->query("SELECT id, judul AS nama_forum FROM forums ORDER BY judul");
              while($f = $forums->fetch_assoc()): ?>
                <option value="<?=$f['id']?>"><?=htmlspecialchars($f['nama_forum'])?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-2">
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="col-md-2">
            <input type="time" name="w_mulai" class="form-control" required>
          </div>
          <div class="col-md-2">
            <input type="time" name="w_selesai" class="form-control" required>
          </div>
          <div class="col-md-2">
            <input type="text" name="topik" class="form-control" placeholder="Topik (opsional)">
          </div>
          <div class="col-md-1">
            <button name="create_jadwal" class="btn btn-primary">Tambah</button>
          </div>
        </div>
      </form>
    </div>

    <div class="table-card">
      <table class="table">
        <thead><tr>
          <th>#</th>
          <th>Forum</th>
          <th>Tanggal</th>
          <th>Waktu</th>
          <th>Topik</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr></thead>
        <tbody>
          <?php $i=1; while($r = $jads->fetch_assoc()): ?>
            <tr>
              <td><?=$i++?></td>
              <td><strong><?=htmlspecialchars($r['nama_forum'])?></strong></td>
              <td><?=htmlspecialchars(formatTanggalIndo($r['tanggal']))?></td>
              <td><?=htmlspecialchars(formatWaktu($r['waktu_mulai']))?> - <?=htmlspecialchars(formatWaktu($r['waktu_selesai']))?></td>
              <td><?=htmlspecialchars($r['topik'] ?? '-')?></td>
              <td>
                <?php if($r['status'] == 'aktif'): ?>
                  <span class="badge bg-success">Aktif</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Selesai</span>
                <?php endif; ?>
              </td>
              <td>
                <a class="btn btn-danger btn-sm" href="kelola_jadwal.php?delete=<?=$r['id']?>" onclick="return confirm('Hapus jadwal?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
>>>>>>> 45dcb6cd97da2ba7dc48c44310df4950700fda78
