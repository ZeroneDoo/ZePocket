<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'];
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $target_amount = $_POST['target_amount'];

    $target_date = !empty($_POST['target_date']) ? $_POST['target_date'] : null;
    $img_url = null;

    // upload image
    if (isset($_FILES['img_url']) && $_FILES['img_url']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['img_url']['tmp_name'];
        $file_name = $_FILES['img_url']['name'];
        $file_size = $_FILES['img_url']['size'];

        // Ambil ekstensi file
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

        // Validasi ekstensi & ukuran maksimum (misal: 2MB)
        if (in_array($file_ext, $allowed_ext) && $file_size <= 2097152) {
            // Berikan nama unik untuk file agar tidak bentrok
            $new_file_name = time() . '.' . $file_ext;

            $upload_dir = '../img/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true); // Buat folder otomatis jika belum ada
            }

            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                $img_url = $new_file_name; // Simpan nama file ke database
            }
        }
    }

    $img_url_val = ($img_url !== null) ? "'" . mysqli_real_escape_string($conn, $img_url) . "'" : "NULL";

    $sql = "INSERT INTO target (user_id, name, target_amount, target_date, img_url) 
        VALUES ('$user_id', '$name', '$target_amount', '$target_date', $img_url_val)";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Hore! Celengan baru <strong>" . htmlspecialchars($name) . "</strong> berhasil dibuat.";
        header("Location: ../index.php");
    } else {
        $_SESSION['error'] = "Gagal membuat celengan baru. Terjadi kesalahan pada sistem.";
        header("Location: ../index.php?status=failed_add_target");
    }
} else {
    // Jika diakses langsung tanpa POST
    header("Location: ../index.php");
}

header("Location: ../index.php");
exit;
