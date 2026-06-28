<?php
include './koneksi/koneksi.php';
include './koneksi/ambilDataDashboard.php';
include './vendor/custom_function.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ZePocket — Tabungan Bertarget</title>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="./css/main.css">
</head>

<body>

	<nav class="navbar navbar-expand-lg bg-body border-bottom sticky-top py-2">
		<div class="container">
			<a class="navbar-brand d-flex align-items-center gap-2 font-serif fw-bold fs-4" href="index.php" style="color: var(--zp-deep);">
				<span class="d-inline-block rounded-circle" style="width: 9px; height: 9px; background-color: var(--zp-terracotta);"></span> ZePocket
			</a>

			<button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse justify-content-lg-end" id="navMain">

				<div class="dropdown pt-3 pt-lg-0 border-lg-0 mt-2 mt-lg-0">
					<a class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle show-caret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--zp-ink);">
						<img src="<?= (!empty($_SESSION['user_img'])) ? './img/' . $_SESSION['user_img'] : 'https://api.dicebear.com/9.x/initials/svg?seed=' . urlencode($_SESSION['user_name']) ?>"
							class="rounded-circle border object-fit-cover"
							style="width: 34px; height: 34px;"
							alt="Foto Profil">
						<span class="fw-semibold small"><?= $_SESSION['user_name'] ?></span>
					</a>

					<ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-2 rounded-3 border-opacity-75 bg-white dropdown-custom" style="min-width: 200px;">

						<li class="px-3 py-2 d-lg-none bg-white rounded-top-3">
							<div class="small text-muted">Masuk sebagai</div>
							<div class="fw-bold small text-truncate">Budi Santoso</div>
						</li>

						<li>
							<hr class="dropdown-divider d-lg-none my-1 opacity-10">
						</li>

						<li>
							<a class="dropdown-item small py-2 d-flex align-items-center gap-2" href="profile.php">
								<i class="fa-regular fa-user text-muted" style="width: 16px;"></i> Profil Saya
							</a>
						</li>
						<li>
							<hr class="dropdown-divider my-1 opacity-10">
						</li>

						<li>
							<a class="dropdown-item small py-2 text-danger d-flex align-items-center gap-2" href="#" data-bs-toggle="modal" data-bs-target="#modalLogout">
								<i class="fa-solid fa-right-from-bracket" style="width: 16px;"></i> Keluar
							</a>
						</li>
					</ul>
				</div>

			</div>
		</div>
	</nav>

	<div class="container py-4">
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

		<div class="card shadow-sm border bg-white mb-4 rounded-4 overflow-hidden">
			<div class="card-body p-3 p-sm-4">
				<div class="row align-items-center g-3">

					<div class="col-12 col-md-auto pe-md-4 border-md-end border-secondary border-opacity-10">
						<div class="text-uppercase text-muted fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.06em;">Total Tabungan</div>
						<div class="font-serif fs-2 fw-bold m-0" style="color: var(--zp-deep); line-height: 1.1;"><?= $total_tabungan_format ?></div>
					</div>

					<div class="col-12 col-sm d-flex flex-wrap gap-2 align-items-center">
						<div class="d-flex align-items-center gap-2 py-2 px-3 rounded-3 bg-light border border-light-subtle">
							<i class="fa-solid fa-piggy-bank small" style="color: var(--zp-deep);"></i>
							<span class="text-muted" style="font-size: 0.82rem;"><strong class="text-dark fw-bold"><?= $jum_celengan ?></strong> Celengan</span>
						</div>

						<div class="d-flex align-items-center gap-2 py-2 px-3 rounded-3 bg-light border border-light-subtle">
							<i class="fa-solid fa-circle-check small" style="color: var(--zp-sage);"></i>
							<span class="text-muted" style="font-size: 0.82rem;"><strong class="text-dark fw-bold"><?= $jum_celengan_tercapai ?></strong> Sudah Tercapai</span>
						</div>
					</div>

					<div class="col-12 col-md-auto ms-md-auto w-100 w-md-auto">
						<button class="btn w-100 text-white fw-semibold px-4 py-2-5 rounded-3 d-flex align-items-center justify-content-center gap-2 shadow-none"
							data-bs-toggle="modal"
							data-bs-target="#modalNewGoal"
							style="background-color: var(--zp-deep); padding-top: 10px; padding-bottom: 10px;">
							<i class="fa-solid fa-plus small"></i>
							<span>Celengan Baru</span>
						</button>
					</div>

				</div>
			</div>
		</div>

		<h2 class="font-serif fs-4 mb-3 fw-bold" style="color: var(--zp-deep);">Celengan Kamu</h2>

		<div class="row g-3 mb-4">
			<?php
			if (mysqli_num_rows($result) > 0) :
				while ($row = mysqli_fetch_assoc($result)) :
					$img = !empty($row['img_url']) ? './img/' . $row['img_url'] : '';
					$tanggal_format = formatTanggal($row['target_date']);
					$nominal_target_format = formatRupiah($row['target_amount']);
					$nominal_current_format = formatRupiah($row['current_amount']);

					$persen = 0;
					if ($row['target_amount'] > 0) {
						// Rumus: (Uang Sekarang / Total Target) * 100, lalu dibulatkan dengan round()
						$persen = round(($row['current_amount'] / $row['target_amount']) * 100);
					}

					$bar_width = $persen > 100 ? 100 : $persen;
			?>
					<div class="col-12 col-sm-6 col-lg-3">
						<div class="card h-100 shadow-sm border bg-white rounded-4 position-relative">
							<div class="card-body d-flex flex-column justify-content-between p-3">
								<div>
									<div class="d-flex justify-content-between align-items-start mb-3">
										<img src="<?= $img ?>" class="border rounded-3 object-fit-cover w-100" alt="Bali" style="height: 170px;"
											onerror="this.outerHTML='<div class=&quot;w-100 d-flex align-items-center justify-content-center bg-light border border-dashed text-muted rounded-3&quot; style=&quot; height:170px;&quot;><i class=&quot;fa-solid fa-image&quot;></i></div>';">
									</div>

									<div class="font-serif fw-bold text-truncate mb-3" style="font-size: 1.05rem;">
										<a href="detail.php?id=<?= $row['id'] ?>" class="stretched-link text-decoration-none" style="color: var(--zp-deep);"><?= htmlspecialchars($row['name']); ?></a>
									</div>

									<div class="d-flex justify-content-between small mb-1">
										<span class="text-muted">Progres</span>
										<span class="fw-bold" style="color: var(--zp-terracotta);"><?= $bar_width ?>%</span>
									</div>
									<div class="progress mb-3" style="height: 7px;">
										<div class="progress-bar" role="progressbar" style="width: <?= $bar_width ?>%; background-color: var(--zp-terracotta);"></div>
									</div>

									<div class="d-flex justify-content-between small mb-3">
										<span class="fw-bold" style="color: var(--zp-deep);"><?= $nominal_current_format ?></span>
										<span class="text-muted">/ <?= $nominal_target_format; ?></span>
									</div>
								</div>

								<div class="text-center py-2 rounded-3 small text-muted bg-light border-0">
									<i class="fa-regular fa-calendar me-1"></i> Target: <?= $tanggal_format; ?>
								</div>
							</div>
						</div>
					</div>
				<?php
				endwhile;
			else :
				?>
				<div class="col-12 text-center py-5">
					<div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 bg-sage-subtle" style="width: 72px; height: 72px;">
						<i class="fa-solid fa-piggy-bank fs-2 text-sage"></i>
					</div>
					<p class="text-muted small">Belum ada target celengan. Yuk, buat target pertamamu!</p>
				</div>
			<?php endif ?>
		</div>

		<h2 class="font-serif fs-4 mb-3 fw-bold" style="color: var(--zp-deep);">Transaksi Terbaru</h2>
		<div class="card shadow-sm border bg-white rounded-4 overflow-hidden">
			<ul class="list-group list-group-flush">
				<?php
				if (mysqli_num_rows($result_trx) > 0) :
					while ($trx = mysqli_fetch_assoc($result_trx)) :

						$is_masuk = ($trx['type'] === 'masuk');
						$warna = $is_masuk ? 'var(--zp-sage)' : 'var(--zp-terracotta)';
						$simbol = $is_masuk ? '+' : '-';

						// 1. Format Tanggal & Nominal Uang
						$tgl_trx = date('d M Y', strtotime($trx['created_at']));
						$nominal_trx = "Rp " . number_format($trx['amount'], 0, ',', '.');

						// 2. Kondisional berdasarkan tipe transaksi (masuk / keluar)
						if ($trx['type'] === 'masuk') {
							$warna = 'var(--zp-sage)';
							$tanda = '+';
							$judul = 'Setor ke ' . htmlspecialchars($trx['target_name']);
						} else {
							$warna = 'var(--zp-terracotta)';
							$tanda = '-';
							$judul = 'Tarik dari ' . htmlspecialchars($trx['target_name']);
						}

				?>
						<li class="list-group-item d-flex align-items-center gap-3 py-3 border-secondary border-opacity-10 bg-transparent">
							<span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: <?= $warna ?>;"></span>

							<div class="flex-grow-1">
								<div class="fw-semibold small text-dark"><?= formatTanggal($trx['created_at']) ?> &middot; <?= formatTanggal($trx['created_at'], 'H:i') ?></div>
							</div>

							<span class="fw-bold small" style="color: <?= $warna ?>"><?= $simbol ?><?= formatRupiah($trx['amount']) ?></span>
						</li>

					<?php
					endwhile;
				else :
					?>
					<li class="list-group-item text-center py-4 text-muted small bg-transparent border-0">
						<i class="fa-solid fa-receipt d-block mb-2 fs-4 opacity-50"></i>
						Belum ada riwayat transaksi.
					</li>
				<?php endif; ?>
			</ul>
		</div>

	</div>

	<footer class="text-center py-2 mt-3 border-top text-muted small bg-white">
		<div class="container">
			<p>&copy; 2026 ZePocket. All rights reserved.</p>
		</div>
	</footer>

	<div class="modal fade bg" id="modalNewGoal" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow rounded-4 bg-white">
				<div class="modal-header border-bottom border-secondary border-opacity-10">
					<h5 class="modal-title font-serif fw-bold" style="color: var(--zp-deep);">Buat Target Baru</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body p-4">
					<form action="./koneksi/prosesTambahTarget.php" method="POST" enctype="multipart/form-data" id="formNewTarget">
						<div class="mb-3">
							<label class="form-label fw-semibold small">Nama Celengan</label>
							<input type="text" name="name" class="form-control py-2 shadow-none border" placeholder="Misal: Dana Pernikahan" required>
						</div>
						<div class="row g-2">
							<div class="col-6 mb-3">
								<label class="form-label fw-semibold small">Target Jumlah</label>
								<input type="number" name="target_amount" class="form-control py-2 shadow-none border" placeholder="Rp 0" required>
							</div>
							<div class="col-6 mb-3">
								<label class="form-label fw-semibold small">Target Tanggal</label>
								<input type="date" name="target_date" class="form-control py-2 shadow-none border" required>
							</div>
						</div>
						<div class="mb-1">
							<label class="form-label fw-semibold small">Gambar Celengan</label>
							<input type="file" name="img_url" class="form-control py-2 shadow-none border" accept="image/*">
							<div class="form-text text-muted" style="font-size: 0.78rem;">Kalau tidak diunggah, akan dipakai gambar placeholder.</div>
						</div>
					</form>
				</div>
				<div class="modal-footer border-top border-secondary border-opacity-10">
					<button type="button" class="btn btn-outline-secondary fw-semibold px-3" data-bs-dismiss="modal" style="color: var(--zp-deep);">Batal</button>
					<button type="submit" form="formNewTarget" class="btn text-white fw-semibold px-4" style="background-color: var(--zp-deep);">Simpan Target</button>
				</div>
			</div>
		</div>
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

	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>