<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaksi_id = isset($_POST['transaksi_id']) ? intval($_POST['transaksi_id']) : 0;
    $target_id = isset($_POST['target_id']) ? intval($_POST['target_id']) : 0;

    if ($transaksi_id > 0 && $target_id > 0) {

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
