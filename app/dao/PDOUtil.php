<?php
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
                die("Koneksi gagal: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
    public static function closeConnection() {
        self::$connection = null;
    }
}
?>