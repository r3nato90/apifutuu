<?php
if (!defined('APP_ROOT')) { die('Acesso direto não permitido.'); }

// A variável $pdo será acessível globalmente
global $pdo;

// Só tenta conectar se a conexão ainda não existir
if (!isset($pdo)) {
    // Busca as credenciais diretamente do array $_ENV
    $db_host = $_ENV['DB_HOST'] ?? null;
    $db_name = $_ENV['DB_NAME'] ?? null;
    $db_user = $_ENV['DB_USER'] ?? null;
    $db_pass = $_ENV['DB_PASS'] ?? null;

    if (!$db_host || !$db_name || !$db_user) {
        die("Erro Crítico: Variáveis de ambiente do banco de dados não foram carregadas. Verifique se o arquivo .env existe em 'ia_futebol_app' e se as permissões de leitura estão corretas.");
    }

    try {
        $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    } catch (PDOException $e) {
        if (isset($_ENV['DEBUG_MODE']) && $_ENV['DEBUG_MODE'] === 'true') {
            die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
        } else {
            die("Erro ao conectar ao servidor. Tente novamente mais tarde.");
        }
    }
}
?>