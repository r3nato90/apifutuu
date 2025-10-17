<?php
if (isLoggedIn()) { header('Location: /dashboard'); exit(); }
$error = ''; $success = '';

if (isset($_SESSION['success_message'])) {
    $success = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = loginUser($GLOBALS['pdo'], $_POST['email'], $_POST['password']);
    if ($result === true) {
        if (isAdmin()) {
            header('Location: /admin');
        } else {
            header('Location: /dashboard');
        }
        exit();
    } else {
        $error = $result; // A função agora retorna a mensagem de erro
    }
}
?>
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md card p-8 bg-gray-900/50 border-gray-800">
        <div class="flex justify-center mb-6"><img src="/assets/img/logo.png" alt="Logo" class="h-20"></div>
        <form method="POST" action="/login" class="space-y-6">
            <div class="text-center">
                 <h1 class="text-2xl font-bold text-white">Acesse sua Conta</h1>
            </div>
            <?php if ($error): ?><p class="text-red-400 text-sm text-center bg-red-900/50 p-3 rounded-md"><?php echo $error; ?></p><?php endif; ?>
            <?php if ($success): ?><p class="text-green-400 text-sm text-center bg-green-900/50 p-3 rounded-md"><?php echo $success; ?></p><?php endif; ?>
            <div>
                <input type="email" name="email" placeholder="Seu email" required class="w-full bg-gray-800 border-gray-700 rounded p-3 text-sm text-white focus:ring-primary focus:border-primary">
            </div>
            <div>
                <input type="password" name="password" placeholder="Sua senha" required class="w-full bg-gray-800 border-gray-700 rounded p-3 text-sm text-white focus:ring-primary focus:border-primary">
            </div>
            <button type="submit" class="w-full btn-primary py-3">Entrar</button>
            <p class="text-sm text-center text-gray-400">Não tem conta? <a href="/register" class="text-primary hover:underline font-semibold">Cadastre-se</a></p>
        </form>
    </div>
</div>