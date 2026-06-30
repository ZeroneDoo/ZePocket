<?php
$title = "Halaman Tidak Ditemukan";
$use_navbar = false;
include '../partials/header.php'
?>

<div class="container min-vh-100 d-flex justify-content-center align-items-center text-center">
    <div class="p-4" style="max-width: 400px;">
        <img src="./img/icon/404.svg" alt="Image Not Found" class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4 bg-opacity-10 text-secondary" style="width: 400px; height: 400px;">
        <p class="text-muted small mb-4">Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
        <a href="index.php" class="btn text-white px-4 py-2 small rounded-3" style="background-color: var(--zp-deep, #677d66);">
            <i class="fa-solid fa-house me-2"></i>Kembali ke Dashboard
        </a>
    </div>
</div>

<?php include '../partials/footer.php' ?>