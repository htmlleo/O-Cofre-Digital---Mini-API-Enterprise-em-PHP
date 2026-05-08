<?php
/**
 * Configurações do Sistema - O Cofre Digital
 */

// Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'cofre_digital');
define('DB_USER', 'root');
define('DB_PASS', ''); // Padrão XAMPP

// Configurações de Segurança
define('SESSION_NAME', 'COFRE_SESSION');
define('DEBUG_MODE', false); // Desativar em produção para evitar echo de erros

// Configurações de Caminhos
define('BASE_PATH', dirname(__DIR__));

// Configurações de Fuso Horário
date_default_timezone_set('America/Sao_Paulo');

// Configuração de Erros (Security by Design)
if (!DEBUG_MODE) {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
