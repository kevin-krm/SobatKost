<?php
session_start();

define('APP_PATH', __DIR__ . '/app');

require_once APP_PATH . '/middleware/Auth.php';
require_once __DIR__ . '/routes/web.php';

$url = $_GET['url'] ?? '';
$id  = $_GET['id'] ?? null;

//LOAD CONTROLLER
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

Route::handle($url);

switch ($url) {

    //DEFAULT
    case '':
        header('Location: index.php?url=login');
        exit;

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

    //HOME
    case 'home':
        controller('HomeController')->index();
        break;

    // ADMIN MENU
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
    case 'kamar/updateStatus':
        if (!$id) {
            die("ID kamar wajib diisi");
        }
        controller('KamarController')->updateStatus($id);
        break;
    case 'kamar/delete':
        if (!$id) {
            die("ID kamar wajib diisi");
        }
        controller('KamarController')->delete($id);
        break;

    case 'komplain':
    case 'komplain/index':
        controller('KomplainController')->index();
        break;
    case 'komplain/create':
        controller('KomplainController')->create();
        break;
    case 'komplain/store':
        controller('KomplainController')->store();
        break;
    case 'komplain/edit':
        if (!$id) {
            die("ID komplain wajib diisi");
        }
        controller('KomplainController')->edit($id);
        break;
    case 'komplain/update':
        if (!$id) {
            die("ID komplain wajib diisi");
        }
        controller('KomplainController')->update($id);
        break;
    case 'komplain/updateStatus':
        if (!$id) {
            die("ID komplain wajib diisi");
        }
        controller('KomplainController')->updateStatus($id);
        break;
    case 'komplain/delete':
        if (!$id) {
            die("ID komplain wajib diisi");
        }
        controller('KomplainController')->delete($id);
        break;

    case 'inventaris':
    case 'inventaris/index':
        controller('InventarisController')->index();
        break;
    case 'inventaris/create':
        controller('InventarisController')->create();
        break;
    case 'inventaris/store':
        controller('InventarisController')->store();
        break;
    case 'inventaris/updateStatus':
        if (!$id) {
            die("ID inventaris wajib diisi");
        }
        controller('InventarisController')->updateStatus($id);
        break;
    case 'inventaris/edit':
        if (!$id) {
            die("ID inventaris wajib diisi");
        }
        controller('InventarisController')->edit($id);
        break;
    case 'inventaris/update':
        if (!$id) {
            die("ID inventaris wajib diisi");
        }

        controller('InventarisController')->update($id);
        break;
    case 'inventaris/delete':
        if (!$id) {
            die("ID inventaris wajib diisi");
        }

        controller('InventarisController')->delete($id);
        break;

    case 'keuangan':
    case 'keuangan/index':
        controller('KeuanganController')->index();
        break;
    case 'keuangan/create':
        controller('KeuanganController')->create();
        break;
    case 'keuangan/store':
        controller('KeuanganController')->store();
        break;
    case 'keuangan/edit':
        if (!$id) {
            die("ID biaya wajib diisi");
        }
        controller('KeuanganController')->edit($id);
        break;
    case 'keuangan/update':
        if (!$id) {
            die("ID biaya wajib diisi");
        }
        controller('KeuanganController')->update($id);
        break;
    case 'keuangan/delete':
        if (!$id) {
            die("ID biaya wajib diisi");
        }
        controller('KeuanganController')->delete($id);
        break;

    case 'pengumuman':
    case 'pengumuman/index':
        controller('PengumumanController')->index();
        break;
    case 'pengumuman/create':
        controller('PengumumanController')->create();
        break;
    case 'pengumuman/store':
        controller('PengumumanController')->store();
        break;
    case 'pengumuman/edit':
        if (!$id) die("ID wajib diisi");
        controller('PengumumanController')->edit($id);
        break;
    case 'pengumuman/update':
        if (!$id) die("ID wajib diisi");
        controller('PengumumanController')->update($id);
        break;
    case 'pengumuman/delete':
        if (!$id) die("ID wajib diisi");
        controller('PengumumanController')->delete($id);
        break;

    case 'aturan':
    case 'aturan/index':
        controller('AturanKostController')->index();
        break;
    case 'aturan/create':
        controller('AturanKostController')->create();
        break;
    case 'aturan/store':
        controller('AturanKostController')->store();
        break;
    case 'aturan/edit':
        if (!$id) die("ID wajib diisi");
        controller('AturanKostController')->edit($id);
        break;
    case 'aturan/update':
        if (!$id) die("ID wajib diisi");
        controller('AturanKostController')->update($id);
        break;
    case 'aturan/delete':
        if (!$id) die("ID wajib diisi");
        controller('AturanKostController')->delete($id);
        break;

    // PENYEWA (USER)
    case 'user':
    case 'user/index':
        require_once APP_PATH . '/view/user/index.php';
        break;

    case 'user/komplain':
        controller('KomplainController')->userIndex();
        break;

    case 'user/pengumuman':
        controller('PengumumanController')->userIndex();
        break;

    case 'user/aturan':
        controller('AturanKostController')->userIndex();
        break;

    default:
        http_response_code(404);
        echo "<h3>404 - Halaman tidak ditemukan</h3>";
        break;
}