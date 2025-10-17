<?php
if (!defined('APP_ROOT')) { die('Acesso direto não permitido.'); }

// ==================================================================
// FUNÇÕES DE AUTENTICAÇÃO E SESSÃO
// ==================================================================

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() { return isset($_SESSION['user_id']); }
}
if (!function_exists('isAdmin')) {
    function isAdmin() { return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1; }
}
if (!function_exists('requireLogin')) {
    function requireLogin() { if (!isLoggedIn()) { header('Location: /login'); exit(); } }
}
if (!function_exists('requireAdmin')) {
    function requireAdmin() { if (!isAdmin()) { header('Location: /dashboard'); exit(); } }
}

// ==================================================================
// FUNÇÕES DE GESTÃO DE UTILIZADORES (PÚBLICO)
// ==================================================================

if (!function_exists('loginUser')) {
    function loginUser($pdo, $email, $senha) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($senha, $user['senha'])) {
            if ($user['status'] !== 'approved') {
                return 'Sua conta está pendente de aprovação por um administrador.';
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'];
            return true;
        }
        return 'Email ou senha inválidos.';
    }
}

if (!function_exists('registerUser')) {
     function registerUser($pdo, $nome, $email, $senha) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return "Este email já está cadastrado.";
        }
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, is_admin, status) VALUES (?, ?, ?, 0, 'pending')");
        if ($stmt->execute([$nome, $email, $hash])) {
            return true;
        }
        return "Erro ao registrar o usuário.";
    }
}

// ==================================================================
// FUNÇÕES DE ANÁLISE E HISTÓRICO
// ==================================================================

if (!function_exists('saveAnalysis')) {
    function saveAnalysis($pdo, $userId, $analysisData) {
        // Implementação da função...
    }
}

if (!function_exists('getAnalyses')) {
    function getAnalyses($pdo, $userId) {
        $stmt = $pdo->prepare("SELECT * FROM analises WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// ==================================================================
// FUNÇÕES DO PAINEL DE ADMINISTRAÇÃO
// ==================================================================

if (!function_exists('getAllUsers')) {
    function getAllUsers($pdo) {
        return $pdo->query("SELECT id, nome, email, plano, status, created_at FROM usuarios ORDER BY created_at DESC")->fetchAll();
    }
}

if (!function_exists('updateUserStatus')) {
    function updateUserStatus($pdo, $userId, $status) {
        $stmt = $pdo->prepare("UPDATE usuarios SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $userId]);
    }
}

if (!function_exists('updateUserPlan')) {
    function updateUserPlan($pdo, $userId, $plan) {
        // Busca os detalhes do plano no banco de dados para garantir consistência
        $stmt_plan = $pdo->prepare("SELECT limite_analises FROM planos WHERE nome = ?");
        $stmt_plan->execute([$plan]);
        $plan_details = $stmt_plan->fetch();
        $limite = $plan_details ? $plan_details['limite_analises'] : 10; // Padrão de 10 se o plano não for encontrado

        $stmt = $pdo->prepare("UPDATE usuarios SET plano = ?, limite_analises = ?, analises_restantes = ? WHERE id = ?");
        return $stmt->execute([$plan, $limite, $limite, $userId]);
    }
}

if (!function_exists('deleteUser')) {
    function deleteUser($pdo, $userId) {
        if (isset($_SESSION['user_id']) && $userId == $_SESSION['user_id']) {
            return false; // Impede o admin de se apagar a si mesmo
        }
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$userId]);
    }
}

if (!function_exists('getUserById')) {
    function getUserById($pdo, $userId) {
        $stmt = $pdo->prepare("SELECT id, nome, email, plano FROM usuarios WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}

if (!function_exists('updateUser')) {
    function updateUser($pdo, $userId, $name, $email, $plan) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $userId]);
        if ($stmt->fetch()) {
            return "O email '{$email}' já está a ser utilizado por outro utilizador.";
        }
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $userId]);
        updateUserPlan($pdo, $userId, $plan);
        return true;
    }
}

if (!function_exists('updateUserPassword')) {
    function updateUserPassword($pdo, $userId, $newPassword) {
        if (empty($newPassword)) { return "O campo da nova senha não pode estar vazio."; }
        if (strlen($newPassword) < 6) { return "A nova senha deve ter pelo menos 6 caracteres."; }
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        return $stmt->execute([$hash, $userId]);
    }
}

// --- FUNÇÕES DE GESTÃO DE PLANOS QUE ESTAVAM EM FALTA ---

if (!function_exists('getAllPlans')) {
    /**
     * Busca todos os planos de assinatura no banco de dados.
     */
    function getAllPlans($pdo) {
        return $pdo->query("SELECT * FROM planos ORDER BY preco ASC")->fetchAll();
    }
}

if (!function_exists('updatePlan')) {
    /**
     * Atualiza o preço e a disponibilidade de um plano específico.
     */
    function updatePlan($pdo, $planId, $price, $isAvailable) {
        $stmt = $pdo->prepare("UPDATE planos SET preco = ?, disponivel = ? WHERE id = ?");
        return $stmt->execute([$price, $isAvailable, $planId]);
    }
}


// --- Funções de Gestão de API (Admin) ---

if (!function_exists('getApiConfigs')) {
    function getApiConfigs($pdo) {
        $configs = [];
        $stmt = $pdo->query("SELECT config_key, config_value FROM api_configs");
        foreach ($stmt->fetchAll() as $row) {
            $configs[$row['config_key']] = $row['config_value'];
        }
        return $configs;
    }
}

if (!function_exists('updateApiConfig')) {
    function updateApiConfig($pdo, $key, $value) {
        $stmt = $pdo->prepare("UPDATE api_configs SET config_value = ? WHERE config_key = ?");
        return $stmt->execute([$value, $key]);
    }
}

// --- ADICIONE ESTAS NOVAS FUNÇÕES AO SEU functions.php ---

// ==================================================================
// FUNÇÕES DE GESTÃO DE PLANOS (PÚBLICO E ADMIN)
// ==================================================================

if (!function_exists('getAllPlans')) {
    /**
     * (ADMIN) Busca TODOS os planos de assinatura para gestão.
     */
    function getAllPlans($pdo) {
        return $pdo->query("SELECT * FROM planos ORDER BY preco ASC")->fetchAll();
    }
}

if (!function_exists('getAvailablePlans')) {
    /**
     * (UTILIZADOR) Busca apenas os planos MARCADOS COMO DISPONÍVEIS para compra.
     */
    function getAvailablePlans($pdo) {
        return $pdo->query("SELECT * FROM planos WHERE disponivel = 1 ORDER BY preco ASC")->fetchAll();
    }
}

if (!function_exists('updatePlan')) {
    /**
     * (ADMIN) Atualiza o preço e a disponibilidade de um plano específico.
     */
    function updatePlan($pdo, $planId, $price, $isAvailable) {
        $stmt = $pdo->prepare("UPDATE planos SET preco = ?, disponivel = ? WHERE id = ?");
        return $stmt->execute([$price, $isAvailable, $planId]);
    }
}

?>