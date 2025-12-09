<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('mahasiswa');

$mahasiswa_id = $_SESSION['user_id'];

// Ambil forum yang diikuti mahasiswa
$sql = "
    SELECT 
        f.id,
        f.kode_forum,
        f.judul,
        u.nama_lengkap AS nama_dosen
    FROM forums f
    INNER JOIN forum_mahasiswa fm ON f.id = fm.forum_id
    LEFT JOIN users u ON f.dosen_id = u.id
    WHERE fm.mahasiswa_id = ?
    ORDER BY f.judul
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $mahasiswa_id);
$stmt->execute();
$forums = $stmt->get_result();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
    <div class="container">

        <div class="page-header">
            <h3>📚 Forum Pembelajaran</h3>
            <a href="join_forum.php" class="btn btn-primary">+ Join Forum</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Berhasil bergabung dengan forum!</div>
        <?php endif; ?>

        <?php if ($forums->num_rows == 0): ?>
        <div class="alert alert-info mt-4">
            Kamu belum bergabung dengan forum mana pun.  
            Silakan klik <strong>+ Join Forum</strong> untuk masuk ke kelas.
        </div>

        <?php else: ?>

        <div class="table-card mt-3">
            <h5 class="mb-3">📖 Daftar Forum yang Kamu Ikuti</h5>

            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Forum</th>
                        <th>Judul Forum</th>
                        <th>Dosen Pengampu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php $i = 1; while ($f = $forums->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><strong><?= htmlspecialchars($f['kode_forum']) ?></strong></td>
                        <td><?= htmlspecialchars($f['judul']) ?></td>
                        <td><?= htmlspecialchars($f['nama_dosen']) ?></td>
                        <td>
                            <a href="detail_forum.php?id=<?= $f['id'] ?>" class="btn btn-sm btn-primary">
                                Masuk Forum →
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>

            </table>

        </div>

        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
