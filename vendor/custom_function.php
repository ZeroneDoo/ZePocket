<?php
function formatTanggal(?string $tanggal, $format = 'd M Y'): string
{
    if (empty($tanggal)) {
        return 'Tanpa Tenggat';
    }
    return date($format, strtotime($tanggal));
}

function formatRupiah($nominal, bool $denganRp = true, int $desimal = 0): string {
    // Pastikan nilai null atau kosong diubah menjadi 0, dan dikonversi ke float
    $angka = (float) ($nominal ?? 0);
    
    // Proses formatting angka dengan ribuan berbasis titik (.) dan desimal koma (,)
    $angkaFormat = number_format($angka, $desimal, ',', '.');
    
    // Gabungkan dengan prefix Rp jika parameter $denganRp bernilai true
    return $denganRp ? "Rp " . $angkaFormat : $angkaFormat;
}