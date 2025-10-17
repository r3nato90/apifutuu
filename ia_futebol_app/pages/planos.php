<?php
requireLogin();
// Usa a nova função para buscar apenas os planos disponíveis
$plans = getAvailablePlans($GLOBALS['pdo']);
?>

<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-white">Escolha o Plano Ideal</h1>
        <p class="text-muted-foreground mt-2">Desbloqueie todo o potencial da nossa IA.</p>
    </div>

    <?php if (empty($plans)): ?>
        <div class="text-center py-20 card bg-gray-900/50 border-gray-800 max-w-2xl mx-auto">
            <i data-lucide="package-x" class="mx-auto h-12 w-12 text-muted-foreground"></i>
            <p class="mt-4 text-lg text-gray-400">Nenhum plano disponível no momento.</p>
            <p class="text-sm text-muted-foreground">Por favor, volte mais tarde.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <?php foreach ($plans as $plan): ?>
                <div class="card p-8 flex flex-col text-center <?php echo $plan['destaque'] ? 'border-primary/70 ring-2 ring-primary' : 'border-gray-800'; ?> bg-gray-900/30">
                    <?php if ($plan['destaque']): ?>
                        <div class="absolute top-0 -translate-y-1/2 left-1/2 -translate-x-1/2"><span class="inline-flex items-center rounded-full bg-primary px-3 py-1 text-xs font-semibold text-primary-foreground">MAIS POPULAR</span></div>
                    <?php endif; ?>
                    <h2 class="text-2xl font-semibold text-white"><?php echo htmlspecialchars($plan['nome']); ?></h2>
                    <p class="text-5xl font-bold my-6 text-white">R$ <?php echo number_format($plan['preco'], 2, ',', '.'); ?><span class="text-lg text-muted-foreground">/mês</span></p>
                    <ul class="space-y-3 text-left mb-8 text-gray-300"><li class="flex items-center gap-3"><i data-lucide="check" class="w-5 h-5 text-primary"></i> <?php echo $plan['limite_analises'] >= 99999 ? 'Análises ilimitadas' : $plan['limite_analises'] . ' análises/dia'; ?></li></ul>
                    <a href="/pagamento?plano=<?php echo htmlspecialchars($plan['nome']); ?>&valor=<?php echo htmlspecialchars($plan['preco']); ?>" class="btn-primary mt-auto w-full block">Assinar <?php echo htmlspecialchars($plan['nome']); ?></a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>