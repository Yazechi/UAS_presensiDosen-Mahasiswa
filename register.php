<?php
include 'config.php';

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = $_POST['role'];

    $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        $error = "Username sudah terpakai, silakan pilih yang lain.";
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $insert = mysqli_query($conn, "INSERT INTO users (nama_lengkap, username, password, role) VALUES ('$nama', '$username', '$password_hashed', '$role')");

        if ($insert) {
            echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='index.php';</script>";
        } else {
            $error = "Terjadi kesalahan saat mendaftar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Akun - Sistem Presensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 450px;">
        <div class="text-center mb-4">
            <img src="https://via.placeholder.com/80" class="rounded-circle mb-3" alt="Logo">
            <h3>Daftar Akun Baru</h3>
        </div>

        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" required>
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Daftar Sebagai</label>
                <select name="role" class="form-select" required>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="dosen">Dosen</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" name="register" class="btn btn-success w-100">Daftar Sekarang</button>
        </form>

        <div class="text-center mt-3">
            <small>Sudah punya akun? <a href="index.php">Login disini</a></small>
        </div>
    </div>
</body>

</html>