<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaksi_id = isset($_POST['transaksi_id']) ? intval($_POST['transaksi_id']) : 0;
    $target_id = isset($_POST['target_id']) ? intval($_POST['target_id']) : 0;
    $user_id = intval($_SESSION['user_id']);

    if ($transaksi_id > 0 && $target_id > 0) {

        $user_check_query = "
            SELECT tr.id 
            FROM transaksi tr
            JOIN target t ON tr.target_id = t.id
            LEFT JOIN target_shares ts ON t.id = ts.target_id AND ts.user_id = $user_id
            WHERE tr.id = $transaksi_id 
                AND tr.target_id = $target_id 
                AND (t.user_id = $user_id OR ts.status = 'accepted')
        ";

        $user_check_result = mysqli_query($conn, $user_check_query);

        if (!$user_check_result || mysqli_num_rows($user_check_result) === 0) {
            $_SESSION['error'] = "Anda tidak memiliki akses untuk menghapus transaksi ini!";
            header("Location: ../index.php");
            exit;
        }

        // Query SQL menghapus baris transaksi berdasarkan id
        $query = "DELETE FROM transaksi WHERE id = $transaksi_id";

        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Transaksi berhasil dihapus dari celengan!";
        } else {
            $_SESSION['error'] = "Gagal menghapus transaksi: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Data transaksi tidak valid.";
    }

    // Kembalikan ke halaman detail celengan semula
    header("Location: ../detail.php?id=" . $target_id);
    exit;
} else {
    header("Location: ../index.php");
    exit;
}
