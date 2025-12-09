<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('mahasiswa');

$mahasiswa_id = $_SESSION['user_id'];

// Query histori absensi sesuai struktur database (attendance)
$sql = "
    SELECT 
        a.id,
        f.judul AS nama_forum,
        f.kode_forum,
        u.nama_lengkap AS dosen,
        j.tanggal,
        j.waktu_mulai,
        j.waktu_selesai,
        a.status,
        a.waktu_absen,
        a.keterangan
    FROM attendance a
    LEFT JOIN jadwal_absensi j ON a.jadwal_id = j.id
    LEFT JOIN forums f ON j.forum_id = f.id
    LEFT JOIN users u ON f.dosen_id = u.id
    WHERE a.mahasiswa_id = ?
    ORDER BY j.tanggal DESC, j.waktu_mulai DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $mahasiswa_id);
$stmt->execute();
$history = $stmt->get_result();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
    <div class="container">

        <div class="page-header">
            <h3>📜 Histori Absensi Mahasiswa</h3>
        </div>

        <?php if ($history->num_rows == 0): ?>
            <div class="alert alert-info mt-4">
                Belum ada histori absensi.
            </div>
        <?php else: ?>

        <div class="table-card mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Forum</th>
                        <th>Dosen</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Waktu Absen</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $history->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($row['nama_forum']) ?></strong><br>
                            <small><?= htmlspecialchars($row['kode_forum']) ?></small>
                        </td>

                        <td><?= htmlspecialchars($row['dosen']) ?></td>

                        <td><?= $row['tanggal'] ?></td>

                        <td>
                            <?php
                                $badge = [
                                    'hadir' => 'success',
                                    'izin' => 'warning',
                                    'sakit' => 'info',
                                    'alpha' => 'danger'
                                ][$row['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $badge ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>

                        <td><?= $row['waktu_absen'] ?: '-' ?></td>

                        <td><?= htmlspecialchars($row['keterangan'] ?: '-') ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
