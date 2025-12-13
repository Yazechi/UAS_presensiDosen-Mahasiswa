<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('mahasiswa');

$mahasiswa_id = $_SESSION['user_id'];

// Ambil daftar forum yang *BELUM* diikuti mahasiswa
$sql = "
    SELECT 
        f.id,
        f.kode_forum,
        f.judul,
        u.nama_lengkap AS nama_dosen
    FROM forums f
    LEFT JOIN forum_mahasiswa fm 
        ON f.id = fm.forum_id AND fm.mahasiswa_id = ?
    LEFT JOIN users u ON f.dosen_id = u.id
    WHERE fm.forum_id IS NULL
    ORDER BY f.judul
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $mahasiswa_id);
$stmt->execute();
$available_forums = $stmt->get_result();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
    <div class="container">

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success mt-3">Berhasil bergabung ke forum.</div>
        <?php elseif (isset($_GET['error'])): ?>
            <?php $err = $_GET['error']; ?>
            <div class="alert alert-danger mt-3">
                <?php if ($err === 'invalid'): ?>Forum tidak valid.
                <?php elseif ($err === 'notfound'): ?>Forum tidak ditemukan.
                <?php elseif ($err === 'already'): ?>Anda sudah tergabung di forum ini.
                <?php else: ?>Terjadi kesalahan. Coba lagi nanti.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h3>🔔 Join Forum Pembelajaran</h3>
            <a href="forum.php" class="btn btn-secondary">← Kembali</a>
        </div>

        <?php if ($available_forums->num_rows == 0): ?>
        <div class="alert alert-info mt-4">
            Tidak ada forum yang tersedia untuk kamu gabung.  
            Semua forum sudah kamu ikuti.
        </div>

        <?php else: ?>

        <div class="table-card mt-3">
            <h5 class="mb-3">📘 Forum Tersedia</h5>

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
                <?php $i = 1; while ($f = $available_forums->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><strong><?= htmlspecialchars($f['kode_forum']) ?></strong></td>
                        <td><?= htmlspecialchars($f['judul']) ?></td>
                        <td><?= htmlspecialchars($f['nama_dosen']) ?></td>
                        <td>
                            <form method="post" action="proses_join.php">
                                <input type="hidden" name="forum_id" value="<?= $f['id'] ?>">
                                <button type="submit" class="btn btn-success btn-sm">Join +</button>
                            </form>
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
