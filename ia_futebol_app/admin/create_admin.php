<?php
// Define o caminho para a raiz da aplicação para que possamos incluir os ficheiros
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Carrega os ficheiros essenciais da aplicação
require_once APP_ROOT . '/includes/config.php';
require_once APP_ROOT . '/includes/db.php';

// --- DEFINA AQUI AS SUAS CREDENCIAIS DE ADMIN ---
$nome_admin = "Admin Principal";
$email_admin = "admin@email.com"; // Pode mudar para o seu email de admin
$senha_admin = "admin123";      // PODE E DEVE mudar para uma senha forte

echo "<h1>Criação de Utilizador Administrador</h1>";

try {
    // Criptografa a senha para guardá-la de forma segura
    $hash = password_hash($senha_admin, PASSWORD_BCRYPT);

    // Prepara o SQL para inserir um novo admin ou atualizar um existente com o mesmo email
    // A coluna 'is_admin' é definida como 1 para dar privilégios de administrador
    $stmt = $GLOBALS['pdo']->prepare(
        "INSERT INTO usuarios (nome, email, senha, is_admin, status) VALUES (?, ?, ?, 1, 'approved')
         ON DUPLICATE KEY UPDATE nome = VALUES(nome), senha = VALUES(senha), is_admin = 1, status = 'approved'"
    );

    // Executa o comando no banco de dados
    if ($stmt->execute([$nome_admin, $email_admin, $hash])) {
        echo "<p style='color:green;'>Administrador '{$email_admin}' criado/atualizado com sucesso!</p>";
        echo "<p><strong>Senha:</strong> {$senha_admin}</p>";
        echo "<p style='color:red; font-weight:bold;'>IMPORTANTE: Apague este ficheiro ('create_admin.php') do seu servidor imediatamente por segurança!</p>";
    } else {
        echo "<p style='color:red;'>Erro ao criar/atualizar o administrador.</p>";
    }

} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro no banco de dados: " . $e->getMessage() . "</p>";
}
?>