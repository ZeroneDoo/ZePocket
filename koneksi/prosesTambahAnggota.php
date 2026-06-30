<?php
include './koneksi.php';

// Proteksi Keamanan: Wajib Login & Wajib menggunakan Method POST
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];
$target_id = intval($_POST['target_id']);

$check_owner = mysqli_query($conn, "SELECT name, user_id FROM target WHERE id = '$target_id' AND user_id = '$user_id'");
if (mysqli_num_rows($check_owner) == 0) {
    $_SESSION['error'] = "Anda tidak memiliki hak akses untuk mengelola atau menambah anggota di celengan ini.";
    header("Location: ../detail.php?id=" . $target_id);
    exit;
}

$target_data = mysqli_fetch_assoc($check_owner);
$target_name = $target_data['name'];

// Ambil nama owner/pengundang untuk kebutuhan teks notifikasi
$owner_res = mysqli_query($conn, "SELECT name FROM users WHERE id = '$user_id'");
$owner_name = mysqli_fetch_assoc($owner_res)['name'];

// 2. PROSES UNDANGAN EMAIL
if (!empty($_POST['invite_emails'])) {
    // Saring email duplikat yang mungkin tidak sengaja terinput dalam satu kiriman
    $emails_array = array_unique(explode(',', $_POST['invite_emails']));
    $invited_count = 0;
    $failed_emails = [];

    foreach ($emails_array as $email_raw) {
        $email = mysqli_real_escape_string($conn, trim($email_raw));

        if (empty($email)) continue;
        if ($email === $user_email) continue; // Mencegah mengundang diri sendiri

        // Cek apakah email terdaftar di database aplikasi
        $user_query = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($user_query) > 0) {
            $friend_data = mysqli_fetch_assoc($user_query);
            $friend_id = $friend_data['id'];

            // Cek apakah teman tersebut sudah diundang sebelumnya (mencegah duplikasi data)
            $check_duplicate = mysqli_query($conn, "SELECT status FROM target_shares WHERE target_id = '$target_id' AND user_id = '$friend_id'");
            
            if (mysqli_num_rows($check_duplicate) == 0) {
                // Jalankan INSERT ke target_shares dengan status awal 'pending'
                $insert_share = mysqli_query($conn, "INSERT INTO target_shares (target_id, user_id, status) VALUES ('$target_id', '$friend_id', 'pending')");
                
                if ($insert_share && mysqli_affected_rows($conn) > 0) {
                    $invited_count++;

                    $invite_msg = "<strong>" . $owner_name . "</strong> mengundang Anda untuk bergabung dalam celengan <strong>" . $target_name . "</strong>.";
                    
                    mysqli_query($conn, "INSERT INTO notifications (user_id, target_id, type, message) VALUES ('$friend_id', '$target_id', 'invitation', '$invite_msg')");
                }
            } else {
                // Ambil statusnya untuk memberikan pesan error yang lebih spesifik kepada owner
                $status_exist = mysqli_fetch_assoc($check_duplicate)['status'];
                $status_msg = ($status_exist === 'accepted') ? 'sudah bergabung' : 'sudah diundang';
                $failed_emails[] = htmlspecialchars($email) . " ($status_msg)";
            }
        } else {
            $failed_emails[] = htmlspecialchars($email) . " (tidak terdaftar)";
        }
    }

    // Mengatur balikan pesan feedback ke halaman detail celengan
    if ($invited_count > 0) {
        $_SESSION['success'] = "Berhasil mengirimkan " . $invited_count . " undangan kolaborasi celengan.";
    }
    
    if (!empty($failed_emails)) {
        $failed_msg = "Beberapa email gagal diproses: " . implode(', ', $failed_emails);
        if (isset($_SESSION['success'])) {
            $_SESSION['success'] .= "<br><span class='text-danger small'>" . $failed_msg . "</span>";
        } else {
            $_SESSION['error'] = $failed_msg;
        }
    }
} else {
    $_SESSION['error'] = "Silakan masukkan minimal satu email teman yang ingin diundang.";
}

// Alihkan kembali ke halaman detail celengan terkait
header("Location: ../detail.php?id=" . $target_id);
exit;