<?php
// admin/kelola_libur.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
cekLogin(); cekRole('admin');

$msg = null;

// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_libur'])) {
    $tanggal = $_POST['tanggal'];
    $keterangan = trim($_POST['keterangan']);

    $stmt = $conn->prepare("INSERT INTO hari_libur (tanggal, keterangan) VALUES (?,?)");
    $stmt->bind_param("ss", $tanggal, $keterangan);
    if ($stmt->execute()) $msg = "Hari libur berhasil ditambahkan.";
    else $msg = "Gagal: " . $conn->error;
}

// Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM hari_libur WHERE id = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    header('Location: kelola_libur.php'); exit;
}

// Fetch
$liburs = $conn->query("SELECT * FROM hari_libur ORDER BY tanggal DESC");

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
  <div class="container">
    <div class="page-header">
      <h3>Kelola Hari Libur</h3>
      <a href="dashboard.php" class="btn-back">← Kembali</a>
    </div>

    <?php if($msg): ?>
      <div class="alert alert-info"><?=htmlspecialchars($msg)?></div>
    <?php endif; ?>

    <div class="form-card">
      <h5>➕ Tambah Hari Libur Baru</h5>
      <form method="post">
        <div class="row g-2">
          <div class="col-md-4">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label>Keterangan</label>
            <input name="keterangan" class="form-control" placeholder="Contoh: Hari Kemerdekaan, Hari Raya, dll" required>
          </div>
          <div class="col-md-2" style="display: flex; align-items: flex-end;">
            <button name="create_libur" class="btn btn-success">Tambah</button>
          </div>
        </div>
      </form>
    </div>

    <div class="table-card">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Hari</th>
            <th>Keterangan</th>
            <th>Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; while($r = $liburs->fetch_assoc()): ?>
            <tr>
              <td><?=$i++?></td>
              <td><strong><?=htmlspecialchars(formatTanggalIndo($r['tanggal']))?></strong></td>
              <td><?=htmlspecialchars(getNamaHari($r['tanggal']))?></td>
              <td><?=htmlspecialchars($r['keterangan'])?></td>
              <td><?=htmlspecialchars(date('d M Y', strtotime($r['created_at'])))?></td>
              <td>
                <a class="btn btn-danger btn-sm" href="kelola_libur.php?delete=<?=$r['id']?>" onclick="return confirm('Hapus hari libur ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>