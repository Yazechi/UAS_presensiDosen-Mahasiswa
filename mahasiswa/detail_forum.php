<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin(); cekRole('mahasiswa');

$mahasiswa_id = $_SESSION['user_id'];
$forum_id = intval($_GET['id'] ?? 0);
if (!$forum_id) {
    http_response_code(404);
    echo 'Forum not found';
    exit;
}

// Ambil info forum
$stmt = $conn->prepare("SELECT f.*, u.nama_lengkap AS nama_dosen FROM forums f LEFT JOIN users u ON f.dosen_id = u.id WHERE f.id = ? LIMIT 1");
$stmt->bind_param('i', $forum_id);
$stmt->execute();
$forum = $stmt->get_result()->fetch_assoc();
if (!$forum) {
    http_response_code(404);
    echo 'Forum not found';
    exit;
}

// Cek apakah mahasiswa sudah join
$chk = $conn->prepare("SELECT id FROM forum_mahasiswa WHERE forum_id = ? AND mahasiswa_id = ? LIMIT 1");
$chk->bind_param('ii', $forum_id, $mahasiswa_id);
$chk->execute();
$joined = $chk->get_result()->num_rows > 0;

// Ambil jadwal untuk forum
$stmt2 = $conn->prepare("SELECT * FROM jadwal_absensi WHERE forum_id = ? ORDER BY tanggal DESC, waktu_mulai DESC");
$stmt2->bind_param('i', $forum_id);
$stmt2->execute();
$jadwals = $stmt2->get_result();

// Ambil anggota
$stmt3 = $conn->prepare("SELECT u.id, u.nama_lengkap FROM forum_mahasiswa fm JOIN users u ON fm.mahasiswa_id = u.id WHERE fm.forum_id = ? ORDER BY u.nama_lengkap");
$stmt3->bind_param('i', $forum_id);
$stmt3->execute();
$members = $stmt3->get_result();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
  <div class="container">
    <div class="page-header">
      <h3>Forum: <?= htmlspecialchars($forum['judul']) ?></h3>
      <?php if (!$joined): ?>
        <form method="post" action="proses_join.php">
          <input type="hidden" name="forum_id" value="<?= $forum_id ?>">
          <button class="btn btn-primary">+ Join Forum</button>
        </form>
      <?php else: ?>
        <a href="../mahasiswa/forum.php" class="btn btn-secondary">← Kembali</a>
      <?php endif; ?>
    </div>

    <div class="card">
      <div class="card-body">
        <p><strong>Kode:</strong> <?= htmlspecialchars($forum['kode_forum']) ?></p>
        <p><strong>Dosen Pengampu:</strong> <?= htmlspecialchars($forum['nama_dosen']) ?></p>
        <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($forum['deskripsi'])) ?></p>
      </div>
    </div>

    <h5 class="mt-4">Jadwal</h5>
    <?php if ($jadwals->num_rows == 0): ?>
      <div class="alert alert-info">Belum ada jadwal untuk forum ini.</div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr><th>Tanggal</th><th>Waktu</th><th>Topik</th><th>Status</th></tr>
        </thead>
        <tbody>
        <?php while ($j = $jadwals->fetch_assoc()): ?>
          <tr>
            <td><?= formatTanggalIndo($j['tanggal']) ?></td>
            <td><?= formatWaktu($j['waktu_mulai']) ?> - <?= formatWaktu($j['waktu_selesai']) ?></td>
            <td><?= htmlspecialchars($j['topik']) ?></td>
            <td><?= htmlspecialchars($j['status']) ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <h5 class="mt-4">Anggota (Mahasiswa)</h5>
    <?php if ($members->num_rows == 0): ?>
      <div class="alert alert-info">Belum ada anggota.</div>
    <?php else: ?>
      <ul>
        <?php while ($m = $members->fetch_assoc()): ?>
          <li><?= htmlspecialchars($m['nama_lengkap']) ?></li>
        <?php endwhile; ?>
      </ul>
    <?php endif; ?>

  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
