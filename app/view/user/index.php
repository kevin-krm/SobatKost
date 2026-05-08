<?php
require_once APP_PATH . '/view/user/layout/header.php';
require_once APP_PATH . '/view/user/layout/sidebar.php';

if (isset($contentView) && file_exists($contentView)) {
    require_once $contentView;
} else {
    // Tampilan default
    echo '<div class="mt-4">
            <h2>Selamat Datang di SobatKost</h2>
            <p class="text-muted">Pilih menu di samping untuk mulai mendapatkan informasi</p>
          </div>';
}
require_once APP_PATH . '/view/user/layout/footer.php';
?>