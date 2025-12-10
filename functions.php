<?php
// functions.php

function cekHariLibur($conn, $tanggal) {
    // 1. Cek Weekend (Sabtu & Minggu)
    $day_of_week = date('N', strtotime($tanggal)); // 1=Senin, 7=Minggu
    if ($day_of_week == 6 || $day_of_week == 7) {
        return "Libur Akhir Pekan (Sabtu/Minggu)";
    }

    // 2. Cek Hari Libur Nasional dari Database
    $query = "SELECT * FROM hari_libur WHERE tanggal = '$tanggal'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        return "Libur Nasional: " . $data['keterangan'];
    }

    // Jika tidak libur
    return false; 
}

function getStatusAbsensiDosenHariIni($conn, $dosen_id) {
    $hari_ini = date('Y-m-d');
    $query = "SELECT * FROM absensi_dosen WHERE dosen_id = '$dosen_id' AND tanggal = '$hari_ini'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result); // Mengembalikan data absen jika sudah ada
    }
    return false; // Belum absen
}
?>