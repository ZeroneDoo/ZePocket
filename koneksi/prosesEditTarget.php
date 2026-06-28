<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'];
    $target_id = intval($_POST['target_id']);
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $target_amount = $_POST['target_amount'];

    // Format tanggal jika kosong
    $target_date = !empty($_POST['target_date']) ? "'" . mysqli_real_escape_string($conn, $_POST['target_date']) . "'" : "NULL";

    // Query dasar untuk update text & nominal
    $sql_update_fields = "name = '$name', target_amount = '$target_amount', target_date = $target_date";

    // Proses upload gambar baru (jika ada yang diunggah)
    if (isset($_FILES['img_url']) && $_FILES['img_url']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['img_url']['tmp_name'];
        $file_name = $_FILES['img_url']['name'];
        $file_size = $_FILES['img_url']['size'];

        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($file_ext, $allowed_ext) && $file_size <= 2097152) {
            $new_file_name = time() . '.' . $file_ext;

            $upload_dir = '../img/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {

                // Hapus file gambar lama
                $query_old = "SELECT img_url FROM target WHERE id = $target_id AND user_id = $user_id";
                $res_old = mysqli_query($conn, $query_old);
                if ($res_old && mysqli_num_rows($res_old) > 0) {
                    $old_data = mysqli_fetch_assoc($res_old);
                    if (!empty($old_data['img_url']) && file_exists($upload_dir . $old_data['img_url'])) {
                        unlink($upload_dir . $old_data['img_url']);
                    }
                }

                // Tambahkan field img_url ke dalam rentetan update
                $safe_img = mysqli_real_escape_string($conn, $new_file_name);
                $sql_update_fields .= ", img_url = '$safe_img'";
            }
        }
    }

    // Eksekusi query UPDATE berdasarkan ID target dan ID user pemiliknya
    $sql = "UPDATE target SET $sql_update_fields WHERE id = $target_id AND user_id = $user_id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Perubahan pada celengan <strong>" . htmlspecialchars($name) . "</strong> berhasil disimpan.";
        // Dialihkan kembali ke halaman detail celengan tersebut
        header("Location: ../detail.php?id=" . $target_id);
    } else {
        $_SESSION['error'] = "Gagal mengubah target celengan. Terjadi kesalahan pada sistem.";
        header("Location: ../detail.php?id=" . $target_id);
    }
} else {
    header("Location: ../index.php");
}
exit;
