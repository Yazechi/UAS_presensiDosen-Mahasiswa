<?php
// admin/kelola_user.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('admin');

$alert = null;

// HAPUS
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $alert = "User berhasil dihapus.";
    } else $alert = "Gagal menghapus user.";
}

// TAMBAH (simple - default password 'mhs123' / 'dosen123' sesuai role)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'] ?: 'mhs123';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $nama = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $nim_nip = trim($_POST['nim_nip']);

    $stmt = $conn->prepare("INSERT INTO users (username,password,nama_lengkap,email,role,nim_nip) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $username, $hash, $nama, $email, $role, $nim_nip);
    if ($stmt->execute()) $alert = "User berhasil ditambahkan.";
    else $alert = "Gagal menambahkan user: " . $conn->error;
}

// FETCH
$res = $conn->query("SELECT id,username,nama_lengkap,email,role,nim_nip,created_at FROM users ORDER BY created_at DESC");

include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Kelola User</h3>
  <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if($alert): ?><div class="alert alert-info"><?=htmlspecialchars($alert)?></div><?php endif; ?>

<!-- Form tambah sederhana -->
<div class="card mb-3">
  <div class="card-body">
    <h5>Tambah User</h5>
    <form method="post" class="row g-2">
      <div class="col-md-3"><input name="username" class="form-control" placeholder="username" required></div>
      <div class="col-md-3"><input name="password" class="form-control" placeholder="password (optional)"></div>
      <div class="col-md-3"><input name="nama_lengkap" class="form-control" placeholder="Nama lengkap" required></div>
      <div class="col-md-3"><input name="email" class="form-control" placeholder="Email"></div>
      <div class="col-md-3"><input name="nim_nip" class="form-control" placeholder="NIM / NIP"></div>
      <div class="col-md-3">
        <select name="role" class="form-control" required>
          <option value="mahasiswa">Mahasiswa</option>
          <option value="dosen">Dosen</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div class="col-md-3"><button name="add_user" class="btn btn-success">Tambah</button></div>
    </form>
  </div>
</div>

<!-- Table -->
<div class="card">
  <div class="card-body">
    <table class="table table-striped">
      <thead><tr><th>#</th><th>Username</th><th>Nama</th><th>Email</th><th>Role</th><th>NIM/NIP</th><th>Dibuat</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php $i=1; while($r = $res->fetch_assoc()): ?>
        <tr>
          <td><?=$i++?></td>
          <td><?=htmlspecialchars($r['username'])?></td>
          <td><?=htmlspecialchars($r['nama_lengkap'])?></td>
          <td><?=htmlspecialchars($r['email'])?></td>
          <td><?=htmlspecialchars($r['role'])?></td>
          <td><?=htmlspecialchars($r['nim_nip'])?></td>
          <td><?=htmlspecialchars($r['created_at'])?></td>
          <td>
            <a href="user_edit.php?id=<?=$r['id']?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="kelola_user.php?delete=<?=$r['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user?')">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
