<?php
require_once APP_PATH . '/middleware/AuthMiddleware.php';
class HomeController {
    public function __construct()
    {
        AuthMiddleware::check();
    }

    public function index() {
        // Konten utama dashboard (belum diterapkan)
        $contentView = APP_PATH . '/view/layout/dashboard_home.php';
        require_once APP_PATH . '/view/index.php'; // Memanggil master layout
    }
}