<div class="mb-4">
    <h2 class="fw-bold mb-0">Papan Pengumuman</h2>
    <p class="text-muted">Informasi terbaru dari pengelola kost.</p>
</div>

<div class="row">
    <?php if (empty($pengumumanList)) : ?>
        <div class="col-12 text-center mt-5 text-muted">Belum ada pengumuman baru.</div>
    <?php else : ?>
        <?php foreach ($pengumumanList as $p) : ?>
            <div class="col-md-12 mb-3">
                <div class="card border-0 shadow-sm border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold text-primary mb-0">
                                <i class="bi bi-info-circle me-2"></i> <?= htmlspecialchars($p->getJudul() ?? '') ?>
                            </h5>
                            <small class="text-muted"><?= htmlspecialchars($p->getCreatedAt() ?? '') ?></small>
                        </div>
                        <p class="mb-0 text-dark"><?= nl2br(htmlspecialchars($p->getKonten() ?? '')) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>