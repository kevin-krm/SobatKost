<?php
// Mendefinisikan path absolut ke folder app agar pemanggilan file konsisten
define('APP_PATH', __DIR__ . '/app');

// 1. Ambil URL dan bersihkan
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
$url = filter_var($url, FILTER_SANITIZE_URL);

// 2. Pecah URL menjadi bagian-bagian
$urlParts = explode('/', $url);

// 3. Tentukan Controller
$controllerName = isset($urlParts[0]) && $urlParts[0] !== '' ? ucfirst($urlParts[0]) . 'Controller' : 'HomeController';

// 4. Tentukan Method
$methodName = isset($urlParts[1]) ? $urlParts[1] : 'index';

// 5. Lokasi file controller berdasarkan struktur folder app/controller/
$controllerFile = APP_PATH . '/controller/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;

    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        if (method_exists($controller, $methodName)) {
            // Jalankan method yang diminta
            $params = array_slice($urlParts, 2);
            call_user_func_array([$controller, $methodName], $params);
        } else {
            echo "Aksi <b>$methodName</b> tidak ditemukan.";
        }
    } else {
        echo "Kelas <b>$controllerName</b> tidak ditemukan.";
    }
} else {
    echo "Halaman tidak ditemukan.";
}