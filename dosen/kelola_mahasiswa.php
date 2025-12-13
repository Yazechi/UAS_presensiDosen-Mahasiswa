<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('dosen');

// 1. Cek Login Dosen
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'dosen') {
    header("Location: ../index.php");
    exit;
}

// 2. AMBIL DATA MAHASISWA
$query = "SELECT * FROM users WHERE role = 'mahasiswa' ORDER BY nama_lengkap ASC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../includes/header.php';
?>

<div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Mahasiswa</h5>
            <a href="dashboard.php" class="btn btn-light btn-sm">Kembali ke Dashboard</a>
        </div>
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th>NIM</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row['nim_nip']; ?></td> 
                                <td><strong><?= $row['nama_lengkap']; ?></strong></td>
                                <td><?= $row['email']; ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info text-white">Detail</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data mahasiswa.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>