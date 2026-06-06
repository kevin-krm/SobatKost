<?php
require_once __DIR__ . '/Auth.php';

class AuthMiddleware
{
    public static function check()
    {
        if (!Auth::check()) {
            header('Location: index.php?url=login');
            exit;
        }
    }
}