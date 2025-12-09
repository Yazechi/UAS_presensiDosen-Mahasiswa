<?php
include '../config.php';

// 1. Cek Login Dosen
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'dosen') {
}

$dosen_id = $_SESSION['user_id'] ?? 1; // Default ID 1 jika testing tanpa login

// 2. PROSES TAMBAH FORUM
if (isset($_POST['tambah_forum'])) {
    $nama_forum = mysqli_real_escape_string($conn, $_POST['nama_forum']);
    $deskripsi  = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Generate Kode Forum unik (6 karakter)
    $kode_forum = strtoupper(substr(md5(time()), 0, 6));

    // Query Insert ke tabel 'forum'
    $query = "INSERT INTO forum (nama_forum, deskripsi, dosen_id, kode_forum) 
              VALUES ('$nama_forum', '$deskripsi', '$dosen_id', '$kode_forum')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Berhasil membuat forum baru! Kode Akses: $kode_forum'); window.location.href='kelola_forum.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}

// 3. PROSES HAPUS FORUM
if (isset($_GET['hapus'])) {
    $id_forum = $_GET['hapus'];
    // Hapus hanya jika forum itu milik dosen yang sedang login
    mysqli_query($conn, "DELETE FROM forum WHERE id='$id_forum' AND dosen_id='$dosen_id'");
    echo "<script>alert('Forum berhasil dihapus!'); window.location.href='kelola_forum.php';</script>";
}

// 4. AMBIL DATA FORUM MILIK DOSEN INI
$query = "SELECT * FROM forum WHERE dosen_id = '$dosen_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Forum / Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Buat Kelas Baru</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Mata Kuliah / Forum</label>
                            <input type="text" name="nama_forum" class="form-control" placeholder="Contoh: Pemrograman Web" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi tentang kelas ini..."></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="tambah_forum" class="btn btn-primary">
                                Simpan Kelas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-3 text-center">
                <a href="dashboard.php" class="text-decoration-none">← Kembali ke Dashboard</a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Daftar Kelas Saya</h5>
                </div>
                <div class="card-body">
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas & Deskripsi</th>
                                    <th class="text-center">Kode Akses</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($result) > 0): ?>
                                    <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <strong class="text-primary"><?= $row['nama_forum']; ?></strong><br>
                                            <small class="text-muted"><?= $row['deskripsi']; ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark fs-6 font-monospace">
                                                <?= $row['kode_forum']; ?>
                                            </span>
                                            <div class="small text-muted mt-1">Bagikan ke Mhs</div>
                                        </td>
                                        <td class="text-center">
                                            <a href="kelola_forum.php?hapus=<?= $row['id']; ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Yakin ingin menghapus kelas ini? Semua jadwal & data di dalamnya akan hilang.');">
                                               <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-box-seam display-4"></i>
                                            <p class="mt-2">Anda belum membuat kelas apapun.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>