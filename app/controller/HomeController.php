<?php
class HomeController {
    public function index() {
        // Konten utama dashboard (belum diterapkan)
        $contentView = APP_PATH . '/view/layout/dashboard_home.php';
        require_once APP_PATH . '/view/index.php'; // Memanggil master layout
    }
}