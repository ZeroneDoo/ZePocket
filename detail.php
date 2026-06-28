<?php
include "./koneksi/koneksi.php";
include "./vendor/custom_function.php";
include "./koneksi/ambilDataDetailTarget.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dana Liburan ke Bali — ZePocket</title>
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

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 small" role="alert">
        <i class="fa-solid fa-circle-check me-1"></i>
        <?= $_SESSION['success']; ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 small" role="alert">
        <i class="fa-solid fa-circle-exclamation me-1"></i>
        <?= $_SESSION['error']; ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <a href="index.php" class="bg-white btn btn-light btn-sm border border-secondary border-opacity-10 d-inline-flex align-items-center gap-2 mb-3 rounded-3 px-3 py-1.5 fw-medium shadow-none" style="color: var(--zp-deep);">
      <i class="fa-solid fa-arrow-left small"></i> Kembali ke Dasbor
    </a>

    <div class="card shadow-sm border bg-white mb-4 rounded-4 overflow-hidden">
      <div class="card-body p-3 p-sm-4">
        <img src="<?= $img ?>" class="w-100 border rounded-4 object-fit-cover shadow-sm mx-auto mx-sm-0 flex-shrink-0" alt="Bali" style="height: 300px;"
          onerror="this.outerHTML='<div class=&quot;w-100 d-flex align-items-center justify-content-center bg-light border border-dashed text-muted rounded-4 shadow-sm mx-auto mx-sm-0 flex-shrink-0&quot; style=&quot;height:300px;&quot;><i class=&quot;fa-solid fa-image fs-3&quot;></i></div>';">
        <div class="d-flex flex-column flex-sm-row gap-4 align-items-start mt-3">

          <div class="flex-grow-1 w-100">
            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-1">
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <h1 class="font-serif fw-bold fs-3 mb-0" style="color: var(--zp-deep);"><?= $data['name'] ?></h1>
              </div>

              <div class="dropdown">
                <button class="btn btn-light btn-sm border border-secondary border-opacity-10 shadow-none px-2.5 rounded-3" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border rounded-3 bg-white dropdown-custom">
                  <li><a class="dropdown-item small py-2" href="#" data-bs-toggle="modal" data-bs-target="#modalEditTarget"><i class="fa-solid fa-pen me-2 text-muted" style="width: 14px;"></i>Edit Celengan</a></li>
                  <li>
                    <hr class="dropdown-divider my-1 opacity-10">
                  </li>
                  <li><a class="dropdown-item small py-2 text-danger" href="#" data-bs-toggle="modal" data-bs-target="#modalDeleteTarget"><i class="fa-solid fa-trash me-2" style="width: 14px;"></i>Hapus Celengan</a></li>
                </ul>
              </div>
            </div>

            <div class="text-muted small mb-3">
              <i class="fa-regular fa-calendar me-1"></i> Target: <?= $tanggal_format ?>
              &nbsp;&middot;&nbsp;
              <i class="fa-regular fa-clock me-1"></i> Dibuat <?= formatTanggal($data['created_at']) ?>
            </div>

            <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
              <div class="font-serif fw-bold fs-1" style="color: var(--zp-terracotta); line-height: 1;"><?= $persen ?>%</div>
              <div class="flex-grow-1" style="min-width: 200px;">
                <div class="progress mb-1.5" style="height: 10px; background-color: rgba(45, 42, 38, 0.08);">
                  <div class="progress-bar" role="progressbar" style="width: <?= $persen ?>%; background-color: var(--zp-terracotta);"></div>
                </div>
                <div class="small text-muted">
                  <span class="fw-bold" style="color: var(--zp-deep);"><?= formatRupiah($terkumpul) ?></span> / <?= formatRupiah($data['target_amount']) ?>
                </div>
              </div>
            </div>

            <button class="btn text-white fw-semibold px-4 py-2 rounded-3 shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#modalTransaction" style="background-color: var(--zp-deep);">
              <i class="fa-solid fa-plus small me-1"></i> Tambah Transaksi
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row g-3 mb-4">

      <div class="col-6 col-md-4">
        <div class="p-3 rounded-4 bg-white border shadow-sm h-100 d-flex flex-column justify-content-between text-start">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Terkumpul</span>
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 28px; height: 28px; background-color: rgba(40, 167, 69, 0.1); color: #28a745;">
              <i class="fa-solid fa-wallet" style="font-size: 0.78rem;"></i>
            </div>
          </div>
          <div class="font-serif fs-5 fw-bold" style="color: #28a745;"><?= formatRupiah($terkumpul) ?></div>
        </div>
      </div>

      <div class="col-6 col-md-4">
        <div class="p-3 rounded-4 bg-white border shadow-sm h-100 d-flex flex-column justify-content-between text-start">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Sisa Target</span>
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 28px; height: 28px; background-color: rgba(217, 119, 87, 0.1); color: var(--zp-terracotta);">
              <i class="fa-solid fa-bullseye" style="font-size: 0.78rem;"></i>
            </div>
          </div>
          <div class="font-serif fs-5 fw-bold" style="color: var(--zp-terracotta);"><?= formatRupiah($sisa_target) ?></div>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="p-3 rounded-4 bg-white border shadow-sm h-100 d-flex flex-column justify-content-between text-start">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Sisa Waktu</span>
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 28px; height: 28px; background-color: rgba(45, 42, 38, 0.08); color: var(--zp-deep);">
              <i class="fa-solid fa-hourglass-half" style="font-size: 0.78rem;"></i>
            </div>
          </div>
          <div class="font-serif fs-5 fw-bold">
            <?php
            if ($data['target_date'] === null) {
              echo '<span class="text-muted fs-6">Tanpa Tenggat</span>';
            } elseif ($data['sisa_hari'] < 0) {
              // Merah tegas saat waktu habis
              echo '<span style="color: #dc3545; font-size: 0.95rem;">Waktu Habis</span>';
            } elseif ($data['sisa_hari'] == 0) {
              // Kuning/Emas peringatan untuk hari terakhir
              echo '<span style="color: #ffc107; font-size: 0.95rem;">Hari Ini!</span>';
            } else {
              // Warna deep bawaan jika waktu masih aman berjalan
              echo '<span style="color: var(--zp-deep);">' . $data['sisa_hari'] . ' Hari</span>';
            }
            ?>
          </div>
        </div>
      </div>

    </div>

    <h2 class="font-serif fs-4 mb-3 fw-bold" style="color: var(--zp-deep);">Riwayat Transaksi</h2>
    <div class="card shadow-sm border bg-white rounded-4">
      <ul class="list-group list-group-flush rounded-4">
        <?php
        if (mysqli_num_rows($result_transaksi) > 0) :
          while ($row_trx = mysqli_fetch_assoc($result_transaksi)) :
            $is_masuk = ($row_trx['type'] === 'masuk');
            $warna = $is_masuk ? 'var(--zp-sage)' : 'var(--zp-terracotta)';
            $simbol = $is_masuk ? '+' : '-';
        ?>
            <li class="list-group-item d-flex align-items-center gap-3 py-3 border-secondary border-opacity-10 bg-transparent">
              <span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: <?= $warna ?>;"></span>

              <div class="flex-grow-1">
                <div class="fw-semibold small text-dark"><?= formatTanggal($row_trx['created_at']) ?> &middot; <?= formatTanggal($row_trx['created_at'], 'H:i') ?></div>
              </div>

              <span class="fw-bold small" style="color: <?= $warna ?>"><?= $simbol ?><?= formatRupiah($row_trx['amount']) ?></span>

              <div class="dropdown position-relative">
                <button class="btn btn-sm text-muted px-2 border-0 shadow-none" type="button" data-bs-toggle="dropdown" data-bs-display="static">
                  <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border small dropdown-custom bg-white position-absolute top-100 end-0" style="margin-top: 5px; z-index: 1050;">
                  <li>
                    <button type="button" class="dropdown-item py-1.5"
                      data-bs-toggle="modal"
                      data-bs-target="#modalEditTransaction"
                      data-bs-id="<?= $row_trx['id'] ?>"
                      data-bs-amount="<?= intval($row_trx['amount']) ?>"
                      data-bs-type="<?= $row_trx['type'] ?>">
                      <i class="fa-solid fa-pen me-2 text-muted"></i>Edit
                    </button>
                  </li>
                  <li>
                    <button type="button" class="dropdown-item py-1.5 text-danger"
                      data-bs-toggle="modal"
                      data-bs-target="#modalDeleteTransaction"
                      data-bs-id="<?= $row_trx['id'] ?>"
                      data-bs-amount="<?= formatRupiah($row_trx['amount']) ?>">
                      <i class="fa-solid fa-trash me-2"></i>Hapus
                    </button>
                  </li>
                </ul>
              </div>
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

  <footer class="text-center py-2 mt-4 border-top text-muted small bg-white">
    <div class="container">
      <p>&copy; 2026 ZePocket. All rights reserved.</p>
    </div>
  </footer>

  <div class="modal fade" id="modalTransaction" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm-custom">
      <div class="modal-content border-0 shadow rounded-4 bg-white">
        <div class="modal-header border-bottom border-secondary border-opacity-10">
          <h5 class="modal-title font-serif fw-bold" style="color: var(--zp-deep);">Tambah Transaksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form id="formNewTransaction" action="./koneksi/prosesTambahTransaksi.php" method="POST">
          <div class="modal-body p-4">

            <input type="hidden" name="target_id" value="<?= $id_target ?>">

            <div class="mb-3">
              <label class="form-label fw-semibold small">Jenis Transaksi</label>
              <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="type" id="typeIn" value="masuk" autocomplete="off" checked onchange="setTransactionType('in')">
                <label class="btn btn-outline-success border border-opacity-25 small fw-semibold py-2" for="typeIn" style="font-size: 0.85rem;">
                  <i class="fa-solid fa-arrow-down me-1"></i> Uang Masuk
                </label>

                <input type="radio" class="btn-check" name="type" id="typeOut" value="keluar" autocomplete="off" onchange="setTransactionType('out')">
                <label class="btn btn-outline-danger border border-opacity-25 small fw-semibold py-2" for="typeOut" style="font-size: 0.85rem;">
                  <i class="fa-solid fa-arrow-up me-1"></i> Uang Keluar
                </label>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold small">Jumlah Nominal</label>
              <input type="number" name="amount" class="form-control py-2 shadow-none border" placeholder="Rp 0" min="1" required>
            </div>
          </div>

          <div class="modal-footer border-top border-secondary border-opacity-10">
            <button type="button" class="btn btn-outline-secondary fw-semibold px-3 btn-sm" data-bs-dismiss="modal" style="color: var(--zp-deep);">Batal</button>
            <button type="submit" id="btnConfirmTransaction" class="btn text-white fw-semibold px-4 btn-sm" style="background-color: var(--zp-deep);">Konfirmasi Simpan</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <div class="modal fade" id="modalEditTransaction" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm-custom">
      <div class="modal-content border-0 shadow rounded-4 bg-white">
        <div class="modal-header border-bottom border-secondary border-opacity-10">
          <h5 class="modal-title font-serif fw-bold" style="color: var(--zp-deep);">Edit Transaksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form id="formEditTransaction" action="./koneksi/prosesEditTransaksi.php" method="POST">

          <input type="hidden" name="target_id" value="<?= $id_target ?>">
          <input type="hidden" name="transaksi_id" id="edit_transaksi_id">

          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fw-semibold small">Jenis Transaksi</label>
              <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="type" id="editTypeIn" value="masuk" autocomplete="off">
                <label class="btn btn-outline-success border border-opacity-25 small fw-semibold py-2" for="editTypeIn" style="font-size: 0.85rem;">
                  <i class="fa-solid fa-arrow-down me-1"></i> Uang Masuk
                </label>

                <input type="radio" class="btn-check" name="type" id="editTypeOut" value="keluar" autocomplete="off">
                <label class="btn btn-outline-danger border border-opacity-25 small fw-semibold py-2" for="editTypeOut" style="font-size: 0.85rem;">
                  <i class="fa-solid fa-arrow-up me-1"></i> Uang Keluar
                </label>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold small">Jumlah Nominal</label>
              <input type="number" name="amount" id="editAmount" class="form-control py-2 shadow-none border" placeholder="Rp 0" min="1" required>
            </div>
          </div>

          <div class="modal-footer border-top border-secondary border-opacity-10">
            <button type="button" class="btn btn-outline-secondary fw-semibold px-3 btn-sm" data-bs-dismiss="modal" style="color: var(--zp-deep);">Batal</button>
            <button type="submit" class="btn text-white fw-semibold px-4 btn-sm" style="background-color: var(--zp-deep);">Simpan Perubahan</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <div class="modal fade" id="modalEditTarget" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow rounded-4 bg-white">
        <div class="modal-header border-bottom border-secondary border-opacity-10">
          <h5 class="modal-title font-serif fw-bold" style="color: var(--zp-deep);">Edit Target Celengan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form id="formEditTarget" action="./koneksi/prosesEditTarget.php" method="POST" enctype="multipart/form-data">

          <div class="modal-body p-4">
            <input type="hidden" name="target_id" value="<?= $data['id'] ?>">

            <div class="mb-3">
              <label class="form-label fw-semibold small">Nama Celengan</label>
              <input type="text" name="name" class="form-control py-2 shadow-none border" value="<?= htmlspecialchars($data['name']) ?>" required>
            </div>
            <div class="row g-2">
              <div class="col-6 mb-3">
                <label class="form-label fw-semibold small">Target Jumlah</label>
                <input type="number" name="target_amount" class="form-control py-2 shadow-none border" value="<?= intval($data['target_amount']) ?>" required>
              </div>
              <div class="col-6 mb-3">
                <label class="form-label fw-semibold small">Target Tanggal</label>
                <input type="date" name="target_date" class="form-control py-2 shadow-none border" value="<?= $data['target_date'] ?>">
              </div>
            </div>
            <div class="mb-1">
              <label class="form-label fw-semibold small">Ganti Gambar Celengan</label>
              <input type="file" name="img_url" class="form-control py-2 shadow-none border" accept="image/*">
              <div class="form-text text-muted" style="font-size: 0.78rem;">Kosongkan bila gambar utama tidak ingin diubah.</div>
            </div>
          </div>

          <div class="modal-footer border-top border-secondary border-opacity-10">
            <button type="button" class="btn btn-outline-secondary fw-semibold px-3" data-bs-dismiss="modal" style="color: var(--zp-deep);">Batal</button>
            <button type="submit" class="btn text-white fw-semibold px-4" style="background-color: var(--zp-deep);">Simpan Perubahan</button>
          </div>

        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalDeleteTransaction" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content rounded-4 border-0 shadow bg-white">

        <form action="./koneksi/prosesHapusTransaksi.php" method="POST">

          <input type="hidden" name="target_id" value="<?= $id_target ?>">
          <input type="hidden" name="transaksi_id" id="delete_transaksi_id">

          <div class="modal-body p-4 text-center">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 56px; height: 56px; background-color: rgba(217,119,87,0.1); color: var(--zp-terracotta);">
              <i class="fa-solid fa-trash fs-4"></i>
            </div>
            <h5 class="font-serif fw-bold mb-2" style="color: var(--zp-deep);">Hapus Riwayat Transaksi?</h5>
            <p class="text-muted small mb-4">Apakah Anda yakin ingin menghapus transaksi sebesar <strong class="text-dark" id="delete_trx_amount"></strong>? Tindakan ini permanen.</p>

            <div class="d-flex gap-2">
              <button type="button" class="btn btn-light w-100 fw-semibold py-2 small border text-muted" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn text-white w-100 fw-semibold py-2 small" style="background-color: var(--zp-terracotta);">Ya, Hapus</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalDeleteTarget" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content rounded-4 border-0 shadow bg-white">
        <form action="./koneksi/prosesHapusTarget.php" method="POST">
          <div class="modal-body p-4 text-center">
            <input type="hidden" name="target_id" value="<?= $data['id'] ?>">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 56px; height: 56px; background-color: rgba(217,119,87,0.1); color: var(--zp-terracotta);">
              <i class="fa-solid fa-trash fs-4"></i>
            </div>
            <h5 class="font-serif fw-bold mb-2" style="color: var(--zp-deep);">Hapus Target Celengan?</h5>
            <p class="text-muted small mb-4">Anda akan menghapus <strong class="text-dark">"<?= htmlspecialchars($data['name']) ?>"</strong> beserta riwayat transaksi permanen.</p>

            <div class="d-flex gap-2">
              <button type="button" class="btn btn-light w-100 fw-semibold py-2 small border text-muted" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn text-white w-100 fw-semibold py-2 small" style="background-color: var(--zp-terracotta);">Ya, Hapus</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalLogout" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content rounded-4 border-0 shadow bg-white">
        <div class="modal-body p-4 text-center">
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 56px; height: 56px; background-color: rgba(217,119,87,0.1); color: var(--zp-terracotta);">
            <i class="fa-solid fa-right-from-bracket fs-4"></i>
          </div>
          <h5 class="font-serif fw-bold mb-2" style="color: var(--zp-deep);">Konfirmasi Keluar</h5>
          <p class="text-muted small mb-4">Apakah Anda yakin ingin keluar dari akun <strong class="text-dark">ZePocket</strong> Anda?</p>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-light w-100 fw-semibold py-2 small border text-muted" data-bs-dismiss="modal">Batal</button>
            <a href="./koneksi/prosesLogout.php" class="btn text-white w-100 fw-semibold py-2 small" style="background-color: var(--zp-terracotta);">Keluar</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
  <script>
    function setTransactionType(type) {
      const btn = document.getElementById('btnConfirmTransaction');
      if (type === 'in') {
        btn.style.backgroundColor = 'var(--zp-deep)';
        btn.textContent = 'Konfirmasi Uang Masuk';
      } else {
        btn.style.backgroundColor = 'var(--zp-terracotta)';
        btn.textContent = 'Konfirmasi Uang Keluar';
      }
    }

    const modalEditTrx = document.getElementById('modalEditTransaction');
    if (modalEditTrx) {
      modalEditTrx.addEventListener('show.bs.modal', function(event) {
        // Tombol Edit spesifik yang diklik oleh user
        const button = event.relatedTarget;

        // Ekstrak data dari atribut data-bs-*
        const idTrx = button.getAttribute('data-bs-id');
        const amountTrx = button.getAttribute('data-bs-amount');
        const typeTrx = button.getAttribute('data-bs-type');

        // Masukkan data ke dalam inputan modal edit
        document.getElementById('edit_transaksi_id').value = idTrx;
        document.getElementById('editAmount').value = amountTrx;

        // Atur posisi check radio button sesuai jenis transaksi asli
        if (typeTrx === 'masuk') {
          document.getElementById('editTypeIn').checked = true;
        } else {
          document.getElementById('editTypeOut').checked = true;
        }
      });
    }

    const modalDeleteTrx = document.getElementById('modalDeleteTransaction');
    if (modalDeleteTrx) {
      modalDeleteTrx.addEventListener('show.bs.modal', function(event) {
        // Tombol Hapus spesifik yang diklik
        const button = event.relatedTarget;

        // Ambil data ID dan Nominal dari atribut data-bs-*
        const idTrx = button.getAttribute('data-bs-id');
        const amountTrx = button.getAttribute('data-bs-amount');

        // Taruh datanya ke dalam form modal hapus
        document.getElementById('delete_transaksi_id').value = idTrx;
        document.getElementById('delete_trx_amount').textContent = amountTrx;
      });
    }
  </script>
</body>

</html>