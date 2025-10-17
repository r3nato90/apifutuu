<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IA FOOTBALL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root { --background: oklch(0.12 0 0); --foreground: oklch(0.98 0 0); --card: oklch(0.16 0 0); --primary: oklch(0.85 0.25 145); --muted-foreground: oklch(0.65 0 0); --border: oklch(0.25 0 0); --radius: 0.5rem; }
        body { background-color: var(--background); color: var(--foreground); font-family: Inter, sans-serif; }
        .card { background-color: var(--card); border: 1px solid var(--border); border-radius: var(--radius); }
        .btn-primary { background-color: var(--primary); color: #1f2937; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; font-weight: 500; }
    </style>
</head>
<body class="antialiased">
    <?php if (isLoggedIn()): ?>
        <header class="border-b border-gray-700 bg-black/30 backdrop-blur-sm sticky top-0 z-50">
            </header>
    <?php endif; ?>
    <main>
        <?php echo $pageContent; // O conteúdo da página é inserido aqui ?>
    </main>
    <script> lucide.createIcons(); </script>
</body>
</html>