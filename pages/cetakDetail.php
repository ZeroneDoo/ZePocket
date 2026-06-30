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
    ORDER BY tr.created_at DESC
";
$trx_result = mysqli_query($conn, $trx_query);
$total_trx  = mysqli_num_rows($trx_result);

// ==========================================
// PROSES GENERATE PDF MINIMALIS (A4)
// ==========================================

class MinimalistPDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', '16');
        $this->SetTextColor(45, 42, 38);
        $this->Cell(0, 10, 'ZePocket', 0, 1, 'L');
        
        $this->SetFont('Arial', '', '9');
        $this->SetTextColor(130, 130, 130);
        $this->Cell(0, 4, 'Laporan Mutasi & Informasi Pencapaian Target Celengan', 0, 1, 'L');
        
        $this->SetDrawColor(230, 230, 230);
        $this->SetLineWidth(0.3);
        $this->Line(15, 24, 195, 24);
        $this->Ln(8);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(160, 160, 160);
        $this->Cell(0, 10, 'Dicetak otomatis via ZePocket | Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'C');
    }
}

$pdf = new MinimalistPDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

// --- KATEGORI 1: INFORMASI UMUM ---
$pdf->SetFont('Arial', 'B', '11');
$pdf->SetTextColor(45, 42, 38);
$pdf->Cell(0, 6, '1. PROFIL CELENGAN', 0, 1, 'L');
$pdf->Ln(2);

$pdf->SetFont('Arial', '', '10');
$pdf->SetTextColor(80, 80, 80);

$pdf->Cell(35, 5, 'Nama Celengan', 0, 0); $pdf->Cell(5, 5, ':', 0, 0); 
$pdf->SetFont('Arial', 'B', '10'); $pdf->SetTextColor(45, 42, 38);
$pdf->Cell(0, 5, $data_target['name'], 0, 1);

$pdf->SetFont('Arial', '', '10'); $pdf->SetTextColor(80, 80, 80);
$pdf->Cell(35, 5, 'Pemilik Utama', 0, 0); $pdf->Cell(5, 5, ':', 0, 0); $pdf->Cell(0, 5, $data_target['owner_name'], 0, 1);

$text_anggota = (count($daftar_anggota) > 0) ? implode(', ', $daftar_anggota) : 'Tidak ada (Celengan Pribadi)';
$pdf->Cell(35, 5, 'Anggota Tim', 0, 0); $pdf->Cell(5, 5, ':', 0, 0); $pdf->Cell(0, 5, $text_anggota, 0, 1);

$tgl_target = !empty($data_target['target_date']) ? date('d F Y', strtotime($data_target['target_date'])) : 'Tanpa Tenggat Waktu';
$pdf->Cell(35, 5, 'Target Selesai', 0, 0); $pdf->Cell(5, 5, ':', 0, 0); $pdf->Cell(0, 5, $tgl_target, 0, 1);
$pdf->Ln(6);

// --- KATEGORI 2: IKHTISAR KEUANGAN ---
$pdf->SetFont('Arial', 'B', '11');
$pdf->SetTextColor(45, 42, 38);
$pdf->Cell(0, 6, '2. RINGKASAN SALDO & PROGRESS', 0, 1, 'L');
$pdf->Ln(2);

$target_amt  = floatval($data_target['target_amount']);
$current_amt = floatval($data_target['current_amount']);
$sisa_target = max(0, $target_amt - $current_amt);
$persen      = $target_amt > 0 ? round(($current_amt / $target_amt) * 100, 1) : 0;

$pdf->SetFont('Arial', '', '10'); $pdf->SetTextColor(80, 80, 80);
$pdf->Cell(45, 5, 'Target yang Dicapai', 0, 0); $pdf->Cell(5, 5, ':', 0, 0); $pdf->Cell(0, 5, 'Rp ' . number_format($target_amt, 0, ',', '.'), 0, 1);
$pdf->Cell(45, 5, 'Total Dana Terkumpul', 0, 0); $pdf->Cell(5, 5, ':', 0, 0); $pdf->Cell(0, 5, 'Rp ' . number_format($current_amt, 0, ',', '.'), 0, 1);
$pdf->Cell(45, 5, 'Sisa Kekurangan Dana', 0, 0); $pdf->Cell(5, 5, ':', 0, 0); $pdf->Cell(0, 5, 'Rp ' . number_format($sisa_target, 0, ',', '.'), 0, 1);
$pdf->Cell(45, 5, 'Persentase Progres', 0, 0); $pdf->Cell(5, 5, ':', 0, 0); 
$pdf->SetFont('Arial', 'B', '10'); $pdf->SetTextColor(217, 119, 87);
$pdf->Cell(0, 5, $persen . ' %', 0, 1);
$pdf->Ln(6);

// --- KATEGORI 3: RIWAYAT TRANSAKSI ---
$pdf->SetFont('Arial', 'B', '11');
$pdf->SetTextColor(45, 42, 38);
$pdf->Cell(0, 6, '3. RIWAYAT TRANSAKSI', 0, 1, 'L');
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', '9.5');
$pdf->SetTextColor(45, 42, 38);
$pdf->SetDrawColor(180, 180, 180);
$pdf->SetLineWidth(0.4);

$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Cell(40, 8, 'Tanggal & Waktu', 0, 0, 'L');
$pdf->Cell(50, 8, 'Dicatat Oleh', 0, 0, 'L');
$pdf->Cell(40, 8, 'Jenis Mutasi', 0, 0, 'L');
$pdf->Cell(50, 8, 'Nominal', 0, 1, 'R');
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(2);

$pdf->SetFont('Arial', '', '9.5');
$pdf->SetTextColor(80, 80, 80);
$pdf->SetDrawColor(240, 240, 240);
$pdf->SetLineWidth(0.2);

if ($total_trx > 0) {
    while ($row = mysqli_fetch_assoc($trx_result)) {
        $tanggal_trx = date('d-m-Y H:i', strtotime($row['created_at'])) . ' WIB';
        $pencatat    = htmlspecialchars($row['creator_name'] ?? 'Pemilik');
        
        if ($row['type'] === 'masuk') {
            $tipe_text = 'Uang Masuk';
            $simbol    = '+ Rp ';
            $pdf->SetTextColor(40, 167, 69);
        } else {
            $tipe_text = 'Uang Keluar';
            $simbol    = '- Rp ';
            $pdf->SetTextColor(217, 119, 87); 
        }
        
        $pdf->Cell(40, 7, $tanggal_trx, 0, 0, 'L');
        
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(50, 7, $pencatat, 0, 0, 'L');
        $pdf->Cell(40, 7, $tipe_text, 0, 0, 'L');
        
        if ($row['type'] === 'masuk') {
            $pdf->SetTextColor(40, 167, 69);
        } else {
            $pdf->SetTextColor(217, 119, 87);
        }
        $pdf->Cell(50, 7, $simbol . number_format($row['amount'], 0, ',', '.'), 0, 1, 'R');
        
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(1);
    }
} else {
    $pdf->SetTextColor(140, 140, 140);
    $pdf->Cell(180, 10, 'Belum ada riwayat mutasi transaksi pada celengan ini.', 0, 1, 'C');
}

$filename = "Laporan_ZePocket_" . str_replace(' ', '_', $data_target['name']) . ".pdf";
$pdf->Output('I', $filename);
exit;