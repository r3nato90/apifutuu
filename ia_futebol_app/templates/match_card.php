<?php
// A variável $match é passada pelo loop no dashboard.php
$homeTeam = $match['teams']['home'];
$awayTeam = $match['teams']['away'];
$league = $match['league'];
$fixture = $match['fixture'];
?>
<div class="card p-4 sm:p-6 hover:border-primary/50 transition-all duration-300 group hover:shadow-lg bg-gray-900/30 border-gray-800">
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0 flex-1">
                <img src="<?php echo htmlspecialchars($league['logo']); ?>" alt="<?php echo htmlspecialchars($league['name']); ?>" class="w-5 h-5 rounded flex-shrink-0">
                <span class="text-xs sm:text-sm font-medium text-muted-foreground truncate"><?php echo htmlspecialchars($league['name']); ?></span>
            </div>
            <span class="inline-flex items-center rounded-full border border-gray-700 px-2.5 py-0.5 text-xs font-semibold text-white"><?php echo htmlspecialchars($fixture['status']['short']); ?></span>
        </div>

        <div class="grid grid-cols-[1fr_auto_1fr] gap-3 sm:gap-4 items-center">
            <div class="flex flex-col items-center gap-2">
                <div class="relative w-12 h-12 sm:w-16 sm:h-16 transition-transform group-hover:scale-105">
                    <img src="<?php echo htmlspecialchars($homeTeam['logo']); ?>" alt="<?php echo htmlspecialchars($homeTeam['name']); ?>" class="object-contain w-full h-full">
                </div>
                <span class="text-xs sm:text-sm font-semibold text-center h-8 text-white"><?php echo htmlspecialchars($homeTeam['name']); ?></span>
            </div>

            <div class="flex flex-col items-center gap-1">
                <div class="text-xl sm:text-2xl font-bold text-muted-foreground">VS</div>
            </div>

            <div class="flex flex-col items-center gap-2">
                <div class="relative w-12 h-12 sm:w-16 sm:h-16 transition-transform group-hover:scale-105">
                    <img src="<?php echo htmlspecialchars($awayTeam['logo']); ?>" alt="<?php echo htmlspecialchars($awayTeam['name']); ?>" class="object-contain w-full h-full">
                </div>
                <span class="text-xs sm:text-sm font-semibold text-center h-8 text-white"><?php echo htmlspecialchars($awayTeam['name']); ?></span>
            </div>
        </div>

        <div class="flex items-center justify-between text-xs text-muted-foreground pt-2 border-t border-gray-700 gap-2">
            <div class="flex items-center gap-1">
                <i data-lucide="calendar" class="h-3 w-3"></i>
                <span><?php echo date('d/m \à\s H:i', strtotime($fixture['date'])); ?></span>
            </div>
            <div class="flex items-center gap-1 min-w-0">
                <i data-lucide="map-pin" class="h-3 w-3 flex-shrink-0"></i>
                <span class="truncate"><?php echo htmlspecialchars($fixture['venue']['name']); ?></span>
            </div>
        </div>
        
        <a href="/analise/<?php echo $fixture['id']; ?>" class="btn-primary w-full text-center flex items-center justify-center gap-2 py-2">
            <i data-lucide="trending-up" class="h-4 w-4"></i>
            Analisar Partida
        </a>
    </div>
</div>