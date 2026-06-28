<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email)) {
        $_SESSION['error'] = "Nama dan Email tidak boleh dikosongkan.";
        header("Location: ../profile.php");
        exit;
    }

    // Email unik check
    $query_email = "SELECT id FROM users WHERE email = '$email' AND id != $user_id";
    $result_email = mysqli_query($conn, $query_email);

    if (mysqli_num_rows($result_email) > 0) {
        $_SESSION['error'] = "Email <strong>$email</strong> sudah terdaftar pada akun lain.";
        header("Location: ../profile.php");
        exit;
    }

    $sql_update = "name = '$name', email = '$email'";
    $is_avatar_uploaded = false;
    $new_avatar_name = "";

    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_img']['tmp_name'];
        $file_name = $_FILES['profile_img']['name'];
        $file_size = $_FILES['profile_img']['size'];

        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

        // Batasi ukuran gambar maks 2MB
        if (in_array($file_ext, $allowed_ext) && $file_size <= 2097152) {
            $new_avatar_name = "user_" . $user_id . "_" . time() . '.' . $file_ext;
            $upload_dir = '../img/';

            if (move_uploaded_file($file_tmp, $upload_dir . $new_avatar_name)) {

                // FIX: Menggunakan kolom 'img_url' sesuai database Anda
                $query_old_img = "SELECT img_url FROM users WHERE id = $user_id";
                $res_old_img = mysqli_query($conn, $query_old_img);
                if ($res_old_img && mysqli_num_rows($res_old_img) > 0) {
                    $old_img_data = mysqli_fetch_assoc($res_old_img);
                    if (!empty($old_img_data['img_url']) && file_exists($upload_dir . $old_img_data['img_url'])) {
                        unlink($upload_dir . $old_img_data['img_url']); // Hapus file lama
                    }
                }

                $safe_avatar = mysqli_real_escape_string($conn, $new_avatar_name);
                $sql_update .= ", img_url = '$safe_avatar'";
                $is_avatar_uploaded = true;
            }
        } else {
            $_SESSION['error'] = "Format gambar harus JPG/PNG/WebP dan ukuran maksimal 2MB.";
            header("Location: ../profile.php");
            exit;
        }
    }

    // Ubah sandi
    if (!empty($old_password) || !empty($new_password) || !empty($confirm_password)) {

        if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['error'] = "Untuk mengubah kata sandi, semua kolom sandi wajib diisi!";
            header("Location: ../profile.php");
            exit;
        }

        $query_pwd = "SELECT password FROM users WHERE id = $user_id";
        $res_pwd = mysqli_query($conn, $query_pwd);
        $user_pwd = mysqli_fetch_assoc($res_pwd);

        if (!password_verify($old_password, $user_pwd['password'])) {
            $_SESSION['error'] = "Kata sandi lama yang Anda masukkan salah.";
            header("Location: ../profile.php");
            exit;
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = "Konfirmasi kata sandi baru tidak cocok.";
            header("Location: ../profile.php");
            exit;
        }

        if (strlen($new_password) < 6) {
            $_SESSION['error'] = "Kata sandi baru minimal harus 6 karakter.";
            header("Location: ../profile.php");
            exit;
        }

        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $sql_update .= ", password = '$hashed_password'";
    }

    $sql = "UPDATE users SET $sql_update WHERE id = $user_id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Hore! Perubahan profil Anda berhasil disimpan.";

        $_SESSION['user_name']  = $_POST['name'];
        $_SESSION['user_email'] = $_POST['email'];

        if ($is_avatar_uploaded) {
            $_SESSION['user_img'] = $new_avatar_name;
        }
    } else {
        $_SESSION['error'] = "Gagal memperbarui profil. Terjadi kesalahan pada database: " . mysqli_error($conn);
    }

    header("Location: ../profile.php");
    exit;
} else {
    header("Location: ../profile.php");
    exit;
}
