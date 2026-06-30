<?php
include './koneksi/koneksi.php';
include './koneksi/ambilDataDashboard.php';
include './utils/custom_function.php';

$title = "Dashboard";

include './partials/header.php';
?>
<div class="container py-4">
	<?php if (isset($_SESSION['success'])) : ?>
		<div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4 small d-flex align-items-center gap-2" role="alert" style="background-color: rgba(103, 125, 106, 0.15); color: var(--zp-sage);">
			<i class="fa-solid fa-circle-check fs-5"></i>
			<div><?= $_SESSION['success']; ?></div>
			<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<?php unset($_SESSION['success']); ?>
	<?php endif; ?>

	<?php if (isset($_SESSION['error'])) : ?>
		<div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4 small d-flex align-items-center gap-2" role="alert" style="background-color: rgba(217, 119, 87, 0.1); color: var(--zp-terracotta);">
			<i class="fa-solid fa-circle-exclamation fs-5"></i>
			<div><?= $_SESSION['error']; ?></div>
			<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<?php unset($_SESSION['error']); ?>
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
						data-bs-target="#modalCreateTarget"
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
					$persen = round(($row['current_amount'] / $row['target_amount']) * 100);
				}

				$bar_width = $persen > 100 ? 100 : $persen;
		?>
				<div class="col-12 col-sm-6 col-lg-3">
					<div class="card h-100 shadow-sm border bg-white rounded-4 position-relative">
						<div class="card-body d-flex flex-column justify-content-between p-3">
							<div>
								<div class="d-flex justify-content-between align-items-start mb-3">
									<img src="<?= $img ?>" class="border rounded-3 object-fit-cover w-100" alt="Cover Celengan" style="height: 170px;"
										onerror="this.outerHTML='<div class=&quot;w-100 d-flex align-items-center justify-content-center bg-light border border-dashed text-muted rounded-3&quot; style=&quot; height:170px;&quot;><i class=&quot;fa-solid fa-image&quot;></i></div>';">
								</div>

								<div class="d-flex align-items-start gap-2 mb-3">
									<div class="font-serif fw-bold text-truncate" style="font-size: 1.05rem; flex: 1;">
										<a href="detail.php?id=<?= $row['id'] ?>" class="stretched-link text-decoration-none" style="color: var(--zp-deep);"><?= htmlspecialchars($row['name']); ?></a>
									</div>
									<?php if (isset($row['is_owner']) && $row['is_owner'] == 0) : ?>
										<span class="badge rounded-pill bg-info bg-opacity-10 text-info border border-info border-opacity-20 px-2 py-1 extra-small" style="font-size: 0.68rem; position: relative; z-index: 2;">Bersama</span>
									<?php endif; ?>
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

								<div class="d-flex align-items-center mb-3" style="position: relative; z-index: 2;">
									<div class="d-flex align-items-center me-2">
										<?php
										$t_id = $row['id'];
										$members = getCollaborators($conn, $row['id'], $row['user_id']);
										$total_members = count($members);

										$limit = 0;
										foreach ($members as $collab) {
											$limit++;
											if ($limit > 3) continue;

											$avatar_src = !empty($collab['img_url'])
												? './img/' . htmlspecialchars($collab['img_url'])
												: 'https://ui-avatars.com/api/?name=' . urlencode($collab['name']) . '&background=random&color=fff';
										?>
											<img src="<?= $avatar_src ?>"
												class="rounded-circle border border-2 border-white object-fit-cover"
												style="width: 26px; height: 26px; margin-left: <?= $limit > 1 ? '-8px' : '0' ?>; position: relative; z-index: <?= 10 - $limit ?>;"
												alt="<?= htmlspecialchars($collab['name']) ?>"
												data-bs-toggle="tooltip"
												title="<?= htmlspecialchars($collab['name']) ?>">
										<?php
										}

										if ($total_members > 3) {
											$rem = $total_members - 3;
											echo "<div class='rounded-circle bg-light border border-2 border-white d-flex align-items-center justify-content-center text-muted fw-bold small shadow-sm' style='width: 26px; height: 26px; margin-left: -8px; font-size: 0.7rem; position: relative; z-index: 5;'>+{$rem}</div>";
										}
										?>
									</div>
									<span class="text-muted small" style="font-size: 0.78rem;">
										<?= $total_members > 1 ? $total_members . ' Anggota' : 'Pribadi' ?>
									</span>
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
					$simbol = $is_masuk ? '+' : '-';

					if ($is_masuk) {
						$warna = 'var(--zp-sage)';
						$judul = 'Setor ke ' . htmlspecialchars($trx['target_name']);
					} else {
						$warna = 'var(--zp-terracotta)';
						$judul = 'Tarik dari ' . htmlspecialchars($trx['target_name']);
					}
			?>
					<li class="list-group-item d-flex align-items-center gap-3 py-3 border-secondary border-opacity-10 bg-transparent">
						<span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: <?= $warna ?>; flex-shrink: 0;"></span>

						<div class="flex-grow-1 text-truncate">
							<div class="fw-semibold small text-dark text-truncate mb-0.5"><?= $judul ?></div>

							<div class="text-muted extra-small" style="font-size: 0.75rem;"><?= formatTanggal($trx['created_at']) ?> &middot; <?= date('H:i', strtotime($trx['created_at'])) ?> WIB</div>

							<div class="text-muted d-flex align-items-center gap-1.5 mt-1" style="font-size: 0.7rem;">
								<?php
								// Menentukan source foto profil pencatat transaksi
								if (!empty($trx['creator_avatar'])) {
									$avatar_src = "./img/" . htmlspecialchars($trx['img_url']);
								} else {
									// Fallback otomatis menggunakan UI-Avatars jika belum unggah foto profil
									$nama_user = urlencode($trx['creator_name'] ?? 'Pemilik');
									$avatar_src = "https://api.dicebear.com/9.x/initials/svg?seed={$nama_user}";
								}
								?>

								<img src="<?= $avatar_src ?>"
									alt="Avatar"
									class="rounded-circle object-fit-cover"
									style="width: 16px; height: 16px; border: 1px solid rgba(0,0,0,0.06);">

								<span class="text-truncate">Oleh: <strong class="text-secondary"><?= htmlspecialchars($trx['creator_name'] ?? 'Pemilik') ?></strong></span>
							</div>
						</div>

						<span class="fw-bold small flex-shrink-0" style="color: <?= $warna ?>"><?= $simbol ?><?= formatRupiah($trx['amount']) ?></span>
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

