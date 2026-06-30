<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

$notif_sql = "
SELECT n.*, ts.status AS invite_status 
FROM notifications n
LEFT JOIN target_shares ts ON n.target_id = ts.target_id AND ts.user_id = n.user_id
WHERE n.user_id = '$user_id' 
ORDER BY n.created_at DESC";
$notif_res = mysqli_query($conn, $notif_sql);
