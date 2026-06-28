<?php
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
	header("Location: ../index.php");
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = trim($_POST['name']);
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	// Validasi sederhana
	if (empty($name) || empty($email) || empty($password)) {
		$_SESSION['error'] = "Semua kolom wajib diisi!";
	} elseif (strlen($email) > 32) {
		$_SESSION['error'] = "Email terlalu panjang! Maksimal 32 karakter.";
	} else {
		// Cek apakah email sudah ada
		$query_cek  = "SELECT id FROM users WHERE email = '$email'";
		$result_cek = mysqli_query($conn, $query_cek);

		if (mysqli_num_rows($result_cek) > 0) {
			$_SESSION['error'] = "Email sudah terdaftar! Silakan gunakan email lain.";
		} else {
			$hashed_password = password_hash($password, PASSWORD_BCRYPT);
			$query_insert = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

			if (mysqli_query($conn, $query_insert)) {
				$_SESSION['success'] = "Registrasi berhasil! Silakan Login.";
				header("Location: ../login.php"); // Jika sukses, langsung lempar ke login
				exit;
			} else {
				$_SESSION['error'] = "Gagal mendaftar: " . mysqli_error($conn);
			}
		}
	}
}

header("Location: ../register.php");
exit;