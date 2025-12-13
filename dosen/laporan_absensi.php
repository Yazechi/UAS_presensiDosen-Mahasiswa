<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('dosen');

 
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'dosen') {
}

$dosen_id = $_SESSION['user_id'] ?? 0; 

$query = "SELECT 
            absensi.id,
            absensi.waktu_absen,
            absensi.status,
            absensi.keterangan,
            users.nama_lengkap,
            users.nim_nip,
            jadwal_absensi.tanggal,
            jadwal_absensi.waktu_mulai
          FROM absensi
          JOIN users ON absensi.mahasiswa_id = users.id
          JOIN jadwal_absensi ON absensi.jadwal_id = jadwal_absensi.id
          WHERE jadwal_absensi.created_by = '$dosen_id' 
          ORDER BY jadwal_absensi.tanggal DESC, users.nama_lengkap ASC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../includes/header.php';
?>

<div class="card shadow">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-file-earmark-spreadsheet"></i> Laporan Absensi Mahasiswa</h5>
            <div>
                <a href="dashboard.php" class="btn btn-light btn-sm">Dashboard</a>
            </div>
        </div>
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Tanggal & Jam Kuliah</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Waktu Absen</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                
                                <td>
                                    <?php echo date('d M Y', strtotime($row['tanggal'])); ?> <br>
                                    <small class="text-muted"><?php echo $row['waktu_mulai']; ?> WIB</small>
                                </td>

                                <td><?php echo $row['nim_nip']; ?></td>
                                <td class="fw-bold"><?php echo $row['nama_lengkap']; ?></td>
                                
                                <td class="text-center">
                                    <?php 
                                    if($row['waktu_absen']) {
                                        echo date('H:i:s', strtotime($row['waktu_absen'])); 
                                    } else {
                                        echo "-";
                                    }
                                    ?>
                                </td>

                                <td class="text-center">
                                    <?php 
                                        $status = $row['status'];
                                        $badge = "bg-secondary";
                                        if($status == 'hadir') $badge = "bg-success";
                                        if($status == 'izin') $badge = "bg-warning text-dark";
                                        if($status == 'sakit') $badge = "bg-info text-dark";
                                        if($status == 'alpha' || $status == 'absen') $badge = "bg-danger";
                                    ?>
                                    <span class="badge <?php echo $badge; ?> text-uppercase">
                                        <?php echo $status; ?>
                                    </span>
                                </td>

                                <td><?php echo $row['keterangan']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-info-circle display-4"></i>
                                    <p class="mt-2">Belum ada data absensi mahasiswa.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3 text-end">
                 <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="bi bi-printer"></i> Cetak Laporan
                 </button>
            </div>

        </div>
    </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>