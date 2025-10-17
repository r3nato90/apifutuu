<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração - IA Football</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root { --background: #111827; --foreground: #f9fafb; --card: #1f2937; --primary: #34d399; --muted-foreground: #9ca3af; --border: #374151; --radius: 0.5rem; --destructive: #f87171; }
        body { background-color: var(--background); color: var(--foreground); font-family: Inter, sans-serif; }
        .card { background-color: var(--card); border: 1px solid var(--border); border-radius: var(--radius); }
        .btn-primary { background-color: var(--primary); color: #111827; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; font-weight: 500; }
        .btn-danger { background-color: var(--destructive); color: #f9fafb; }
        .text-muted-foreground { color: var(--muted-foreground); }
        .text-primary { color: var(--primary); }
    </style>
</head>
<body class="antialiased">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-gray-900 border-r border-gray-800 p-6 flex-shrink-0">
            <div class="flex items-center gap-2 mb-10">
                <img src="/assets/img/logo.png" alt="Logo" class="h-10">
            </div>
            <nav class="space-y-2">
                <a href="/admin" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                </a>
                <a href="/admin_users" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i data-lucide="users" class="w-5 h-5"></i> Gerir Utilizadores
                </a>
                <a href="/admin_plans" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i data-lucide="shield" class="w-5 h-5"></i> Gerir Planos
                </a>
                <a href="/admin_settings" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i data-lucide="sliders-horizontal" class="w-5 h-5"></i> Configurações de API
                </a>
            </nav>
            <div class="mt-auto absolute bottom-6">
                <a href="/logout" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                    <i data-lucide="log-out" class="w-5 h-5"></i> Sair
                </a>
            </div>
        </aside>

        <div class="flex-1">
            <main class="p-6 lg:p-10">
                <?php echo $pageContent; // O conteúdo da página do admin é inserido aqui ?>
            </main>
        </div>
    </div>
    <script> lucide.createIcons(); </script>
</body>
</html>