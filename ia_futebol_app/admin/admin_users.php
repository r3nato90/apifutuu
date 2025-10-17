<?php
requireAdmin();
$pdo = $GLOBALS['pdo'];

// Processa ações rápidas como aprovar ou excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
    $userId = $_POST['user_id'];
    if ($_POST['action'] === 'approve') { updateUserStatus($pdo, $userId, 'approved'); }
    if ($_POST['action'] === 'delete') { deleteUser($pdo, $userId); }
    header('Location: /admin_users'); // Recarrega a página para ver as alterações
    exit;
}

// Busca todos os utilizadores para exibir na tabela
$users = getAllUsers($pdo);
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Gerir Utilizadores</h1>
            <p class="mt-1 text-sm text-gray-400">Total de <?php echo count($users); ?> utilizadores registados.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="/admin" class="text-sm font-medium text-gray-300 hover:text-white">&larr; Voltar ao Dashboard</a>
        </div>
    </div>
    
    <div class="card bg-gray-900/50 border-gray-800 overflow-hidden shadow-lg rounded-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">
                <thead class="text-xs text-gray-400 uppercase bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3">Nome</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Plano</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-400">Nenhum utilizador registado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-800/40">
                            <td class="px-6 py-4 font-medium text-white"><?php echo htmlspecialchars($user['nome']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['plano']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full 
                                    <?php echo $user['status'] === 'approved' ? 'text-green-100 bg-green-700' : ($user['status'] === 'pending' ? 'text-yellow-100 bg-yellow-700' : 'text-red-100 bg-red-700'); ?>">
                                    <?php echo htmlspecialchars($user['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex items-center justify-end gap-4">
                                <?php if ($user['status'] === 'pending'): ?>
                                <form method="POST" class="inline" onsubmit="return confirm('Aprovar este utilizador?');">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="font-medium text-green-400 hover:underline">Aprovar</button>
                                </form>
                                <?php endif; ?>
                                
                                <a href="/admin_edit_user?id=<?php echo $user['id']; ?>" class="font-medium text-primary hover:underline">Editar</a>
                                
                                <?php if ($_SESSION['user_id'] != $user['id']): // Impede que o admin se apague a si mesmo ?>
                                <form method="POST" class="inline" onsubmit="return confirm('Tem a certeza que quer apagar este utilizador? Esta ação é irreversível.');">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="font-medium text-red-500 hover:underline">Excluir</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>