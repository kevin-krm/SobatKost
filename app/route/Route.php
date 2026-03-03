<?php
include_once 'controller/PenghuniController.php';

$menu = filter_input(INPUT_GET, 'menu');

switch ($menu) {
    case 'adm-penghuni-create':
        $controller = new PenghuniController();
        $controller->create();
        break;
    case 'adm-penghuni-delete':
        // Routing untuk delete data
        break;
    default:
        // Diisi dengan action default
        break;
}
