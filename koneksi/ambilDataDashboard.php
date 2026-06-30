<?php
if (!isset($_SESSION['user_id'])) {
   header("Location: ./login.php");
   exit;
}

$user_id = $_SESSION['user_id'];

$query_summary = "
SELECT 
   IFNULL(SUM(current_amount), 0) AS total_tabungan,
   COUNT(*) AS celengan,
   COUNT(CASE WHEN current_amount >= target_amount THEN 1 END) AS celengan_tercapai
FROM (
   SELECT
      t.target_amount,
      IFNULL(SUM(CASE 
         WHEN tr.type = 'masuk' THEN tr.amount 
         WHEN tr.type = 'keluar' THEN -tr.amount 
         ELSE 0 
      END), 0) AS current_amount
   FROM target t
   LEFT JOIN transaksi tr ON t.id = tr.target_id
   LEFT JOIN target_shares ts ON t.id = ts.target_id AND ts.user_id = '$user_id'
   WHERE t.user_id = '$user_id' OR ts.status = 'accepted'
   GROUP BY t.id
) AS tabel_bantu";

$query  = "
SELECT 
    t.id,
    t.user_id,
    t.name,
    t.target_amount,
    t.target_date,
    t.img_url,
    IFNULL(SUM(CASE 
        WHEN tr.type = 'masuk' THEN tr.amount 
        WHEN tr.type = 'keluar' THEN -tr.amount 
        ELSE 0 
    END), 0) AS current_amount,
    t.created_at, 
    t.updated_at,
    -- Fitur Tambahan: Mengetahui status peran (1 = Pemilik Utama, 0 = Anggota Kolaborasi)
    IF(t.user_id = '$user_id', 1, 0) AS is_owner
FROM target t
LEFT JOIN transaksi tr ON t.id = tr.target_id
LEFT JOIN target_shares ts ON t.id = ts.target_id AND ts.user_id = '$user_id'
WHERE t.user_id = '$user_id' OR ts.status = 'accepted'
GROUP BY t.id
ORDER BY t.created_at DESC;
";

$query_trx = "
SELECT 
   tr.id,
   tr.user_id,
   tr.amount, 
   tr.type, 
   tr.created_at, 
   t.name AS target_name,
   u.name AS creator_name,
   u.img_url AS img_url 
FROM transaksi tr
INNER JOIN target t ON tr.target_id = t.id
LEFT JOIN target_shares ts ON t.id = ts.target_id AND ts.user_id = '$user_id'
LEFT JOIN users u ON tr.user_id = u.id -- JOIN tambahan untuk mengambil data profil user
WHERE t.user_id = '$user_id' OR ts.status = 'accepted'
ORDER BY tr.created_at DESC
LIMIT 5
";

$result = mysqli_query($conn, $query);
$result_trx = mysqli_query($conn, $query_trx);
$result_summary = mysqli_query($conn, $query_summary);

$summary = mysqli_fetch_assoc($result_summary);

$total_tabungan_format = "Rp " . number_format($summary['total_tabungan'], 0, ',', '.');
$jum_celengan = $summary['celengan'];
$jum_celengan_tercapai = $summary['celengan_tercapai'];
