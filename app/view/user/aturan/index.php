<div class="mb-4">
    <h2 class="fw-bold mb-0">E-Rules / Aturan Kost</h2>
    <p class="text-muted">Daftar tata tertib dan peraturan yang wajib dipatuhi oleh seluruh penyewa kost.</p>
</div>

<div class="row">
    <?php if (empty($aturanList)) : ?>
        <div class="col-12 text-center mt-5 text-muted">Belum ada aturan yang dicatat oleh pengelola.</div>
    <?php else : ?>
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <?php foreach ($aturanList as $index => $a) : ?>
                        <div class="p-4 bg-white <?= $index !== count($aturanList) - 1 ? 'border-bottom' : '' ?>">
                            <h5 class="fw-bold text-dark mb-3">
                                <i class="bi bi-bookmark-star-fill text-primary me-2"></i>
                                <?= htmlspecialchars($a->getJudulAturan() ?? '') ?>
                            </h5>
                            <p class="text-secondary mb-0 lh-lg" style="white-space: pre-line;">
                                <?= htmlspecialchars($a->getDeskripsiAturan() ?? '') ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>