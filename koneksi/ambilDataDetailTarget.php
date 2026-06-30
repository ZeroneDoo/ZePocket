<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$id_target = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

if ($id_target === 0) {
    header("Location: index.php");
    exit;
}

$query_detail = "
SELECT 
    t.*,
    IFNULL(SUM(CASE WHEN tr.type = 'masuk' THEN tr.amount ELSE 0 END), 0) AS total_masuk,
    IFNULL(SUM(CASE WHEN tr.type = 'keluar' THEN tr.amount ELSE 0 END), 0) AS total_keluar,
    DATEDIFF(t.target_date, CURDATE()) AS sisa_hari,
    -- Menandai apakah user aktif saat ini adalah pemilik celengan (1 = Owner, 0 = Anggota)
    IF(t.user_id = $user_id, 1, 0) AS is_owner
FROM target t
LEFT JOIN transaksi tr ON t.id = tr.target_id
LEFT JOIN target_shares ts ON t.id = ts.target_id AND ts.user_id = $user_id
WHERE t.id = $id_target AND (t.user_id = $user_id OR ts.status = 'accepted')
GROUP BY t.id
";

$result_detail = mysqli_query($conn, $query_detail);
$data = mysqli_fetch_assoc($result_detail);

if (!$data) {
    $_SESSION['error'] = "Celengan tidak ditemukan atau Anda tidak memiliki akses ke celengan tersebut.";
    header("Location: index.php");
    exit;
}

$query_transaksi = "
    SELECT tr.*, u.name AS creator_name, u.img_url AS img_url 
    FROM transaksi tr
    LEFT JOIN users u ON tr.user_id = u.id
    WHERE tr.target_id = $id_target
    ORDER BY tr.created_at DESC;
";
$result_transaksi = mysqli_query($conn, $query_transaksi);

$terkumpul = $data['total_masuk'] - $data['total_keluar'];
$sisa_target = $data['target_amount'] - $terkumpul;
if ($sisa_target < 0) $sisa_target = 0;

$persen = $data['target_amount'] > 0 ? round(($terkumpul / $data['target_amount']) * 100) : 0;
if ($persen > 100) $persen = 100;

$img = !empty($data['img_url']) ? './img/' . $data['img_url'] : '';
$tanggal_format = formatTanggal($data['target_date']);
