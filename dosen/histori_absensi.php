<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('dosen');

$dosen_id = $_SESSION['user_id'];
$query = "SELECT * FROM absensi_dosen WHERE dosen_id = '$dosen_id' ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../includes/header.php';
?>

<main class="app-main">
<div class="container-fluid">
    <h3>Riwayat Kehadiran Saya</h3>
    <div class="card shadow mt-3">
        <div class="card-body">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Waktu Absen</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= date('d F Y', strtotime($row['tanggal'])); ?></td>
                        <td><?= date('H:i:s', strtotime($row['waktu_absen'])); ?></td>
                        <td>
                            <span class="badge bg-success"><?= strtoupper($row['status']); ?></span>
                        </td>
                        <td><?= $row['keterangan']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>