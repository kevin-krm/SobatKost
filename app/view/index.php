<?php
require_once APP_PATH . '/view/layout/header.php';
require_once APP_PATH . '/view/layout/sidebar.php';

if (isset($contentView) && file_exists($contentView)) {
    require_once $contentView;
} else {
    // Tampilan default
    echo '<div class="mt-4">
            <h2>Selamat Datang di SobatKost Admin</h2>
            <p class="text-muted">Pilih menu di samping untuk mulai mengelola.</p>
          </div>';
}
require_once APP_PATH . '/view/layout/footer.php';
?>