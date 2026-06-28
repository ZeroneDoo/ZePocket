<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan bersihkan data inputan
    $target_id = isset($_POST['target_id']) ? intval($_POST['target_id']) : 0;
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    // Validasi data inputan sebelum dimasukkan ke DB
    if ($target_id > 0 && $amount > 0 && ($type === 'masuk' || $type === 'keluar')) {

        // Buat query dengan Prepared Statement
        $query = "INSERT INTO transaksi (target_id, amount, type) VALUES ($target_id, $amount, '$type')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Transaksi berhasil dicatat ke dalam celengan!";
            header("Location: ../detail.php?id=" . $target_id);
            exit;
        } else {
            $_SESSION['error'] = "Gagal mengeksekusi transaksi: " . mysqli_error($conn);
            header("Location: ../detail.php?id=" . $target_id);
            exit;
        }
    } else {
        $_SESSION['error'] = "Gagal menyimpan. Pastikan nominal yang Anda masukkan benar!";
        header("Location: ../detail.php?id=" . $target_id);
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}
