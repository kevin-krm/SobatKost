<?php
define('APP_PATH', __DIR__ . '/app');

$url = $_GET['url'] ?? '';
$id  = $_GET['id'] ?? null;

// Helper load controller
function controller($name)
{
    $file = APP_PATH . "/controller/{$name}.php";

    if (!file_exists($file)) {
        http_response_code(404);
        die("Controller {$name} tidak ditemukan");
    }
    require_once $file;
    return new $name();
}

switch ($url) {
    // HOME
    case '':
    case 'home':
        controller('HomeController')->index();
        break;

    // AUTH
    case 'login':
        controller('AuthController')->login();
        break;

    case 'login/process':
        controller('AuthController')->loginProcess();
        break;

    case 'logout':
        controller('AuthController')->logout();
        break;

    // PENGGUNA
    case 'pengguna':
    case 'pengguna/index':
        controller('PenggunaController')->index();
        break;

    case 'pengguna/create':
        controller('PenggunaController')->create();
        break;

    case 'pengguna/store':
        controller('PenggunaController')->store();
        break;

    case 'pengguna/edit':
        if (!$id) {
            die("ID pengguna wajib diisi");
        }
        controller('PenggunaController')->edit($id);
        break;

    case 'pengguna/update':
        if (!$id) {
            die("ID pengguna wajib diisi");
        }
        controller('PenggunaController')->update($id);
        break;

    case 'pengguna/delete':
        if (!$id) {
            die("ID pengguna wajib diisi");
        }
        controller('PenggunaController')->delete($id);
        break;

    // KAMAR
    case 'kamar':
    case 'kamar/index':
        controller('KamarController')->index();
        break;

    case 'kamar/create':
        controller('KamarController')->create();
        break;

    case 'kamar/store':
        controller('KamarController')->store();
        break;

    case 'kamar/edit':
        if (!$id) {
            die("ID kamar wajib diisi");
        }
        controller('KamarController')->edit($id);
        break;

    case 'kamar/update':
        if (!$id) {
            die("ID kamar wajib diisi");
        }
        controller('KamarController')->update($id);
        break;

    case 'kamar/delete':
        if (!$id) {
            die("ID kamar wajib diisi");
        }
        controller('KamarController')->delete($id);
        break;

    // DEFAULT (404)
    default:
        http_response_code(404);
        echo "<h3>404 - Halaman tidak ditemukan</h3>";
        break;
}