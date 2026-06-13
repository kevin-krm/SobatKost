<?php
$currentUrl = $_SERVER['REQUEST_URI'];
$userId = $_SESSION['user']['id'] ?? null;

if ($userId) {
    require_once APP_PATH . '/dao/TagihanDao.php';
    require_once APP_PATH . '/dao/KontrakDao.php';
    require_once APP_PATH . '/model/TagihanReminderService.php';

    // Clear existing notifications to keep it updated with database changes (e.g. if paid)
    unset($_SESSION['reminder_notifications'][$userId]);

    $tagihanDao = new TagihanDao();
    $kontrakDao = new KontrakDao();
    $kontrakList = $kontrakDao->getKontrakByPengguna($userId);

    $dueTagihanList = [];
    foreach ($kontrakList as $kontrak) {
        $tagihan_kontrak = $tagihanDao->getTagihanByKontrakId($kontrak->getIdKontrak());
        foreach ($tagihan_kontrak as $tagihan) {
            if ($tagihan->getStatusTagihan() === 'Belum Lunas') {
                $dueDate = strtotime($tagihan->getTanggalJatuhTempo());
                $limitDate = strtotime('+7 days');
                if ($dueDate <= $limitDate) {
                    $dueTagihanList[] = $tagihan;
                }
            }
        }
    }

    if (!empty($dueTagihanList)) {
        $reminderService = new TagihanReminderService();
        $reminderService->kirimReminder($dueTagihanList);
    }
}

$reminderNotifications = $userId && isset($_SESSION['reminder_notifications'][$userId])
    ? array_values($_SESSION['reminder_notifications'][$userId])
    : [];

function isActive($path, $currentUrl)
{
    return strpos($currentUrl, $path) !== false ? 'active' : '';
}
?>

<div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <i class="bi bi-house-heart-fill"></i>
        SobatKost
    </div>

    <div class="list-group list-group-flush">
        <a href="/SobatKost/user" class="list-group-item list-group-item-action <?= $currentUrl == '/SobatKost/user' ? 'active' : '' ?>">
            <i class="bi bi-megaphone me-2"></i> Pengumuman
        </a>

        <a href="/SobatKost/user/tagihan" class="list-group-item list-group-item-action <?= isActive('user/tagihan', $currentUrl) ?>">
            <i class="bi bi-receipt me-2"></i> Tagihan
        </a>

        <a href="/SobatKost/user/komplain" class="list-group-item list-group-item-action <?= isActive('user/komplain', $currentUrl) ?>">
            <i class="bi bi-tools me-2"></i> Tiket Komplain
        </a>

        <a href="/SobatKost/user/aturan" class="list-group-item list-group-item-action <?= isActive('user/aturan', $currentUrl) ?>">
            <i class="bi bi-journal-text me-2"></i> Aturan Kost
        </a>

        <a href="/SobatKost/user/about" class="list-group-item list-group-item-action <?= isActive('user/about', $currentUrl) ?>">
            <i class="bi bi-person me-2"></i> Tentang Saya
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="/SobatKost/logout" class="btn btn-danger w-100">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<!-- CONTENT AREA -->
<div id="page-content-wrapper">
    <!-- HEADER-->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top shadow-sm">
        <div class="container-fluid">

            <!-- TOGGLE -->
            <button class="btn btn-light me-3" id="menu-toggle">
                <i class="bi bi-list"></i>
            </button>

            <h5 class="text-white mb-0">
                SobatKost
            </h5>

            <div class="ms-auto d-flex align-items-center text-white">
                <!-- NOTIFIKASI -->
                <div class="dropdown me-3">
                    <button
                            class="btn  position-relative text-white"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                    >
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= count($reminderNotifications) ?>
                        </span>
                    </button>

                    <!-- DROPDOWN -->
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-0" style="width: 320px;">
                        <li class="dropdown-header fw-bold py-3 px-3 border-bottom">
                            Notifikasi
                        </li>

                        <?php if (empty($reminderNotifications)): ?>
                            <li class="text-center py-5 px-3">
                                <i class="bi bi-bell-slash fs-1 text-muted"></i>
                                <p class="text-muted mt-3 mb-0">
                                    Belum ada notifikasi
                                </p>
                            </li>
                        <?php else: ?>
                            <?php foreach ($reminderNotifications as $notif): ?>
                                <li class="px-3 py-3 border-bottom">
                                    <div class="fw-bold small mb-1">
                                        <?= htmlspecialchars($notif['judul']) ?>
                                    </div>
                                    <div class="small text-muted">
                                        <?= htmlspecialchars($notif['pesan']) ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="ms-auto d-flex align-items-center text-white">
                <span class="me-3 d-none d-md-inline">
                    Hello, <?= $_SESSION['user']['nama'] ?? 'Pengguna'; ?>
                </span>
                    <i class="bi bi-person-circle fs-4"></i>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="container-fluid p-4 content-area ">
