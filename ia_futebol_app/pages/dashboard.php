<?php
requireLogin();

require_once APP_ROOT . '/api/FootballApiService.php';

$stmt = $GLOBALS['pdo']->query("SELECT config_value FROM api_configs WHERE config_key = 'FOOTBALL_API_KEY'");
$footballApiKey = $stmt->fetchColumn();

$footballApi = new FootballApiService($footballApiKey);
$data = $footballApi->getTodayMatches();

$matchesByDate = [];
if (!empty($data['matches'])) {
    $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'America/Sao_Paulo', IntlDateFormatter::GREGORIAN, 'EEEE, d \'de\' MMMM \'de\' yyyy');
    foreach ($data['matches'] as $match) {
        $date = new DateTime($match['fixture']['date']);
        $dateKey = mb_convert_case($formatter->format($date), MB_CASE_TITLE, 'UTF-8');
        $matchesByDate[$dateKey][] = $match;
    }
}
?>

<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 md:py-8">
    <div class="mb-4 sm:mb-6 md:mb-8">
        <div class="flex items-center gap-2 sm:gap-3 mb-2">
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-balance text-white">Próximas Partidas</h2>
            <i data-lucide="sparkles" class="h-5 w-5 sm:h-6 sm:w-6 text-primary animate-pulse"></i>
        </div>
        <p class="text-xs sm:text-sm md:text-base text-muted-foreground">Análise inteligente para suas apostas esportivas</p>
    </div>

    <?php if ($data['isDemoMode']): ?>
        <div class="bg-yellow-900/50 border border-yellow-700 text-yellow-300 text-sm rounded-lg p-4 mb-6">
            <div class="flex items-start"><i data-lucide="info" class="h-5 w-5 text-yellow-400 mt-0.5"></i>
                <div class="ml-3">
                    <h3 class="font-bold">Modo Demonstração</h3>
                    <p class="mt-1 text-sm text-yellow-200">A chave da API de futebol não foi configurada no painel de administração.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="space-y-8">
        <?php if (empty($matchesByDate)): ?>
            <div class="text-center py-20 card bg-gray-900/50 border-gray-800 rounded-lg">
                <i data-lucide="calendar-x" class="mx-auto h-12 w-12 text-muted-foreground"></i>
                <p class="mt-4 text-lg text-gray-400">Nenhuma partida encontrada.</p>
            </div>
        <?php else: ?>
            <?php foreach ($matchesByDate as $date => $matches): ?>
                <div class="space-y-4">
                    <div class="flex items-center gap-3 pb-2 border-b border-gray-700">
                        <i data-lucide="calendar" class="h-5 w-5 text-primary"></i>
                        <h3 class="text-lg sm:text-xl font-semibold capitalize text-white"><?php echo $date; ?></h3>
                        <span class="text-sm text-gray-400">({{ count($matches) }} jogos)</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($matches as $match): ?>
                            <?php include APP_ROOT . '/templates/match_card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>