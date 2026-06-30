<?php
include '../koneksi/koneksi.php';
require('../vendor/fpdf/fpdf.php');

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

$target_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id   = intval($_SESSION['user_id']);

if ($target_id <= 0) {
    die("ID Celengan tidak valid.");
}

$target_query = "
    SELECT t.*, u.name AS owner_name,
           IFNULL(SUM(CASE 
                WHEN tr.type = 'masuk' THEN tr.amount 
                WHEN tr.type = 'keluar' THEN -tr.amount 
                ELSE 0 
           END), 0) AS current_amount
    FROM target t
    LEFT JOIN transaksi tr ON t.id = tr.target_id
    LEFT JOIN users u ON t.user_id = u.id
    LEFT JOIN target_shares ts ON t.id = ts.target_id AND ts.user_id = $user_id
    WHERE t.id = $target_id AND (t.user_id = $user_id OR ts.status = 'accepted')
    GROUP BY t.id
";
$target_result = mysqli_query($conn, $target_query);

if (!$target_result || mysqli_num_rows($target_result) === 0) {
    die("Celengan tidak ditemukan atau Anda tidak memiliki akses!");
}
$data_target = mysqli_fetch_assoc($target_result);

$collab_query = "
    SELECT u.name 
    FROM target_shares ts
    JOIN users u ON ts.user_id = u.id
    WHERE ts.target_id = $target_id AND ts.status = 'accepted'
";
$collab_result = mysqli_query($conn, $collab_query);
$daftar_anggota = [];
while ($collab = mysqli_fetch_assoc($collab_result)) {
    $daftar_anggota[] = $collab['name'];
}

$trx_query = "
    SELECT tr.*, u.name AS creator_name
    FROM transaksi tr
    LEFT JOIN users u ON tr.user_id = u.id
    WHERE tr.target_id = $target_id
    ORDER BY tr.created_at ASC
";
$trx_result = mysqli_query($conn, $trx_query);
$total_trx  = mysqli_num_rows($trx_result);

// ==========================================
// PROSES GENERATE STRUK BELANJA (FPDF)
// ==========================================

$pdf = new FPDF('P', 'mm', array(80, 297));
$pdf->SetMargins(5, 5, 5); 
$pdf->AddPage();

$pdf->SetFont('Courier', 'B', 14);
$pdf->Cell(70, 6, 'ZEPOCKET', 0, 1, 'C');
$pdf->SetFont('Courier', '', 8);
$pdf->Cell(70, 4, 'Asisten Keuangan & Celengan Bersama', 0, 1, 'C');
$pdf->Cell(70, 4, date('d/m/Y H:i') . ' WIB', 0, 1, 'C');

// Pembatas Garis Putus-putus
$pdf->Cell(70, 4, '========================================', 0, 1, 'C');

// --- INFORMASI CELENGAN (TARGET) ---
$pdf->SetFont('Courier', 'B', 9);
$pdf->Cell(70, 5, 'CELENGAN: ' . strtoupper($data_target['name']), 0, 1, 'L');
$pdf->SetFont('Courier', '', 9);
$pdf->Cell(70, 4, 'Pemilik : ' . $data_target['owner_name'], 0, 1, 'L');

if (count($daftar_anggota) > 0) {
    $pdf->Cell(70, 4, 'Anggota : ' . implode(', ', $daftar_anggota), 0, 1, 'L');
} else {
    $pdf->Cell(70, 4, 'Anggota : (Celengan Pribadi)', 0, 1, 'L');
}

$pdf->Cell(70, 4, '----------------------------------------', 0, 1, 'C');

$target_amt  = floatval($data_target['target_amount']);
$current_amt = floatval($data_target['current_amount']);
$sisa_target = $target_amt - $current_amt;
if ($sisa_target < 0) $sisa_target = 0;
$persen      = $target_amt > 0 ? round(($current_amt / $target_amt) * 100, 1) : 0;

$pdf->Cell(35, 5, 'Target Nominal:', 0, 0, 'L');
$pdf->Cell(35, 5, 'Rp ' . number_format($target_amt, 0, ',', '.'), 0, 1, 'R');

$pdf->Cell(35, 5, 'Total Terkumpul:', 0, 0, 'L');
$pdf->Cell(35, 5, 'Rp ' . number_format($current_amt, 0, ',', '.'), 0, 1, 'R');

$pdf->Cell(35, 5, 'Sisa Kekurangan:', 0, 0, 'L');
$pdf->Cell(35, 5, 'Rp ' . number_format($sisa_target, 0, ',', '.'), 0, 1, 'R');

$pdf->Cell(35, 5, 'Progres Pencapaian:', 0, 0, 'L');
$pdf->Cell(35, 5, $persen . ' %', 0, 1, 'R');

$pdf->Cell(70, 4, '========================================', 0, 1, 'C');
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(70, 5, 'RIWAYAT MUTASI TRANSAKSI', 0, 1, 'C');
$pdf->Cell(70, 4, '----------------------------------------', 0, 1, 'C');

// --- DAFTAR TRANSAKSI ---
$pdf->SetFont('Courier', '', 8);

if ($total_trx > 0) {
    $no = 1;
    while ($row = mysqli_fetch_assoc($trx_result)) {
        $tgl = date('d/m/y H:i', strtotime($row['created_at']));
        $oleh = substr($row['creator_name'] ?? 'Pemilik', 0, 12); // Batasi nama agar tidak kepanjangan
        $pdf->Cell(40, 4, $tgl . ' [Oleh:' . $oleh . ']', 0, 0, 'L');
        
        $tipe = ($row['type'] === 'masuk') ? '(MASUK)' : '(KELUAR)';
        $pdf->Cell(30, 4, $tipe, 0, 1, 'R');

        $simbol = ($row['type'] === 'masuk') ? '+' : '-';
        $pdf->SetFont('Courier', 'B', 9);
        $pdf->Cell(70, 4, $simbol . ' Rp ' . number_format($row['amount'], 0, ',', '.'), 0, 1, 'R');
        $pdf->SetFont('Courier', '', 8);
        
        $pdf->Ln(1);
    }
} else {
    $pdf->Cell(70, 6, '(Belum ada riwayat transaksi)', 0, 1, 'C');
}

// --- FOOTER STRUK ---
$pdf->Cell(70, 4, '========================================', 0, 1, 'C');
$pdf->SetFont('Courier', '', 9);
$pdf->Cell(70, 5, 'Total Transaksi: ' . $total_trx, 0, 1, 'L');
$pdf->Ln(4);

$pdf->SetFont('Courier', 'I', 9);
$pdf->Cell(70, 4, 'Terima Kasih', 0, 1, 'C');
$pdf->Cell(70, 4, 'Mari Disiplin Menabung! ;)', 0, 1, 'C');

$filename = "Cetak_ZePocket_" . time() . ".pdf";
$pdf->Output('I', $filename);
exit;