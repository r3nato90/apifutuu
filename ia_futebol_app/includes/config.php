<?php
// Define a constante APP_ROOT se ainda não estiver definida.
if (!defined('APP_ROOT')) {
    // __DIR__ é a pasta atual (includes), então voltamos um nível para chegar a 'ia_futebol_app'.
    define('APP_ROOT', dirname(__DIR__));
}

// Carrega as variáveis de ambiente do arquivo .env diretamente para $_ENV.
// Esta é a abordagem mais compatível.
$envFile = APP_ROOT . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value, ' "');
        }
    }
}

// Ativa a exibição de erros se o DEBUG_MODE estiver 'true' no .env
$isDebugMode = isset($_ENV['DEBUG_MODE']) && $_ENV['DEBUG_MODE'] === 'true';
if ($isDebugMode) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>