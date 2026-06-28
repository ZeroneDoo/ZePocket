<?php
include './koneksi/koneksi.php';
if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Daftar — Kendi</title>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="./css/main.css">
</head>

<body>

	<div class="container min-vh-100 d-flex justify-content-center align-items-center py-5">

		<div class="card shadow-sm border bg-white p-4 p-sm-5 rounded-4 w-100" style="max-width: 440px;">

			<div class="text-center mb-4">
				<a class="font-serif fw-bold fs-3 text-decoration-none d-inline-flex align-items-center gap-2" href="#" style="color: var(--zp-deep);">
					<span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background-color: var(--zp-terracotta);"></span> ZePocket
				</a>
				<p class="text-muted small mt-2 mb-0">Mulai langkah awal untuk mewujudkan mimpimu</p>
			</div>

			<?php if (isset($_SESSION['error'])): ?>
				<div class="alert border-0 small py-2 px-3 rounded-3 mb-3 d-flex align-items-center gap-2" style="background: rgba(217,119,87,0.15); color: var(--zp-terracotta);">
					<i class="fa-solid fa-circle-exclamation"></i>
					<div><?= $_SESSION['error']; ?></div>
				</div>
				<?php unset($_SESSION['error']); ?>
			<?php endif; ?>

			<form action="./koneksi/prosesRegister.php" method="POST">
				<div class="mb-3">
					<label class="form-label fw-semibold small mb-1">Nama Lengkap</label>
					<input type="text" class="form-control py-2 shadow-none border" name="name" required placeholder="John Doe">
				</div>

				<div class="mb-3">
					<label class="form-label fw-semibold small mb-1">Alamat Email</label>
					<input type="email" class="form-control py-2 shadow-none border" name="email" required placeholder="nama@email.com">
					<div class="form-text text-muted" style="font-size: 0.72rem;">Sesuai batasan tabel database (Maks. 32 karakter).</div>
				</div>

				<div class="mb-3">
					<label class="form-label fw-semibold small mb-1">Password Baru</label>
					<input type="password" class="form-control py-2 shadow-none border" name="password" required placeholder="••••••••"
						</div>

					<button type="submit" class="btn text-white w-100 fw-semibold py-2 mt-2" style="background-color: var(--zp-deep);">
						Daftar Akun Baru
					</button>
			</form>

			<div class="text-center mt-4">
				<span class="text-muted small">Sudah memiliki akun?
					<a href="login.php" class="fw-semibold text-decoration-none" style="color: var(--zp-terracotta);">Masuk di sini</a>
				</span>
			</div>

		</div>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>