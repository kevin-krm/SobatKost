<?php
require_once APP_PATH . '/middleware/Auth.php';

class Route
{
    public static function handle($url)
    {
        //PUBLIC ROUTES
        $publicRoutes = [
            '',
            'login',
            'login/process',
            'logout'
        ];
        if (!in_array($url, $publicRoutes) && !Auth::check()) {
            header('Location: index.php?url=login');
            exit;
        }

        /*
        | ROUTE ROLE ACCESS:
        | 1 = Owner
        | 2 = Penjaga
        | 3 = Penyewa
        */
        $routeRoles = [
            'pengguna' => [1, 2],
            'pengguna/index' => [1, 2],
            'pengguna/create' => [1, 2],
            'pengguna/store' => [1, 2],
            'pengguna/detail' => [1, 2],
            'pengguna/edit' => [1, 2],
            'pengguna/update' => [1, 2],
            'pengguna/delete' => [1, 2],

            'kontrak' => [1, 2],
            'kontrak/index' => [1, 2],
            'kontrak/create' => [1, 2],
            'kontrak/store' => [1, 2],
            'kontrak/edit' => [1, 2],
            'kontrak/update' => [1, 2],
            'kontrak/delete' => [1, 2],

            'kamar' => [1, 2],
            'kamar/index' => [1, 2],
            'kamar/create' => [1, 2],
            'kamar/store' => [1, 2],
            'kamar/edit' => [1, 2],
            'kamar/update' => [1, 2],
            'kamar/updateStatus' => [1, 2],
            'kamar/delete' => [1, 2],

            'komplain' => [1, 2],
            'komplain/index' => [1, 2],
            'komplain/create' => [1, 2, 3],
            'komplain/store' => [1, 2, 3],
            'komplain/edit' => [1, 2],
            'komplain/update' => [1, 2],
            'komplain/updateStatus' => [1, 2],
            'komplain/delete' => [1, 2],

            'inventaris' => [1, 2],
            'inventaris/index' => [1, 2],
            'inventaris/create' => [1, 2],
            'inventaris/store' => [1, 2],
            'inventaris/edit' => [1, 2],
            'inventaris/update' => [1, 2],
            'inventaris/updateStatus' => [1, 2],
            'inventaris/delete' => [1, 2],

            'keuangan' => [1, 2],
            'keuangan/index' => [1, 2],
            'keuangan/create' => [1, 2],
            'keuangan/store' => [1, 2],
            'keuangan/edit' => [1, 2],
            'keuangan/update' => [1, 2],
            'keuangan/delete' => [1, 2],

            'tagihan' => [1, 2],
            'tagihan/index' => [1, 2],
            'tagihan/create' => [1, 2],
            'tagihan/store' => [1, 2],
            'tagihan/detail' => [1, 2, 3],
            'tagihan/edit' => [1, 2],
            'tagihan/update' => [1, 2],
            'tagihan/delete' => [1, 2],
            'tagihan/dashboard' => [1, 2],

            'pembayaran' => [1, 2],
            'pembayaran/index' => [1, 2],
            'pembayaran/upload' => [1, 2, 3],
            'pembayaran/store' => [1, 2, 3],
            'pembayaran/detail' => [1, 2, 3],
            'pembayaran/verify' => [1, 2],
            'pembayaran/reject' => [1, 2],

            'pengumuman' => [1, 2],
            'pengumuman/index' => [1, 2],
            'pengumuman/create' => [1, 2],
            'pengumuman/store' => [1, 2],
            'pengumuman/edit' => [1, 2],
            'pengumuman/update' => [1, 2],
            'pengumuman/delete' => [1, 2],

            'aturan' => [1, 2],
            'aturan/index' => [1, 2],
            'aturan/create' => [1, 2],
            'aturan/store' => [1, 2],
            'aturan/edit' => [1, 2],
            'aturan/update' => [1, 2],
            'aturan/delete' => [1, 2],

            'user/tagihan' => [3],
            'user/tagihan/detail' => [3],

            'user' => [1, 3],
            'user/index' => [1, 3],
            'user/komplain' => [1, 3],
            'user/pengumuman' => [1, 3],
            'user/aturan' => [1, 3],
            'user/about' => [1, 3],
            'user/about/updatePassword' => [1, 3],
        ];

        //ROLE CHECK
        if (isset($routeRoles[$url])) {
            $allowedRoles = $routeRoles[$url];
            if (!in_array(Auth::role(), $allowedRoles)) {
                if (Auth::isPenyewa()) {
                    header('Location: http://localhost/SobatKost/user');
                    exit;
                }
                http_response_code(403);
                die('403 Forbidden');
            }
        }
    }
}