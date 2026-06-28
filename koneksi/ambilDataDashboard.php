<?php
// Pastikan file ini tidak diakses langsung tanpa session user_id
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
      WHERE t.user_id = '$user_id'
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
	t.updated_at 
FROM target t
LEFT JOIN transaksi tr
ON t.id = tr.target_id
WHERE t.user_id = '$user_id'
GROUP BY t.id
ORDER BY t.created_at DESC;
";

$query_trx = "
SELECT 
    tr.amount, 
    tr.type, 
    tr.created_at, 
    t.name AS target_name
FROM transaksi tr
INNER JOIN target t ON tr.target_id = t.id
WHERE t.user_id = '$user_id'
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