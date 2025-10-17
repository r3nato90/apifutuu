<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IA FOOTBALL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root { --background: oklch(0.12 0 0); --foreground: oklch(0.98 0 0); --card: oklch(0.16 0 0); --primary: oklch(0.85 0.25 145); --muted-foreground: oklch(0.65 0 0); --border: oklch(0.25 0 0); --radius: 0.5rem; --destructive: oklch(0.577 0.245 27.325); }
        body { background-color: var(--background); color: var(--foreground); font-family: Inter, sans-serif; }
        .card { background-color: var(--card); border: 1px solid var(--border); border-radius: var(--radius); }
        .btn-primary { background-color: var(--primary); color: #111827; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; font-weight: 500; }
        .btn-primary:hover { opacity: 0.9; }
        .text-muted-foreground { color: var(--muted-foreground); }
        .text-primary { color: var(--primary); }
        .text-destructive { color: var(--destructive); }
    </style>
</head>
<body class="antialiased">
    <?php if (isLoggedIn()): ?>
        <header class="border-b border-gray-700 bg-black/30 backdrop-blur-sm sticky top-0 z-50">
            <div class="container mx-auto px-4 py-3">
                <nav class="flex items-center justify-between">
                    <a href="/dashboard"><img src="/assets/img/logo.png" alt="Logo" class="h-10"></a>
                    <div class="flex items-center space-x-6 text-sm text-white">
                        <a href="/dashboard" class="hover:text-primary">Partidas</a>
                        <a href="/historico" class="hover:text-primary">Histórico</a>
                        <a href="/planos" class="hover:text-primary">Planos</a>
                        <?php if (isAdmin()): ?><a href="/admin" class="text-yellow-400 hover:text-yellow-300">Admin</a><?php endif; ?>
                        <span class="text-gray-400"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <a href="/logout" class="text-gray-400 hover:text-white flex items-center gap-1"><i data-lucide="log-out" class="w-4 h-4"></i>Sair</a>
                    </div>
                </nav>
            </div>
        </header>
    <?php endif; ?>

    <main>
        <?php echo $pageContent; ?>
    </main>

    <script> lucide.createIcons(); </script>
</body>
</html>