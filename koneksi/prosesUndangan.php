<?php
include './koneksi.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action']) || !isset($_POST['target_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$target_id = intval($_POST['target_id']);
$notif_id = intval($_POST['notif_id']);
$action = $_POST['action'];

if ($action === 'accept') {
    $update = mysqli_query($conn, "UPDATE target_shares SET status = 'accepted' WHERE target_id = '$target_id' AND user_id = '$user_id'");
    
    if ($update) {
        $_SESSION['success'] = "Berhasil! Anda sekarang resmi bergabung ke dalam celengan bersama ini.";
    } else {
        $_SESSION['error'] = "Terjadi kesalahan sistem saat mencoba bergabung.";
    }
} elseif ($action === 'decline') {
    $delete = mysqli_query($conn, "DELETE FROM target_shares WHERE target_id = '$target_id' AND user_id = '$user_id'");
    
    if ($delete) {
        mysqli_query($conn, "DELETE FROM notifications WHERE id = '$notif_id' AND user_id = '$user_id'");
        
        $_SESSION['success'] = "Undangan kolaborasi berhasil ditolak.";
        header("Location: ../notification.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal memproses penolakan.";
    }
}

mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE id = '$notif_id' AND user_id = '$user_id'");

header("Location: ../notification.php");
exit;