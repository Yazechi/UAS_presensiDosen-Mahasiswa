<?php
include 'config.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        if (password_verify($password, $data['password'])) {
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['nama'] = $data['nama_lengkap'];

            if ($data['role'] == 'admin') header("Location: admin/dashboard.php");
            else if ($data['role'] == 'dosen') header("Location: dosen/dashboard.php");
            else if ($data['role'] == 'mahasiswa') header("Location: mahasiswa/dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Presensi</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 400px;">
        <div class="text-center mb-4">
            <img src="https://via.placeholder.com/100/667eea/ffffff?text=Login" class="rounded-circle mb-3" alt="Logo">
            <h3>Login Sistem</h3>
            <p class="text-muted">Masuk ke akun Anda</p>
        </div>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <small>Belum punya akun? <a href="register.php">Daftar disini</a></small>
        </div>
        <div class="text-center mt-2">
            <small><a href="index.php">← Kembali ke Beranda</a></small>
        </div>
    </div>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
