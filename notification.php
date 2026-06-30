<?php
include './koneksi/koneksi.php';
include './koneksi/ambilDataNotif.php';
include './utils/custom_function.php';

$title = 'Notification';

include './partials/header.php';
?>
<div class="container py-5" style="max-width: 600px; min-height: calc(100vh - 165px);">
    <h4 class="font-serif fw-bold mb-4" style="color: var(--zp-deep);">Inbox Notifikasi</h4>

    <div class="d-flex flex-column gap-3">
        <?php
        if (mysqli_num_rows($notif_res) == 0) {
            echo "<div class='text-center border rounded-4 bg-white p-5 text-muted small'>Belum ada notifikasi masuk untuk Anda.</div>";
        }

        while ($notif = mysqli_fetch_assoc($notif_res)) :
            // Tandai background abu-abu tipis jika notifikasi sudah dibaca
            $bg_class = ($notif['is_read'] == 1) ? 'bg-white text-muted' : 'bg-white border-start border-3 border-primary';
        ?>
            <div class="card border-0 shadow-sm rounded-4 p-3 <?= $bg_class ?>">
                <div class="d-flex align-items-start gap-3">
                    <div
                        class="rounded-circle bg-light d-flex justify-content-center align-items-center flex-shrink-0 text-muted"
                        style="width: 40px; height: 40px;">
                        <i class="fa-solid <?= $notif['type'] === 'invitation' ? 'fa-envelope-open-text text-warning' : 'fa-wallet text-success' ?>"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small mb-1 text-dark"><?= $notif['message'] ?></div>
                        <div class="text-muted mb-2" style="font-size: 0.72rem;"><i class="fa-regular fa-clock me-1"></i><?= formatTanggal($notif['created_at'], 'd M Y, H:i') ?></div>

                        <?php if ($notif['type'] === 'invitation' && $notif['invite_status'] === 'pending') : ?>

                            <div class="d-flex gap-2 mt-2">
                                <form action="./koneksi/prosesUndangan.php" method="POST" class="d-inline">
                                    <input type="hidden" name="target_id" value="<?= $notif['target_id'] ?>">
                                    <input type="hidden" name="notif_id" value="<?= $notif['id'] ?>">
                                    <button type="submit" name="action" value="accept" class="btn btn-success btn-sm font-semibold rounded-3 px-3 py-1 text-white shadow-none" style="font-size: 0.78rem;">
                                        Terima
                                    </button>
                                </form>

                                <button type="button"
                                    class="btn btn-outline-danger btn-sm font-semibold rounded-3 px-3 py-1 shadow-none"
                                    style="font-size: 0.78rem;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDeclineNotif"
                                    data-bs-target-id="<?= $notif['target_id'] ?>"
                                    data-bs-notif-id="<?= $notif['id'] ?>">
                                    Tolak
                                </button>
                            </div>

                        <?php elseif ($notif['type'] === 'invitation' && $notif['invite_status'] === 'accepted') : ?>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small" style="font-size: 0.7rem;">
                                <i class="fa-solid fa-check me-1"></i>Anda telah bergabung
                            </span>

                        <?php elseif ($notif['type'] === 'invitation' && $notif['invite_status'] === null) : ?>
                            <span class="badge bg-secondary bg-opacity-10 text-muted rounded-pill px-2 py-1 small" style="font-size: 0.7rem;">
                                Undangan Ditolak / Dibatalkan
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php
        endwhile;

        // Sekaligus ubah semua status notifikasi menjadi sudah dibaca (is_read = 1) saat membuka halaman ini
        updateIsReadNotification($conn, $user_id)
        ?>
    </div>
</div>

<div class="modal fade" id="modalDeclineNotif" tabindex="-1" aria-labelledby="modalDeclineNotifLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow bg-white">
            <div class="modal-body p-4 text-center">

                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 56px; height: 56px; background-color: rgba(217,119,87,0.1); color: var(--zp-terracotta);">
                    <i class="fa-solid fa-envelope-circle-check fs-4"></i>
                </div>

                <h5 class="font-serif fw-bold mb-2" id="modalDeclineNotifLabel" style="color: var(--zp-deep);">Tolak Undangan</h5>
                <p class="text-muted small mb-4">Apakah Anda yakin ingin menolak undangan kolaborasi celengan ini?</p>

                <form action="./koneksi/prosesUndangan.php" method="POST">
                    <input type="hidden" name="action" value="decline">
                    <input type="hidden" name="target_id" id="declineTargetId">
                    <input type="hidden" name="notif_id" id="declineNotifId">

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 fw-semibold rounded-3 btn-sm py-2 text-muted" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-white w-100 fw-semibold rounded-3 btn-sm py-2" style="background-color: var(--zp-terracotta);">Tolak</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    const modalDeclineNotif = document.getElementById('modalDeclineNotif');
    if (modalDeclineNotif) {
        modalDeclineNotif.addEventListener('show.bs.modal', function(event) {
            // Tombol pemicu yang diklik
            const button = event.relatedTarget;

            // Ambil data atribut data-bs-*
            const targetId = button.getAttribute('data-bs-target-id');
            const notifId = button.getAttribute('data-bs-notif-id');

            // Tanam nilai ke dalam hidden input form di dalam modal
            document.getElementById('declineTargetId').value = targetId;
            document.getElementById('declineNotifId').value = notifId;
        });
    }
</script>

<?php include './partials/footer.php'; ?>