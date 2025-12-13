<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

cekLogin(); cekRole('mahasiswa');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: join_forum.php');
    exit;
}

$mahasiswa_id = $_SESSION['user_id'];
$forum_id = isset($_POST['forum_id']) ? intval($_POST['forum_id']) : 0;

if (!$forum_id) {
    header('Location: join_forum.php?error=invalid');
    exit;
}

// Cek apakah forum ada
$stmt = $conn->prepare("SELECT id FROM forums WHERE id = ?");
$stmt->bind_param('i', $forum_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    header('Location: join_forum.php?error=notfound');
    exit;
}

// Cek apakah sudah join
$stmt = $conn->prepare("SELECT id FROM forum_mahasiswa WHERE forum_id = ? AND mahasiswa_id = ?");
$stmt->bind_param('ii', $forum_id, $mahasiswa_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    header('Location: join_forum.php?error=already');
    exit;
}

// Masukkan data join
$stmt = $conn->prepare("INSERT INTO forum_mahasiswa (forum_id, mahasiswa_id) VALUES (?, ?)");
$stmt->bind_param('ii', $forum_id, $mahasiswa_id);
if ($stmt->execute()) {
    header('Location: join_forum.php?success=1');
    exit;
} else {
    header('Location: join_forum.php?error=insert');
    exit;
}
