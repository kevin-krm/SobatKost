<?php

class Auth
{
    public static function check()
    {
        return isset($_SESSION['user']);
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