<div class="mb-4">
    <h2 class="fw-bold mb-0">E-Rules / Aturan Kost</h2>
    <p class="text-muted">Daftar tata tertib dan peraturan yang wajib dipatuhi oleh seluruh penyewa kost.</p>
</div>

<div class="row">
    <?php if (empty($aturanList)) : ?>
        <div class="col-12 text-center mt-5 text-muted">Belum ada aturan yang dicatat oleh pengelola.</div>
    <?php else : ?>
        <div class="col-md-12">
            <div class="accordion shadow-sm" id="accordionAturan">
                <?php foreach ($aturanList as $index => $a) : ?>
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="heading<?= $index ?>">
                            <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?> fw-bold text-dark bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $index ?>">
                                <i class="bi bi-bookmark-star text-primary me-2"></i> <?= htmlspecialchars($a->getJudulAturan() ?? '') ?>
                            </button>
                        </h2>
                        <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $index ?>" data-bs-parent="#accordionAturan">
                            <div class="accordion-body text-secondary">
                                <?= nl2br(htmlspecialchars($a->getDeskripsiAturan() ?? '')) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>