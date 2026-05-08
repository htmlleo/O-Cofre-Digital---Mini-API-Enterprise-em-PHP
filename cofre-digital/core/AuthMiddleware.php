<?php
/**
 * Middleware de Autenticação
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Response.php';

class AuthMiddleware {
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            Response::error('Acesso negado. Autenticação necessária.', 401);
        }

        return $_SESSION['user_id'];
    }

    public static function getUserId() {
        return self::check();
    }
}
