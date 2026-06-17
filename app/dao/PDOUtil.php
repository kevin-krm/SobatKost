<?php
/**
 * Class PDOUtil
 * Menerapkan Singleton Pattern
 * Memastikan aplikasi hanya membuka satu koneksi (instance) database saja 
 * untuk mencegah pemborosan memory dan server overload saat query intensif.
 */
class PDOUtil {
    private static $connection;
    public static function createConnection() {
        if (!isset(self::$connection)) {
            $host = 'localhost';
            $db   = 'SobatKost';
            $user = 'root';
            $pass = '';

            try {
                self::$connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                session_unset();
                @session_destroy();
                die("Koneksi gagal: " . $e->getMessage() . ". Anda telah terlogout secara otomatis.");
            }
        }
        return self::$connection;
    }
    public static function closeConnection() {
        self::$connection = null;
    }
}
?>