<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
	header("Location: ../login.php");
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$user_id = $_SESSION['user_id'];
	$user_email = $_SESSION['user_email'];
	$user_name = $_SESSION['user_name'];
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
		// Ambil id target yang baru diinsert
		$target_id = mysqli_insert_id($conn);

		$success_message = "Hore! Celengan baru <strong>" . htmlspecialchars($name) . "</strong> berhasil dibuat.";

		//  undangan teman
		if (!empty($_POST['invite_emails'])) {
			$emails_array = explode(',', $_POST['invite_emails']);
			$invited_count = 0;
			$failed_emails = [];

			foreach ($emails_array as $email_raw) {
				$email = mysqli_real_escape_string($conn, trim($email_raw));

				if (empty($email)) continue;
				if ($email === $user_email) continue;

				// check email terdaftar 
				$user_query = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
				if (mysqli_num_rows($user_query) > 0) {
					$friend_data = mysqli_fetch_assoc($user_query);
					$friend_id = $friend_data['id'];

					$insert_share = mysqli_query($conn, "INSERT IGNORE INTO target_shares (target_id, user_id, status) VALUES ('$target_id', '$friend_id', 'pending')");
					
					// Pastikan baris data benar-benar bertambah (bukan terlewat/ignore akibat constraint)
					if ($insert_share && mysqli_affected_rows($conn) > 0) {
						$invited_count++;

						$invite_msg = "<strong>" . mysqli_real_escape_string($conn, $user_name) . "</strong> mengundang Anda untuk bergabung dalam kolaborasi celengan <strong>" . mysqli_real_escape_string($conn, $name) . "</strong>.";
						
						mysqli_query($conn, "INSERT INTO notifications (user_id, target_id, type, message) VALUES ('$friend_id', '$target_id', 'invitation', '$invite_msg')");
					}
					$invited_count++;
				} else {
					$failed_emails[] = htmlspecialchars($email);
				}
			}

			if ($invited_count > 0) {
				$success_message .= " Berhasil mengirimkan " . $invited_count . " undangan celengan bersama.";
			}
			if (!empty($failed_emails)) {
				$success_message .= "<br><span class='text-danger small'>Beberapa email tidak terdaftar: " . implode(', ', $failed_emails) . "</span>";
			}
		}

		$_SESSION['success'] = $success_message;
		header("Location: ../index.php");
	} else {
		$_SESSION['error'] = "Gagal membuat celengan baru. Terjadi kesalahan pada sistem.";
		header("Location: ../index.php");
	}
} else {
	header("Location: ../index.php");
}

exit;
