<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaksi_id = isset($_POST['transaksi_id']) ? intval($_POST['transaksi_id']) : 0;
    $target_id = isset($_POST['target_id']) ? intval($_POST['target_id']) : 0;
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $user_id = intval($_SESSION['user_id']);

    if ($transaksi_id > 0 && $target_id > 0 && $amount > 0 && ($type === 'masuk' || $type === 'keluar')) {

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
            $_SESSION['error'] = "Anda tidak memiliki akses untuk mengubah transaksi ini!";
            header("Location: ../index.php");
            exit;
        }

        $query = "UPDATE transaksi SET 
                    amount = $amount, 
                    type = '$type' 
                  WHERE id = $transaksi_id";

        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Transaksi berhasil diperbarui!";
            header("Location: ../detail.php?id=" . $target_id);
            exit;
        } else {
            $_SESSION['error'] = "Gagal memperbarui transaksi: " . mysqli_error($conn);
            header("Location: ../detail.php?id=" . $target_id);
            exit;
        }
    } else {
        $_SESSION['error'] = "Gagal memperbarui. Pastikan semua data yang Anda masukkan benar!";
        header("Location: ../detail.php?id=" . $target_id);
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}
