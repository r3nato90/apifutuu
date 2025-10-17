<?php
requireAdmin();
$pdo = $GLOBALS['pdo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['configs'] as $key => $value) {
        updateApiConfig($pdo, $key, $value);
    }
    header('Location: /admin_apis?success=1');
    exit;
}

$configs = getApiConfigs($pdo);
?>

<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-white mb-6">Gerir Chaves de API</h1>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-900/50 border border-green-700 text-green-300 text-sm rounded-lg p-3 mb-6">
            Chaves de API atualizadas com sucesso!
        </div>
    <?php endif; ?>

    <div class="card p-6 bg-gray-800/50 max-w-2xl">
        <form method="POST" action="/admin_apis" class="space-y-6">
            <div>
                <label for="gemini-key" class="block text-sm font-medium text-gray-300 mb-1">Gemini API Key</label>
                <input type="text" name="configs[GEMINI_API_KEY]" id="gemini-key" value="<?php echo htmlspecialchars($configs['GEMINI_API_KEY']); ?>" class="w-full bg-gray-900 border-gray-700 rounded p-2 text-sm">
            </div>
            <div>
                <label for="football-key" class="block text-sm font-medium text-gray-300 mb-1">Football API Key</label>
                <input type="text" name="configs[FOOTBALL_API_KEY]" id="football-key" value="<?php echo htmlspecialchars($configs['FOOTBALL_API_KEY']); ?>" class="w-full bg-gray-900 border-gray-700 rounded p-2 text-sm">
            </div>
            <div>
                <label for="lirapay-key" class="block text-sm font-medium text-gray-300 mb-1">LiraPay API Secret</label>
                <input type="text" name="configs[LIRAPAY_API_SECRET]" id="lirapay-key" value="<?php echo htmlspecialchars($configs['LIRAPAY_API_SECRET']); ?>" class="w-full bg-gray-900 border-gray-700 rounded p-2 text-sm">
            </div>
            <div class="pt-4">
                <button type="submit" class="btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>