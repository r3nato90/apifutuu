<?php
requireLogin();
$analyses = getAnalyses($GLOBALS['pdo'], $_SESSION['user_id']);
?>

<div class="container mx-auto px-4 py-6 sm:py-8">
    <div class="mb-6 sm:mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold mb-1 text-white">Histórico de Análises</h2>
        <p class="text-sm text-muted-foreground"><?php echo count($analyses); ?> <?php echo (count($analyses) == 1 ? 'análise salva' : 'análises salvas'); ?></p>
    </div>

    <?php if (empty($analyses)): ?>
        <div class="text-center py-20 card bg-gray-900/50 border-gray-800 rounded-lg">
            <i data-lucide="history" class="mx-auto h-12 w-12 text-muted-foreground"></i>
            <p class="mt-4 text-lg text-gray-400">Nenhuma análise encontrada</p>
            <p class="text-sm text-muted-foreground">Analise partidas para ver o seu histórico aqui.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($analyses as $analysis): ?>
                <a href="/analise/<?php echo $analysis['match_id']; ?>" class="block card p-6 hover:border-primary/50 transition-all bg-gray-900/30 border-gray-800">
                    </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>