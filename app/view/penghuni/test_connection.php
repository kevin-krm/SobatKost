<?php
require_once '../../dao/PDOUtil.php';

try {
    $connection = PDOUtil::createMySQLConnection();
    echo "Koneksi berhasil!";
    $connection = null;
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>