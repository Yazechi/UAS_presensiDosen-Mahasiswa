<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
cekLogin(); cekRole('mahasiswa');

$mahasiswa_id = $_SESSION['user_id'];

// Ambil jadwal absensi aktif
$jadwal = $conn->query("
    SELECT j.*, f.judul AS nama_forum
    FROM jadwal_absensi j
    LEFT JOIN forums f ON j.forum_id = f.id
    WHERE j.status = 'aktif'
    ORDER BY j.tanggal, j.waktu_mulai
")->fetch_assoc();

// Jika mahasiswa menekan tombol absen
if (isset($_POST['absen'])) {
    $status = $_POST['status'];
    $keterangan = $_POST['keterangan'] ?? '';

    $stmt = $conn->prepare("
        INSERT INTO attendance (mahasiswa_id, jadwal_id, status, waktu_absen, keterangan)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $stmt->bind_param("iiss", $mahasiswa_id, $jadwal['id'], $status, $keterangan);
    $stmt->execute();

    header("Location: histori_absensi.php?success=absen");
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
    <div class="container">

        <h3>📌 Absensi Kehadiran</h3>

        <?php if (!$jadwal): ?>
            <div class="alert alert-warning mt-3">
                Tidak ada jadwal absensi aktif saat ini.
            </div>
        <?php else: ?>

        <div class="form-card mt-3">
            <h5><strong><?= htmlspecialchars($jadwal['nama_forum']) ?></strong></h5>

            <p>
                Tanggal: <?= formatTanggalIndo($jadwal['tanggal']) ?><br>
                Waktu: <?= formatWaktu($jadwal['waktu_mulai']) ?> -
                       <?= formatWaktu($jadwal['waktu_selesai']) ?>
            </p>

            <form method="post">
                <label>Status Absensi:</label>
                <select name="status" class="form-control" required>
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                </select>

                <label class="mt-2">Keterangan (Opsional):</label>
                <textarea name="keterangan" class="form-control" placeholder="Masukkan keterangan jika diperlukan"></textarea>

                <button type="submit" name="absen" class="btn btn-primary mt-3">
                    ✔ Absen Sekarang
                </button>
            </form>
        </div>

        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
