<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $target_id = isset($_POST['target_id']) ? intval($_POST['target_id']) : 0;

    if ($target_id > 0) {

        $query_img = "SELECT img_url, name FROM target WHERE id = $target_id AND user_id = $user_id";
        $res_img = mysqli_query($conn, $query_img);

        if ($res_img && mysqli_num_rows($res_img) > 0) {
            $data_target = mysqli_fetch_assoc($res_img);
            $nama_celengan = $data_target['name'];
            $file_gambar = $data_target['img_url'];
            $upload_dir = '../img/';

            if (!empty($file_gambar) && file_exists($upload_dir . $file_gambar)) {
                unlink($upload_dir . $file_gambar);
            }

            $query_delete_trx = "DELETE FROM transaksi WHERE target_id = $target_id";
            mysqli_query($conn, $query_delete_trx);

            $query_delete_target = "DELETE FROM target WHERE id = $target_id AND user_id = $user_id";

            if (mysqli_query($conn, $query_delete_target)) {
                $_SESSION['success'] = "Celengan <strong>" . htmlspecialchars($nama_celengan) . "</strong> beserta riwayat transaksinya telah dihapus secara permanen.";
                header("Location: ../index.php");
                exit;
            } else {
                $_SESSION['error'] = "Gagal menghapus celengan. Terjadi kesalahan sistem.";
            }
        } else {
            $_SESSION['error'] = "Celengan tidak ditemukan atau Anda tidak memiliki akses.";
        }
    } else {
        $_SESSION['error'] = "ID Celengan tidak valid.";
    }

    // Jika gagal, kembalikan ke halaman detail celengan semula
    header("Location: ../detail.php?id=" . $target_id);
    exit;
} else {
    header("Location: ../index.php");
    exit;
}
