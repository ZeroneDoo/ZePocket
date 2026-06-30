<nav class="navbar navbar-expand-lg bg-body border-bottom sticky-top py-2">
	<div class="container">
		<a class="navbar-brand d-flex align-items-center gap-2 font-serif fw-bold fs-4" href="index.php" style="color: var(--zp-deep);">
			<span class="d-inline-block rounded-circle" style="width: 9px; height: 9px; background-color: var(--zp-terracotta);"></span> ZePocket
		</a>

		<button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse justify-content-lg-end" id="navMain">
			<div class="d-flex align-items-center gap-3 pt-3 pt-lg-0 mt-2 mt-lg-0 w-100 justify-content-start justify-content-lg-end">

				<a class="d-flex align-items-center justify-content-center text-decoration-none position-relative rounded-circle bg-light border border-light-subtle shadow-none"
					href="notification.php"
					style="width: 36px; height: 36px; color: var(--zp-deep);">
					<i class="fa-regular fa-bell fs-5"></i>
					<span class="position-absolute top-0 start-100 translate-middle p-1 border border-white rounded-circle"
						style="margin-top: 6px; margin-left: -6px; background-color: var(--zp-terracotta);"></span>
				</a>

				<div class="dropdown">
					<a class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle show-caret shadow-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--zp-ink);">
						<img src="<?= (!empty($_SESSION['user_img'])) ? './img/' . $_SESSION['user_img'] : 'https://api.dicebear.com/9.x/initials/svg?seed=' . urlencode($_SESSION['user_name']) ?>"
							class="rounded-circle border object-fit-cover"
							style="width: 34px; height: 34px;"
							alt="Foto Profil">
						<span class="fw-semibold small d-none d-sm-inline"><?= $_SESSION['user_name'] ?></span>
					</a>

					<ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-2 rounded-3 border-opacity-75 bg-white dropdown-custom" style="min-width: 200px;">

						<li class="px-3 py-2 d-lg-none bg-white rounded-top-3">
							<div class="small text-muted">Masuk sebagai</div>
							<div class="fw-bold small text-truncate"><?= $_SESSION['user_name'] ?></div>
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
	</div>
</nav>