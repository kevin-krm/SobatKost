<?php

class AuthMiddleware
{
    public static function check()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?url=login');
            exit;
        }
    }
}