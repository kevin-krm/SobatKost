<?php
require_once __DIR__ . '/../dao/PDOUtil.php';

class Auth
{
    public static function check()
    {
        if (!isset($_SESSION['user'])) {
            return false;
        }

        try {
            // Verifikasi koneksi ke database
            $link = PDOUtil::createConnection();
            if ($link) {
                // Ambil status Uptime database untuk mendeteksi restart database
                $stmt = $link->query("SHOW GLOBAL STATUS LIKE 'Uptime'");
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $uptime = (int)$row['Value'];
                    $db_start_time = time() - $uptime;
                    // Bulatkan ke kelipatan 10 detik terdekat untuk meminimalkan latensi eksekusi query
                    $db_start_time_rounded = round($db_start_time / 10) * 10;
                    
                    if (isset($_SESSION['db_start_time'])) {
                        if (abs($_SESSION['db_start_time'] - $db_start_time_rounded) > 10) {
                            // Database telah di-restart, Logout otomatis
                            session_unset();
                            @session_destroy();
                            return false;
                        }
                    } else {
                        // Simpan waktu boot pertama kali database ke dalam session
                        $_SESSION['db_start_time'] = $db_start_time_rounded;
                    }
                }
            }
        } catch (PDOException $e) {
            // Jika query gagal, hapus sesi dan anggap tidak terautentikasi
            session_unset();
            @session_destroy();
            return false;
        }
        return true;
    }

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function role()
    {
        return $_SESSION['user']['id_peran'] ?? null;
    }

    public static function isOwner()
    {
        return self::role() == 1;
    }

    public static function isPenjaga()
    {
        return self::role() == 2;
    }

    public static function isPenyewa()
    {
        return self::role() == 3;
    }
}