<div class="modal fade bg" id="modalCreateTarget" tabindex="-1">
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
					<div class="mb-3">
						<label class="form-label fw-semibold small">Gambar Celengan</label>
						<input type="file" name="img_url" class="form-control py-2 shadow-none border" accept="image/*">
						<div class="form-text text-muted" style="font-size: 0.78rem;">Kalau tidak diunggah, akan dipakai gambar placeholder.</div>
					</div>

					<div class="mb-1">
						<label class="form-label fw-semibold small">Undang Teman ke Celengan Ini (Opsional)</label>
						<input type="text" name="invite_emails" class="form-control py-2 shadow-none border" placeholder="budi@gmail.com, siti@gmail.com">
						<div class="form-text text-muted" style="font-size: 0.78rem;">Pisahkan dengan tanda koma ( , ) jika ingin mengundang lebih dari 1 orang.</div>
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

<div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-sm">
		<div class="modal-content rounded-4 border-0 shadow bg-white">
			<div class="modal-body p-4 text-center">

				<div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 56px; height: 56px; background-color: rgba(217,119,87,0.1); color: var(--zp-terracotta);">
					<i class="fa-solid fa-right-from-bracket fs-4"></i>
				</div>

				<h5 class="font-serif fw-bold mb-2" id="modalLogoutLabel" style="color: var(--zp-deep);">Konfirmasi Keluar</h5>
				<p class="text-muted small mb-4">Apakah Anda yakin ingin keluar dari akun <strong class="text-dark">ZePocket</strong> Anda?</p>

				<div class="d-flex gap-2">
					<button type="button" class="btn btn-light w-100 fw-semibold py-2 small border text-muted" data-bs-dismiss="modal">Batal</button>
					<a href="./koneksi/prosesLogout.php" class="btn text-white w-100 fw-semibold py-2 small" style="background-color: var(--zp-terracotta);">Keluar</a>
				</div>

			</div>
		</div>
	</div>
</div>
<?php include './partials/footer.php'; ?>