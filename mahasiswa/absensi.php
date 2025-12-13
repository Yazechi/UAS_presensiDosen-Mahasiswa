<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
cekLogin(); cekRole('mahasiswa');

$mahasiswa_id = $_SESSION['user_id'];

// Ambil semua jadwal aktif untuk forum yang sudah di-join mahasiswa
$sql = "SELECT j.id, j.tanggal, j.waktu_mulai, j.waktu_selesai, j.topik, f.judul AS nama_forum
        FROM jadwal_absensi j
        JOIN forums f ON j.forum_id = f.id
        JOIN forum_mahasiswa fm ON fm.forum_id = j.forum_id
        WHERE fm.mahasiswa_id = ? AND j.status = 'aktif'
        ORDER BY j.tanggal, j.waktu_mulai";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $mahasiswa_id);
$stmt->execute();
$result_jadwals = $stmt->get_result();

// Fetch all jadwals into array so we can check which ones already attended
$jadwals = $result_jadwals->fetch_all(MYSQLI_ASSOC);

// Build list of jadwal IDs to check attendance
$jadwal_ids = array_column($jadwals, 'id');
$attended_ids = [];
if (!empty($jadwal_ids)) {
    $ids_str = implode(',', array_map('intval', $jadwal_ids));
    $sql2 = "SELECT jadwal_id FROM attendance WHERE mahasiswa_id = ? AND jadwal_id IN ($ids_str)";
    $chk = $conn->prepare($sql2);
    $chk->bind_param('i', $mahasiswa_id);
    $chk->execute();
    $resChk = $chk->get_result();
    while ($r = $resChk->fetch_assoc()) {
        $attended_ids[] = (int)$r['jadwal_id'];
    }
}

// Jika mahasiswa menekan tombol absen untuk jadwal tertentu
    if (isset($_POST['absen'])) {
    $jadwal_id = intval($_POST['jadwal_id'] ?? 0);
    $status = $_POST['status'] ?? 'hadir';
    $keterangan = $_POST['keterangan'] ?? '';

    if (!$jadwal_id) {
        $error = 'invalid';
    } else {
            // Cek apakah mahasiswa sudah absen untuk jadwal ini (server-side guard)
            $chk = $conn->prepare("SELECT id FROM attendance WHERE jadwal_id = ? AND mahasiswa_id = ? LIMIT 1");
            $chk->bind_param('ii', $jadwal_id, $mahasiswa_id);
            $chk->execute();
            $resChk = $chk->get_result();
            if ($resChk && $resChk->num_rows > 0) {
                $error = 'already';
            } else {
                $ins = $conn->prepare("INSERT INTO attendance (jadwal_id, mahasiswa_id, status, waktu_absen, keterangan) VALUES (?, ?, ?, NOW(), ?)");
                $ins->bind_param('iiss', $jadwal_id, $mahasiswa_id, $status, $keterangan);
                if ($ins->execute()) {
                    header('Location: histori_absensi.php?success=absen');
                    exit;
                } else {
                    $error = 'insert';
                }
            }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="page-wrapper">
    <div class="container">

        <h3>📌 Absensi Kehadiran</h3>

        <?php if ($result_jadwals->num_rows == 0): ?>
            <div class="alert alert-warning mt-3">
                Tidak ada jadwal absensi aktif untuk forum yang Anda ikuti saat ini.
            </div>
        <?php else: ?>

        <?php if (isset($error) && $error === 'invalid'): ?>
            <div class="alert alert-danger">Jadwal tidak valid.</div>
        <?php elseif (isset($error) && $error === 'already'): ?>
            <div class="alert alert-info">Anda sudah melakukan absensi untuk jadwal ini.</div>
        <?php elseif (isset($error) && $error === 'insert'): ?>
            <div class="alert alert-danger">Terjadi kesalahan saat menyimpan. Coba lagi.</div>
        <?php endif; ?>

        <div class="form-card mt-3">
            <h5>Pilih Jadwal Aktif untuk Diisi</h5>

            <form method="post">
                    <label>Pilih Jadwal:</label>
                    <select name="jadwal_id" class="form-control" required>
                        <option value="">-- Pilih Jadwal --</option>
                        <?php
                        $available_count = 0;
                        foreach ($jadwals as $row):
                            if (in_array((int)$row['id'], $attended_ids)) continue; // skip already attended
                            $available_count++;
                        ?>
                            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama_forum']) ?> — <?= formatTanggalIndo($row['tanggal']) ?> <?= formatWaktu($row['waktu_mulai']) ?>-<?= formatWaktu($row['waktu_selesai']) ?> <?= $row['topik'] ? '- ' . htmlspecialchars($row['topik']) : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($available_count === 0): ?>
                        <div class="alert alert-info mt-3">Anda sudah melakukan absensi untuk seluruh jadwal aktif yang tersedia.</div>
                    <?php endif; ?>

                <label class="mt-3">Status Absensi:</label>
                <select name="status" class="form-control" required>
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                </select>

                <label class="mt-2">Keterangan (Opsional):</label>
                <textarea name="keterangan" class="form-control" placeholder="Masukkan keterangan jika diperlukan"></textarea>

                <button type="submit" name="absen" class="btn btn-primary mt-3">✔ Absen Sekarang</button>
            </form>
        </div>

        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
