<?php
function formatTanggal(?string $tanggal, $format = 'd M Y'): string
{
    if (empty($tanggal)) {
        return 'Tanpa Tenggat';
    }
    return date($format, strtotime($tanggal));
}

function formatRupiah(float $nominal, bool $denganRp = true, int $desimal = 0): string {
    // Pastikan nilai null atau kosong diubah menjadi 0, dan dikonversi ke float
    $angka = (float) ($nominal ?? 0);
    
    // Proses formatting angka dengan ribuan berbasis titik (.) dan desimal koma (,)
    $angkaFormat = number_format($angka, $desimal, ',', '.');
    
    // Gabungkan dengan prefix Rp jika parameter $denganRp bernilai true
    return $denganRp ? "Rp " . $angkaFormat : $angkaFormat;
}

function getCollaborators(mysqli $conn, int $target_id, int $owner_id) {
    $target_id = intval($target_id);
    $owner_id = intval($owner_id);
    
    $sql = "
        SELECT u.name, u.img_url 
        FROM users u 
        WHERE u.id = '$owner_id'
        UNION
        SELECT u.name, u.img_url 
        FROM target_shares ts 
        JOIN users u ON ts.user_id = u.id 
        WHERE ts.target_id = '$target_id'
    ";
    
    $result = mysqli_query($conn, $sql);
    $members = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $members[] = $row;
        }
    }
    
    return $members;
}

function addNotification(mysqli $conn, int $user_id, int $target_id, string $type, string $message) {
    $user_id = intval($user_id);
    $target_id = intval($target_id);
    $type = mysqli_real_escape_string($conn, $type);
    $message = mysqli_real_escape_string($conn, $message);
    
    $sql = "INSERT INTO notifications (user_id, target_id, type, message) 
            VALUES ('$user_id', '$target_id', '$type', '$message')";
    return mysqli_query($conn, $sql);
}

function updateIsReadNotification(mysqli $conn, int $user_id) {
    $user_id = intval($user_id);

    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = '$user_id'";
    return mysqli_query($conn, $sql);
}