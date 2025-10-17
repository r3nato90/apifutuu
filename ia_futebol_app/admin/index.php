<?php
requireAdmin();
$users = getAllUsers($GLOBALS['pdo']);
$pending_users_count = count(array_filter($users, fn($user) => $user['status'] === 'pending'));
?>
<h1 class="text-3xl font-bold text-white mb-8">Dashboard</h1>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="card p-6"><p class="text-sm text-gray-400">Total de Utilizadores</p><p class="text-3xl font-bold text-white mt-1"><?php echo count($users); ?></p></div>
    <div class="card p-6"><p class="text-sm text-gray-400">Utilizadores Pendentes</p><p class="text-3xl font-bold text-yellow-400 mt-1"><?php echo $pending_users_count; ?></p></div>
</div>