<?php
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email dan Password wajib diisi!";
    } else {
        // FIX 1: Tambahkan 'email' dan 'img_url' ke dalam query SELECT agar datanya bisa dibaca
        $query  = "SELECT id, name, email, password, img_url FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result); 

            if (password_verify($password, $user['password'])) {
                // Login Sukses
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name']  = $user['name'];
                $_SESSION['user_img']   = $user['img_url'];

                if (isset($_POST['remember'])) {
                    setcookie('remember_me', $user['email'], time() + (86400 * 30), "/");
                } else {
                    if (isset($_COOKIE['remember_me'])) {
                        setcookie('remember_me', '', time() - 3600, "/");
                    }
                }

                header("Location: ../index.php");
                exit;
            } else {
                $_SESSION['error'] = "Email atau Password yang dimasukan salah";
            }
        } else {
            $_SESSION['error'] = "Email atau Password yang dimasukan salah.";
        }
    }
}

// Jika gagal login, kembalikan ke halaman login
header("Location: ../login.php");
exit;
