<?php
include './koneksi/koneksi.php';
include './koneksi/ambilDataProfile.php'
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil ZePocket</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./css/main.css">
</head>

<body>

  <div class="container py-5" style="max-width: 600px;">

    <div class="mb-4">
      <a href="index.php" class="text-decoration-none text-muted small fw-semibold">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Dashboard
      </a>
    </div>
    
    <?php if (isset($_SESSION['success'])) : ?>
      <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4 small d-flex align-items-center gap-2" role="alert" style="background-color: rgba(103, 125, 106, 0.15); color: var(--zp-sage);">
        <i class="fa-solid fa-circle-check fs-5"></i>
        <div><?= $_SESSION['success']; ?></div>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['success']);
      ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])) : ?>
      <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4 small d-flex align-items-center gap-2" role="alert" style="background-color: rgba(217, 119, 87, 0.1); color: var(--zp-terracotta);">
        <i class="fa-solid fa-circle-exclamation fs-5"></i>
        <div><?= $_SESSION['error']; ?></div>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['error']);
      ?>
    <?php endif; ?>

    <form action="./koneksi/prosesEditProfile.php" method="POST" enctype="multipart/form-data">

      <div class="card shadow-sm border bg-white rounded-4 text-center p-4 mb-4">
        <div class="position-relative d-inline-block mx-auto mb-2">

          <?php if (!empty($user_data['img_url']) && file_exists('./img/' . $user_data['img_url'])) : ?>
            <img src="./img/<?= $user_data['img_url'] ?>" class="rounded-circle shadow-sm object-fit-cover" style="width: 80px; height: 80px;" alt="Profile">
          <?php else : ?>
            <img src="<?= 'https://api.dicebear.com/9.x/initials/svg?seed=' . $user_data['name'] ?>" class="rounded-circle shadow-sm object-fit-cover" style="width: 80px; height: 80px;" alt="Profile">
          <?php endif; ?>

        </div>

        <div class="mx-auto mb-3" style="max-width: 220px;">
          <input type="file" name="profile_img" class="form-control form-control-sm shadow-none border small text-muted" accept="image/*">
        </div>

        <h4 class="font-serif fw-bold mb-1 text-deep"><?= htmlspecialchars($user_data['name']) ?></h4>
        <p class="text-muted small mb-3"><?= htmlspecialchars($user_data['email']) ?></p>

        <div class="d-flex justify-content-center gap-4 border-top border-secondary border-opacity-10 pt-3 mt-2">
          <div class="text-center">
            <span class="d-block fw-bold fs-5 text-deep"><?= $stat_data['total_celengan'] ?></span>
            <span class="text-muted small" style="font-size: 0.78rem;">Total Celengan</span>
          </div>
          <div class="border-end border-secondary border-opacity-10"></div>
          <div class="text-center">
            <span class="d-block fw-bold fs-5 text-deep">
              <?= date('M Y', strtotime($user_data['created_at'])) ?>
            </span>
            <span class="text-muted small" style="font-size: 0.78rem;">Bergabung Sejak</span>
          </div>
        </div>
      </div>

      <div class="card shadow-sm border bg-white rounded-4 p-4">
        <h5 class="font-serif fw-bold mb-4 text-deep">
          <i class="fa-solid fa-gear me-2 opacity-50 text-sage"></i>Pengaturan Akun
        </h5>

        <div class="mb-3">
          <label class="form-label fw-semibold small text-deep">Nama Lengkap</label>
          <input type="text" name="name" class="form-control py-2 shadow-none border" value="<?= htmlspecialchars($user_data['name']) ?>" required>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold small text-deep">Alamat Email</label>
          <input type="email" name="email" class="form-control py-2 shadow-none border" value="<?= htmlspecialchars($user_data['email']) ?>" required>
        </div>

        <hr class="border-secondary border-opacity-10 my-4">

        <h6 class="fw-bold mb-3 small text-uppercase tracking-wider text-sage">
          <i class="fa-solid fa-lock me-2"></i>Ubah Kata Sandi
        </h6>

        <div class="mb-3">
          <label class="form-label fw-semibold small text-deep">Kata Sandi Lama</label>
          <input type="password" name="old_password" class="form-control py-2 shadow-none border" placeholder="Masukkan kata sandi saat ini">
          <div class="form-text text-muted" style="font-size: 0.75rem;">Kosongkan area kata sandi jika tidak ingin mengubahnya.</div>
        </div>

        <div class="row g-2">
          <div class="col-6 mb-3">
            <label class="form-label fw-semibold small text-deep">Kata Sandi Baru</label>
            <input type="password" name="new_password" class="form-control py-2 shadow-none border" placeholder="Minimal 6 karakter">
          </div>
          <div class="col-6 mb-3">
            <label class="form-label fw-semibold small text-deep">Konfirmasi Kata Sandi</label>
            <input type="password" name="confirm_password" class="form-control py-2 shadow-none border" placeholder="Ulangi kata sandi baru">
          </div>
        </div>

        <div class="d-grid mt-4">
          <button type="submit" class="btn btn-deep py-2.5 rounded-3 shadow-none border-0">
            Simpan Perubahan Profil
          </button>
        </div>

        <div class="text-center mt-4 pt-2 border-top border-secondary border-opacity-10">
          <a href="#" class="btn btn-link text-terracotta text-decoration-none small fw-semibold py-1" data-bs-toggle="modal" data-bs-target="#modalLogout">
            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Keluar dari Aplikasi
          </a>
        </div>

      </div>

    </form>

  </div>


  <!-- MODAL KONFIRMASI LOGOUT -->
  <div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content rounded-4 border-0 shadow bg-white">
        <div class="modal-body p-4 text-center">

          <!-- Ikon Indikator Keluar -->
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 56px; height: 56px; background-color: rgba(217,119,87,0.1); color: var(--zp-terracotta);">
            <i class="fa-solid fa-right-from-bracket fs-4"></i>
          </div>

          <!-- Pesan Teks -->
          <h5 class="font-serif fw-bold mb-2" id="modalLogoutLabel" style="color: var(--zp-deep);">Konfirmasi Keluar</h5>
          <p class="text-muted small mb-4">Apakah Anda yakin ingin keluar dari akun <strong class="text-dark">ZePocket</strong> Anda?</p>

          <!-- Tombol Pilihan -->
          <div class="d-flex gap-2">
            <!-- Tombol Batal (Menutup Modal) -->
            <button type="button" class="btn btn-light w-100 fw-semibold py-2 small border text-muted" data-bs-dismiss="modal">Batal</button>

            <!-- Tombol Eksekusi (Arahkan ke file logout backend Anda) -->
            <a href="./koneksi/prosesLogout.php" class="btn text-white w-100 fw-semibold py-2 small" style="background-color: var(--zp-terracotta);">Keluar</a>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>