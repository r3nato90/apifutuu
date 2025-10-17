<?php
requireAdmin();
$pdo = $GLOBALS['pdo'];
$success = '';
$error = '';

// Processa o formulário quando for submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plans'])) {
    $all_ok = true;
    foreach ($_POST['plans'] as $planId => $planData) {
        $price = $planData['price'] ?? 0;
        // O valor da checkbox vem como 'on' se estiver marcada, senão não vem.
        $isAvailable = isset($planData['available']) ? 1 : 0;

        if (!updatePlan($pdo, $planId, $price, $isAvailable)) {
            $all_ok = false;
        }
    }

    if ($all_ok) {
        $success = 'Planos atualizados com sucesso!';
    } else {
        $error = 'Ocorreu um erro ao atualizar um ou mais planos.';
    }
}

// Busca todos os planos para exibir no formulário
$plans = getAllPlans($pdo);
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Gerir Planos de Assinatura</h1>
            <p class="mt-1 text-sm text-gray-400">Altere os preços e a disponibilidade dos planos para compra.</p>
        </div>
        <a href="/admin" class="text-sm font-medium text-gray-300 hover:text-white mt-4 sm:mt-0">&larr; Voltar ao Dashboard</a>
    </div>

    <div class="max-w-4xl mx-auto card p-8 bg-gray-900/50 border-gray-800">
        <form method="POST" action="/admin_plans">
            
            <?php if ($error): ?><p class="text-red-400 text-sm bg-red-900/50 p-3 rounded-md mb-6"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
            <?php if ($success): ?><p class="text-green-400 text-sm bg-green-900/50 p-3 rounded-md mb-6"><?php echo htmlspecialchars($success); ?></p><?php endif; ?>
            
            <div class="space-y-8">
                <?php foreach ($plans as $plan): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center border-b border-gray-700 pb-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-semibold text-white"><?php echo htmlspecialchars($plan['nome']); ?></h3>
                            <p class="text-sm text-muted-foreground"><?php echo $plan['limite_analises'] >= 99999 ? 'Análises Ilimitadas' : $plan['limite_analises'] . ' análises/dia'; ?></p>
                        </div>
                        
                        <div class="md:col-span-1">
                            <label for="price_<?php echo $plan['id']; ?>" class="block text-sm font-medium text-gray-300 mb-1">Preço (R$)</label>
                            <input type="number" step="0.01" name="plans[<?php echo $plan['id']; ?>][price]" id="price_<?php echo $plan['id']; ?>" value="<?php echo htmlspecialchars($plan['preco']); ?>" required class="w-full bg-gray-800 border-gray-700 rounded p-2 text-sm text-white">
                        </div>

                        <div class="md:col-span-1 flex items-center justify-end">
                            <label for="available_<?php echo $plan['id']; ?>" class="text-sm font-medium text-gray-300 mr-4">Disponível para compra</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="plans[<?php echo $plan['id']; ?>][available]" id="available_<?php echo $plan['id']; ?>" class="sr-only peer" <?php echo $plan['disponivel'] ? 'checked' : ''; ?>>
                                <div class="w-11 h-6 bg-gray-600 rounded-full peer peer-focus:ring-2 peer-focus:ring-primary/50 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="flex justify-end pt-8">
                <button type="submit" class="btn-primary">Salvar Todas as Alterações</button>
            </div>
        </form>
    </div>
</div>