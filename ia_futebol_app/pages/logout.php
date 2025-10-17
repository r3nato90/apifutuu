<?php
// Garante que uma sessão esteja ativa antes de tentar destruí-la
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Limpa todas as variáveis da sessão (ex: $_SESSION['user_id'], $_SESSION['user_name'])
$_SESSION = array();

// Destrói a sessão completamente
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Redireciona o utilizador para a página de login
header('Location: /login');
exit(); // Garante que nenhum código adicional seja executado após o redirecionamento
?>