<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('dosen');

// 1. Cek Login Dosen
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'dosen') {
    // header("Location: ../index.php"); 
}

$dosen_id = $_SESSION['user_id'] ?? 1;

// 2. PROSES TAMBAH JADWAL
if (isset($_POST['buat_jadwal'])) {
    $forum_id = $_POST['forum_id'];
    $tanggal  = $_POST['tanggal'];
    $mulai    = $_POST['waktu_mulai'];
    $selesai  = $_POST['waktu_selesai'];

    
    if ($selesai <= $mulai) {
        echo "<script>alert('Jam selesai harus lebih akhir dari jam mulai!');</script>";
    } else {
        $query = "INSERT INTO jadwal_absensi (forum_id, tanggal, waktu_mulai, waktu_selesai, status, created_by) 
                  VALUES ('$forum_id', '$tanggal', '$mulai', '$selesai', 'aktif', '$dosen_id')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Jadwal Absensi Berhasil Dibuat!'); window.location.href='kelola_jadwal.php';</script>";
        } else {
            echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// 3. AMBIL DATA JADWAL (JOIN dengan tabel forums agar nama kelas muncul)
$query_jadwal = "SELECT j.*, f.judul AS nama_forum
                 FROM jadwal_absensi j
                 JOIN forums f ON j.forum_id = f.id
                 WHERE j.created_by = '$dosen_id'
                 ORDER BY j.tanggal DESC, j.waktu_mulai DESC";
$result_jadwal = mysqli_query($conn, $query_jadwal);

if (!$result_jadwal) {
    die("Query error: " . mysqli_error($conn));
}

// 4. AMBIL LIST FORUM (Untuk Pilihan di Form)
$query_forum = "SELECT * FROM forums WHERE dosen_id = '$dosen_id'";
$result_forum = mysqli_query($conn, $query_forum);

include __DIR__ . '/../includes/header.php';
?>

<div class="row">
        
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Buka Jadwal Absen</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label">Pilih Kelas / Forum</label>
                            <select name="forum_id" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php while($f = mysqli_fetch_assoc($result_forum)): ?>
                                    <option value="<?= $f['id']; ?>"><?= htmlspecialchars($f['judul']); ?></option>
                                <?php endwhile; ?>
                            </select>
                            <div class="form-text">Pastikan sudah membuat Forum dulu.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pertemuan</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="waktu_mulai" class="form-control" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="waktu_selesai" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="buat_jadwal" class="btn btn-success">
                                Simpan Jadwal
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            <div class="text-center">
                <a href="dashboard.php">Kembali ke Dashboard</a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Riwayat Jadwal Mengajar</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kelas</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($result_jadwal) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result_jadwal)): ?>
                                    <tr>
                                        <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                                        <td><strong><?= $row['nama_forum']; ?></strong></td>
                                        <td>
                                            <?= date('H:i', strtotime($row['waktu_mulai'])); ?> - 
                                            <?= date('H:i', strtotime($row['waktu_selesai'])); ?>
                                        </td>
                                        <td>
                                            <?php if($row['status'] == 'aktif'): ?>
                                                <span class="badge bg-success">Sedang Berjalan</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Selesai</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada jadwal dibuat.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>