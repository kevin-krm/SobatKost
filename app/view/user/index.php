<?php
require_once APP_PATH . '/view/user/layout/header.php';
require_once APP_PATH . '/view/user/layout/sidebar.php';

if (isset($contentView) && file_exists($contentView)) {
    require_once $contentView;
} else {
    // TAMPILAN DASHBOARD DEFAULT + PENGUMUMAN AMAN (Bypass Model)
    require_once APP_PATH . '/dao/PDOUtil.php';
    $link = PDOUtil::createConnection();
    $stmt = $link->query("SELECT * FROM pengumuman ORDER BY tanggal_siar DESC LIMIT 10");
    $pengumumanList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="mt-4">
        <h2 class="fw-bold mb-1">Selamat Datang di SobatKost</h2>
        <p class="text-muted">Berikut adalah informasi dan pengumuman terbaru dari pengelola kost.</p>

        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-megaphone-fill text-warning me-2"></i> Papan Pengumuman
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($pengumumanList)): ?>
                            <div class="p-5 text-center text-muted">Belum ada pengumuman saat ini.</div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($pengumumanList as $p): ?>
                                    <div class="list-group-item p-4 bg-white" style="transition: none;">
                                        <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold text-primary">
                                                <?= htmlspecialchars($p['judul']) ?>
                                            </h6>
                                            <small class="text-muted bg-light px-2 py-1 rounded">
                                                <?= date('d M Y', strtotime($p['tanggal_siar'])) ?>
                                            </small>
                                        </div>
                                        <p class="mb-0 text-secondary" style="white-space: pre-line;">
                                            <?= htmlspecialchars($p['konten']) ?>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
}
require_once APP_PATH . '/view/user/layout/footer.php';
?>