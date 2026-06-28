<?php
// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user yang sedang login
$query_user = "SELECT * FROM users WHERE id = $user_id";
$result_user = mysqli_query($conn, $query_user);
$user_data = mysqli_fetch_assoc($result_user);

// Mengambil statistik singkat jumlah celengan
$query_stat = "SELECT COUNT(*) as total_celengan FROM target WHERE user_id = $user_id";
$result_stat = mysqli_query($conn, $query_stat);
$stat_data = mysqli_fetch_assoc($result_stat);