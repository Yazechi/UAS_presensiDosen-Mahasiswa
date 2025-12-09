<?php
// admin/kelola_forum.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin(); cekRole('admin');

$msg = null;

// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_forum'])) {
    $nama = trim($_POST['nama_forum']);
    $des = trim($_POST['deskripsi']);
    $dosen_id = !empty($_POST['dosen_id']) ? intval($_POST['dosen_id']) : null;
    $kode = generateKodeForum();

    $stmt = $conn->prepare("INSERT INTO forums (judul, deskripsi, dosen_id, kode_forum) VALUES (?,?,?,?)");
    $stmt->bind_param("ssis", $nama, $des, $dosen_id, $kode);
    if ($stmt->execute()) $msg = "✅ Forum berhasil dibuat.";
    else $msg = "❌ Gagal: " . $conn->error;
}

// Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM forums WHERE id = ?");
    $stmt->bind_param("i",$id);
    if ($stmt->execute()) header('Location: kelola_forum.php');
    else $msg = "❌ Gagal hapus.";
}

// Edit (process)
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_forum'])) {
        $nama = trim($_POST['nama_forum']);
        $des = trim($_POST['deskripsi']);
        $dosen_id = !empty($_POST['dosen_id']) ? intval($_POST['dosen_id']) : null;
        $stmt = $conn->prepare("UPDATE forums SET judul=?, deskripsi=?, dosen_id=? WHERE id=?");
        $stmt->bind_param("ssii", $nama, $des, $dosen_id, $edit_id);
        if ($stmt->execute()) header('Location: kelola_forum.php');
        else $msg = "❌ Gagal update.";
    } else {
        $stmt = $conn->prepare("SELECT id, judul AS nama_forum, deskripsi, dosen_id, kode_forum, created_at FROM forums WHERE id = ?");
        $stmt->bind_param("i",$edit_id); $stmt->execute();
        $edit_forum = $stmt->get_result()->fetch_assoc();
    }
}

// Fetch lists
$forums = $conn->query("SELECT f.*, f.judul AS nama_forum, u.nama_lengkap as dosen_nama FROM forums f LEFT JOIN users u ON f.dosen_id = u.id ORDER BY f.created_at DESC");
$dosen_list = $conn->query("SELECT id, nama_lengkap FROM users WHERE role = 'dosen' ORDER BY nama_lengkap");

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
  <div class="container">
    <div class="page-header">
      <h3>Kelola Forum</h3>
      <a href="dashboard.php" class="btn-back">← Kembali</a>
    </div>

    <?php if($msg): ?><div class="alert alert-info"><?=htmlspecialchars($msg)?></div><?php endif; ?>

    <?php if(isset($edit_forum)): ?>
      <div class="form-card">
        <h5>✏️ Edit Forum</h5>
        <form method="post">
          <div class="form-group">
            <label>Nama Forum</label>
            <input name="nama_forum" class="form-control" value="<?=htmlspecialchars($edit_forum['nama_forum'])?>" required>
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"><?=htmlspecialchars($edit_forum['deskripsi'])?></textarea>
          </div>
          <div class="form-group">
            <label>Pilih Dosen</label>
            <select name="dosen_id" class="form-control">
              <option value="">-- Pilih Dosen --</option>
              <?php $dosen_list = $conn->query("SELECT id, nama_lengkap FROM users WHERE role='dosen' ORDER BY nama_lengkap");
              while($d = $dosen_list->fetch_assoc()): ?>
                <option value="<?=$d['id']?>" <?=($d['id']==$edit_forum['dosen_id'])?'selected':''?>><?=htmlspecialchars($d['nama_lengkap'])?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <button name="update_forum" class="btn btn-primary">💾 Simpan</button>
          <a href="kelola_forum.php" class="btn btn-secondary">Batal</a>
        </form>
      </div>
    <?php else: ?>
      <div class="form-card">
        <h5>➕ Tambah Forum Baru</h5>
        <form method="post">
          <div class="form-group">
            <label>Nama Forum</label>
            <input name="nama_forum" class="form-control" placeholder="Contoh: Pemrograman Web" required>
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" placeholder="Deskripsi forum" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label>Pilih Dosen</label>
            <select name="dosen_id" class="form-control">
              <option value="">-- Pilih Dosen --</option>
              <?php 
              $dosen_list = $conn->query("SELECT id, nama_lengkap FROM users WHERE role='dosen' ORDER BY nama_lengkap");
              while($d = $dosen_list->fetch_assoc()): ?>
                <option value="<?=$d['id']?>"><?=htmlspecialchars($d['nama_lengkap'])?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <button name="create_forum" class="btn btn-success">✅ Buat Forum</button>
        </form>
      </div>
    <?php endif; ?>

    <div class="table-card">
      <table class="table">
        <thead><tr>
          <th>#</th>
          <th>Nama Forum</th>
          <th>Dosen</th>
          <th>Kode</th>
          <th>Deskripsi</th>
          <th>Dibuat</th>
          <th>Aksi</th>
        </tr></thead>
        <tbody>
          <?php $i=1; while($f = $forums->fetch_assoc()): ?>
          <tr>
            <td><?=$i++?></td>
            <td><strong><?=htmlspecialchars($f['nama_forum'])?></strong></td>
            <td><?=htmlspecialchars($f['dosen_nama'] ?? '-')?></td>
            <td><code><?=htmlspecialchars($f['kode_forum'])?></code></td>
            <td><?=htmlspecialchars(substr($f['deskripsi'] ?? '', 0, 50))?><?=strlen($f['deskripsi'] ?? '') > 50 ? '...' : ''?></td>
            <td><?=htmlspecialchars(formatTanggalIndo($f['created_at']))?></td>
            <td>
              <a class="btn btn-warning btn-sm" href="kelola_forum.php?edit=<?=$f['id']?>">Edit</a>
              <a class="btn btn-danger btn-sm" href="kelola_forum.php?delete=<?=$f['id']?>" onclick="return confirm('Hapus forum ini?')">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
