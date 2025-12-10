<<<<<<< HEAD
<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_presensi_uas";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
=======
<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sistem_presensi";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
>>>>>>> 45dcb6cd97da2ba7dc48c44310df4950700fda78
