<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin();
cekRole('dosen');

$dosen_id = $_SESSION['user_id'];
$hari_ini = date('Y-m-d');

$info_libur = cekHariLibur($conn, $hari_ini);
$data_absen = getStatusAbsensiDosenHariIni($conn, $dosen_id);

// Default status
$bisa_absen = true;
$pesan_status = "Silahkan melakukan absensi hari ini.";
$warna_alert = "alert-primary";

if ($info_libur) {
    // Jika hari libur
    $bisa_absen = false;
    $pesan_status = "Hari ini " . $info_libur . ". Tidak perlu absen.";
    $warna_alert = "alert-warning";
} elseif ($data_absen) {
    // Jika sudah absen
    $bisa_absen = false;
    $jam_absen = date('H:i', strtotime($data_absen['waktu_absen']));
    $pesan_status = "Anda sudah melakukan absensi hari ini pada pukul " . $jam_absen;
    $warna_alert = "alert-success";
}

// --- PROSES ABSEN ---
if (isset($_POST['absen_masuk']) && $bisa_absen) {
    $waktu_sekarang = date('Y-m-d H:i:s');
    
    $query = "INSERT INTO absensi_dosen (dosen_id, tanggal, waktu_absen, status, keterangan) 
              VALUES ('$dosen_id', '$hari_ini', '$waktu_sekarang', 'hadir', 'Hadir Tepat Waktu')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Terima kasih, Absensi Berhasil!'); window.location.href='absensi_harian.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}

include __DIR__ . '/../includes/header.php'; 
?>

<div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Absensi Harian Dosen</h4>
                </div>
                <div class="card-body text-center py-5">
                    
                    <h5 class="text-muted"><?php echo date('l, d F Y'); ?></h5>
                    <h1 class="display-3 fw-bold mb-4" id="jam-digital"></h1>

                    <div class="alert <?php echo $warna_alert; ?>">
                        <?php echo $pesan_status; ?>
                    </div>

                    <?php if ($bisa_absen): ?>
                        <form method="POST">
                            <button type="submit" name="absen_masuk" class="btn btn-success btn-lg px-5 rounded-pill shadow">
                                <i class="bi bi-fingerprint"></i> KLIK UNTUK HADIR
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg px-5 rounded-pill" disabled>
                            Absensi Ditutup
                        </button>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

<script>
    // Script jam digital sederhana
    setInterval(() => {
        const now = new Date();
        document.getElementById('jam-digital').innerText = now.toLocaleTimeString('id-ID', {hour12: false});
    }, 1000);
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>