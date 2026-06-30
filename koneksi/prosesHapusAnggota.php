<?php
include './koneksi.php';

// Proteksi Keamanan: Wajib Login, Wajib Method POST, dan Semua parameter harus terpenuhi
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['share_id']) || !isset($_POST['target_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$share_id = intval($_POST['share_id']);
$target_id = intval($_POST['target_id']);

$check_owner = mysqli_query($conn, "SELECT user_id FROM target WHERE id = '$target_id' AND user_id = '$user_id'");
if (mysqli_num_rows($check_owner) == 0) {
    $_SESSION['error'] = "Anda tidak memiliki hak akses kekuasaan untuk mengelola anggota di celengan ini.";
    header("Location: ../detail.php?id=" . $target_id);
    exit;
}

$delete = mysqli_query($conn, "DELETE FROM target_shares WHERE id = '$share_id' AND target_id = '$target_id'");

if ($delete) {
    $_SESSION['success'] = "Anggota / Undangan kolaborasi berhasil dihapus dari celengan.";
} else {
    $_SESSION['error'] = "Gagal menghapus anggota, terjadi kesalahan database.";
}

header("Location: ../detail.php?id=" . $target_id);
exit;