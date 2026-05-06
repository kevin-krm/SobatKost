<?php
define('APP_PATH', __DIR__ . '/app');

$url = isset($_GET['url']) ? $_GET['url'] : '';
$id  = isset($_GET['id']) ? $_GET['id'] : null;

switch ($url) {

    // HOME
    case '':
    case 'home':
        require_once APP_PATH . '/controller/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    // LOGIN
    case 'login':
        require_once APP_PATH . '/controller/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case 'login/process':
        require_once APP_PATH . '/controller/AuthController.php';
        $controller = new AuthController();
        $controller->loginProcess();
        break;

    case 'logout':
        require_once APP_PATH . '/controller/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    // PENGGUNA
    case 'pengguna':
    case 'pengguna/index':
        require_once APP_PATH . '/controller/PenggunaController.php';
        $controller = new PenggunaController();
        $controller->index();
        break;

    case 'pengguna/create':
        require_once APP_PATH . '/controller/PenggunaController.php';
        $controller = new PenggunaController();
        $controller->create();
        break;

    case 'pengguna/store':
        require_once APP_PATH . '/controller/PenggunaController.php';
        $controller = new PenggunaController();
        $controller->store();
        break;

    case 'pengguna/edit':
        require_once APP_PATH . '/controller/PenggunaController.php';
        $controller = new PenggunaController();
        $controller->edit($id);
        break;

    case 'pengguna/update':
        require_once APP_PATH . '/controller/PenggunaController.php';
        $controller = new PenggunaController();
        $controller->update($id);
        break;

    case 'pengguna/delete':
        require_once APP_PATH . '/controller/PenggunaController.php';
        $controller = new PenggunaController();
        $controller->delete($id);
        break;

    // KAMAR
    case 'kamar':
    case 'kamar/index':
        require_once APP_PATH . '/controller/KamarController.php';
        $controller = new KamarController();
        $controller->index();
        break;

    case 'kamar/create':
        require_once APP_PATH . '/controller/KamarController.php';
        $controller = new KamarController();
        $controller->create();
        break;

    case 'kamar/store':
        require_once APP_PATH . '/controller/KamarController.php';
        $controller = new KamarController();
        $controller->store();
        break;

    case 'kamar/edit':
        require_once APP_PATH . '/controller/KamarController.php';
        $controller = new KamarController();
        $controller->edit($id);
        break;

    case 'kamar/update':
        require_once APP_PATH . '/controller/KamarController.php';
        $controller = new KamarController();
        $controller->update($id);
        break;

    case 'kamar/delete':
        require_once APP_PATH . '/controller/KamarController.php';
        $controller = new KamarController();
        $controller->delete($id);
        break;

    // DEFAULT
    default:
        echo '404 - Halaman tidak ditemukan';
        break;
}