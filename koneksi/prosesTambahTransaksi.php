<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $target_id = isset($_POST['target_id']) ? intval($_POST['target_id']) : 0;
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $user_id = $_SESSION['user_id'];

    // Validasi data inputan sebelum dimasukkan ke DB
    if ($target_id > 0 && $amount > 0 && ($type === 'masuk' || $type === 'keluar')) {
        $user_check_query = "
            SELECT t.id 
            FROM target t
            LEFT JOIN target_shares ts ON t.id = ts.target_id AND ts.user_id = $user_id
            WHERE t.id = $target_id AND (t.user_id = $user_id OR ts.status = 'accepted')
        ";

        $user_check_result = mysqli_query($conn, $user_check_query);

        if (!$user_check_result || mysqli_num_rows($user_check_result) === 0) {
            $_SESSION['error'] = "Anda tidak memiliki akses untuk menambah transaksi di celengan ini!";
            header("Location: ../index.php");
            exit;
        }

        $query = "INSERT INTO transaksi (target_id, user_id, amount, type) VALUES ($target_id, $user_id, $amount, '$type')";

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
