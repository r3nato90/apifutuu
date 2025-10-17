<?php
if (isLoggedIn()) { header('Location: /dashboard'); exit(); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';
    $confirm_senha = $_POST['confirmPassword'] ?? '';

    if (empty($nome) || empty($email) || empty($senha)) { $error = 'Todos os campos são obrigatórios.'; }
    elseif ($senha !== $confirm_senha) { $error = 'As senhas não coincidem.'; }
    elseif (strlen($senha) < 6) { $error = 'A senha deve ter pelo menos 6 caracteres.'; }
    else {
        $result = registerUser($GLOBALS['pdo'], $nome, $email, $senha);
        if ($result === true) {
            $_SESSION['success_message'] = 'Conta criada com sucesso! Aguarde a aprovação de um administrador para fazer login.';
            header('Location: /login');
            exit();
        } else {
            $error = $result;
        }
    }
}
?>
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md card p-8 bg-gray-900/50 border-gray-800">
        <div class="flex justify-center mb-6"><img src="/assets/img/logo.png" alt="Logo" class="h-20"></div>
        <form method="POST" action="/register" class="space-y-4">
            <h1 class="text-2xl font-bold text-center text-white">Criar Conta</h1>
            <?php if ($error): ?><p class="text-red-400 text-sm text-center bg-red-900/50 p-3 rounded-md"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
            <input type="text" name="name" placeholder="Nome completo" required class="w-full bg-gray-800 border-gray-700 rounded p-3 text-sm">
            <input type="email" name="email" placeholder="Seu email" required class="w-full bg-gray-800 border-gray-700 rounded p-3 text-sm">
            <input type="password" name="password" placeholder="Senha (mín. 6 caracteres)" required class="w-full bg-gray-800 border-gray-700 rounded p-3 text-sm">
            <input type="password" name="confirmPassword" placeholder="Confirmar senha" required class="w-full bg-gray-800 border-gray-700 rounded p-3 text-sm">
            <button type="submit" class="w-full btn-primary py-3">Criar Conta</button>
            <p class="text-sm text-center text-gray-400">Já tem conta? <a href="/login" class="text-primary hover:underline">Entrar</a></p>
        </form>
    </div>
</div>