<?php
requireAdmin();
$pdo = $GLOBALS['pdo'];
$error = '';
$success = '';

// Valida o ID do utilizador na URL
$userId = $_GET['id'] ?? null;
if (!$userId || !is_numeric($userId)) {
    header('Location: /admin_users');
    exit();
}

// Busca os dados do utilizador para preencher o formulário
$user = getUserById($pdo, $userId);
if (!$user) {
    // Se o utilizador não for encontrado, volta para a lista
    header('Location: /admin_users');
    exit();
}

// Processa o formulário quando for submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $plan = $_POST['plan'] ?? '';
    $newPassword = $_POST['password'] ?? '';

    // Atualiza os dados principais (nome, email, plano)
    $updateResult = updateUser($pdo, $userId, $name, $email, $plan);
    if ($updateResult === true) {
        $success = 'Dados do utilizador atualizados com sucesso!';
    } else {
        $error = $updateResult; // A função retorna a mensagem de erro específica
    }
    
    // Atualiza a senha apenas se um novo valor for fornecido
    if (!empty($newPassword)) {
        $passwordResult = updateUserPassword($pdo, $userId, $newPassword);
        if ($passwordResult === true) {
             $success .= ' Senha atualizada com sucesso!';
        } else {
            // Se já houver um erro, anexa a este. Senão, define-o.
            $error = $error ? $error . ' ' . $passwordResult : $passwordResult;
        }
    }
    
    // Recarrega os dados do utilizador para exibir as alterações no formulário
    $user = getUserById($pdo, $userId);
}
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-white mb-6">Editar Utilizador: <span class="text-primary"><?php echo htmlspecialchars($user['nome']); ?></span></h1>

    <div class="max-w-2xl mx-auto card p-8 bg-gray-900/50 border-gray-800">
        <form method="POST" action="/admin_edit_user?id=<?php echo $user['id']; ?>" class="space-y-6">

            <?php if ($error): ?><p class="text-red-400 text-sm bg-red-900/50 p-3 rounded-md"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
            <?php if ($success): ?><p class="text-green-400 text-sm bg-green-900/50 p-3 rounded-md"><?php echo htmlspecialchars($success); ?></p><?php endif; ?>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nome Completo</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['nome']); ?>" required class="w-full bg-gray-800 border-gray-700 rounded p-2 text-sm text-white">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="w-full bg-gray-800 border-gray-700 rounded p-2 text-sm text-white">
            </div>

            <div>
                <label for="plan" class="block text-sm font-medium text-gray-300 mb-1">Plano</label>
                <select name="plan" id="plan" class="w-full bg-gray-800 border-gray-700 rounded p-2 text-sm text-white">
                    <option value="Starter" <?php echo $user['plano'] === 'Starter' ? 'selected' : ''; ?>>Starter</option>
                    <option value="Pro" <?php echo $user['plano'] === 'Pro' ? 'selected' : ''; ?>>Pro</option>
                    <option value="Elite" <?php echo $user['plano'] === 'Elite' ? 'selected' : ''; ?>>Elite</option>
                </select>
            </div>

            <div class="border-t border-gray-700 pt-6">
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Nova Senha (opcional)</label>
                <input type="password" name="password" id="password" placeholder="Deixe em branco para não alterar" minlength="6" class="w-full bg-gray-800 border-gray-700 rounded p-2 text-sm text-white">
                <p class="text-xs text-gray-500 mt-1">A nova senha deve ter no mínimo 6 caracteres.</p>
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="/admin_users" class="text-sm font-medium text-gray-300 hover:text-white">&larr; Voltar para a lista</a>
                <button type="submit" class="btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>