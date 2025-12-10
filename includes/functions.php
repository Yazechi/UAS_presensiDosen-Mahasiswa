<<<<<<< HEAD
<?php

function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit();
    }
}

function cekRole($role_required) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != $role_required) {
        header('Location: ../login.php');
        exit();
    }
}

function cekHariLibur($conn, $tanggal) {
    $day_of_week = date('N', strtotime($tanggal));
    if ($day_of_week == 6 || $day_of_week == 7) {
        return true;
    }
    
    $query = "SELECT * FROM hari_libur WHERE tanggal = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $tanggal);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

function cekDosenSudahAbsen($conn, $dosen_id, $tanggal) {
    $query = "SELECT * FROM absensi_dosen WHERE dosen_id = ? AND tanggal = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $dosen_id, $tanggal);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

function getStatusAbsensiDosenHariIni($conn, $dosen_id) {
    $today = date('Y-m-d');
    $day_of_week = date('N', strtotime($today));
    
    if (cekHariLibur($conn, $today)) {
        $message = 'Hari ini adalah hari libur';
        if ($day_of_week == 6) {
            $message = 'Hari ini adalah Sabtu (weekend)';
        } elseif ($day_of_week == 7) {
            $message = 'Hari ini adalah Minggu (weekend)';
        }
        
        return [
            'status' => true,
            'message' => $message,
            'is_libur' => true,
            'sudah_absen' => false
        ];
    }
    
    if (cekDosenSudahAbsen($conn, $dosen_id, $today)) {
        $query = "SELECT * FROM absensi_dosen WHERE dosen_id = ? AND tanggal = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $dosen_id, $today);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        return [
            'status' => true,
            'message' => 'Anda sudah absen hari ini',
            'is_libur' => false,
            'sudah_absen' => true,
            'data' => $data
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Anda belum absen hari ini!',
        'is_libur' => false,
        'sudah_absen' => false
    ];
}

function hitungPersentaseKehadiranDosen($conn, $dosen_id, $bulan = null) {
    if ($bulan == null) {
        $bulan = date('Y-m');
    }
    
    $start_date = $bulan . '-01';
    $end_date = date('Y-m-t', strtotime($start_date));
    
    $query = "SELECT COUNT(*) as total_hadir 
              FROM absensi_dosen 
              WHERE dosen_id = ? 
              AND tanggal BETWEEN ? AND ?
              AND status = 'hadir'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $dosen_id, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $total_hadir = $data['total_hadir'];
    
    $query_kerja = "SELECT COUNT(*) as total_kerja
                    FROM (
                        SELECT ADDDATE('$start_date', @num := @num + 1) as tanggal
                        FROM information_schema.tables t1, information_schema.tables t2,
                        (SELECT @num := -1) num
                        WHERE @num < DATEDIFF('$end_date', '$start_date')
                    ) dates
                    WHERE DAYOFWEEK(tanggal) NOT IN (1, 7)
                    AND tanggal NOT IN (SELECT tanggal FROM hari_libur WHERE tanggal BETWEEN '$start_date' AND '$end_date')";
    
    $result_kerja = $conn->query($query_kerja);
    $data_kerja = $result_kerja->fetch_assoc();
    $total_kerja = $data_kerja['total_kerja'];
    
    if ($total_kerja == 0) return 0;
    
    return round(($total_hadir / $total_kerja) * 100, 2);
}

function hitungPersentaseKehadiranMahasiswa($conn, $mahasiswa_id, $forum_id) {
    $query_total = "SELECT COUNT(*) as total 
                    FROM jadwal_absensi 
                    WHERE forum_id = ? AND status != 'draft'";
    $stmt = $conn->prepare($query_total);
    $stmt->bind_param("i", $forum_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $total_jadwal = $data['total'];
    
    if ($total_jadwal == 0) return 0;
    
    $query_hadir = "SELECT COUNT(*) as hadir 
                    FROM attendance a
                    JOIN jadwal_absensi ja ON a.jadwal_id = ja.id
                    WHERE ja.forum_id = ? 
                    AND a.mahasiswa_id = ? 
                    AND a.status = 'hadir'";
    $stmt = $conn->prepare($query_hadir);
    $stmt->bind_param("ii", $forum_id, $mahasiswa_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $total_hadir = $data['hadir'];
    
    return round(($total_hadir / $total_jadwal) * 100, 2);
}

function formatTanggalIndo($date) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

function formatWaktu($time) {
    return date('H:i', strtotime($time));
}

function getNamaHari($date) {
    $hari = array(
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    );
    
    return $hari[date('l', strtotime($date))];
}

function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function showAlert($message, $type = 'info') {
    echo '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">';
    echo $message;
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    echo '</div>';
}

function redirect($url, $message = null, $type = null) {
    if ($message != null && $type != null) {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
    }
    header('Location: ' . $url);
    exit();
}

function generateKodeForum() {
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
}

?>
=======
<?php

function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit();
    }
}

function cekRole($role_required) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != $role_required) {
        header('Location: ../login.php');
        exit();
    }
}

function cekHariLibur($conn, $tanggal) {
    $day_of_week = date('N', strtotime($tanggal));
    if ($day_of_week == 6 || $day_of_week == 7) {
        return true;
    }
    
    $query = "SELECT * FROM hari_libur WHERE tanggal = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $tanggal);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

function cekDosenSudahAbsen($conn, $dosen_id, $tanggal) {
    $query = "SELECT * FROM absensi_dosen WHERE dosen_id = ? AND tanggal = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $dosen_id, $tanggal);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

function getStatusAbsensiDosenHariIni($conn, $dosen_id) {
    $today = date('Y-m-d');
    $day_of_week = date('N', strtotime($today));
    
    if (cekHariLibur($conn, $today)) {
        $message = 'Hari ini adalah hari libur';
        if ($day_of_week == 6) {
            $message = 'Hari ini adalah Sabtu (weekend)';
        } elseif ($day_of_week == 7) {
            $message = 'Hari ini adalah Minggu (weekend)';
        }
        
        return [
            'status' => true,
            'message' => $message,
            'is_libur' => true,
            'sudah_absen' => false
        ];
    }
    
    if (cekDosenSudahAbsen($conn, $dosen_id, $today)) {
        $query = "SELECT * FROM absensi_dosen WHERE dosen_id = ? AND tanggal = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $dosen_id, $today);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        return [
            'status' => true,
            'message' => 'Anda sudah absen hari ini',
            'is_libur' => false,
            'sudah_absen' => true,
            'data' => $data
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Anda belum absen hari ini!',
        'is_libur' => false,
        'sudah_absen' => false
    ];
}

function hitungPersentaseKehadiranDosen($conn, $dosen_id, $bulan = null) {
    if ($bulan == null) {
        $bulan = date('Y-m');
    }
    
    $start_date = $bulan . '-01';
    $end_date = date('Y-m-t', strtotime($start_date));
    
    $query = "SELECT COUNT(*) as total_hadir 
              FROM absensi_dosen 
              WHERE dosen_id = ? 
              AND tanggal BETWEEN ? AND ?
              AND status = 'hadir'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $dosen_id, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $total_hadir = $data['total_hadir'];
    
    $query_kerja = "SELECT COUNT(*) as total_kerja
                    FROM (
                        SELECT ADDDATE('$start_date', @num := @num + 1) as tanggal
                        FROM information_schema.tables t1, information_schema.tables t2,
                        (SELECT @num := -1) num
                        WHERE @num < DATEDIFF('$end_date', '$start_date')
                    ) dates
                    WHERE DAYOFWEEK(tanggal) NOT IN (1, 7)
                    AND tanggal NOT IN (SELECT tanggal FROM hari_libur WHERE tanggal BETWEEN '$start_date' AND '$end_date')";
    
    $result_kerja = $conn->query($query_kerja);
    $data_kerja = $result_kerja->fetch_assoc();
    $total_kerja = $data_kerja['total_kerja'];
    
    if ($total_kerja == 0) return 0;
    
    return round(($total_hadir / $total_kerja) * 100, 2);
}

function hitungPersentaseKehadiranMahasiswa($conn, $mahasiswa_id, $forum_id) {
    $query_total = "SELECT COUNT(*) as total 
                    FROM jadwal_absensi 
                    WHERE forum_id = ? AND status != 'draft'";
    $stmt = $conn->prepare($query_total);
    $stmt->bind_param("i", $forum_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $total_jadwal = $data['total'];
    
    if ($total_jadwal == 0) return 0;
    
    $query_hadir = "SELECT COUNT(*) as hadir 
                    FROM attendance a
                    JOIN jadwal_absensi ja ON a.jadwal_id = ja.id
                    WHERE ja.forum_id = ? 
                    AND a.mahasiswa_id = ? 
                    AND a.status = 'hadir'";
    $stmt = $conn->prepare($query_hadir);
    $stmt->bind_param("ii", $forum_id, $mahasiswa_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $total_hadir = $data['hadir'];
    
    return round(($total_hadir / $total_jadwal) * 100, 2);
}

function formatTanggalIndo($date) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

function formatWaktu($time) {
    return date('H:i', strtotime($time));
}

function getNamaHari($date) {
    $hari = array(
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    );
    
    return $hari[date('l', strtotime($date))];
}

function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function showAlert($message, $type = 'info') {
    echo '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">';
    echo $message;
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    echo '</div>';
}

function redirect($url, $message = null, $type = null) {
    if ($message != null && $type != null) {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
    }
    header('Location: ' . $url);
    exit();
}

function generateKodeForum() {
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
}

?>
>>>>>>> 45dcb6cd97da2ba7dc48c44310df4950700fda78
