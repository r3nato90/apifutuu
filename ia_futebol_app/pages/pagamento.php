<?php
requireLogin();
global $pdo;

// --- Validação dos Dados do Plano ---
$plano = $_GET['plano'] ?? null;
$valor = $_GET['valor'] ?? null;
$allowed_plans = ['Starter', 'Pro', 'Elite'];

if (!$plano || !$valor || !is_numeric($valor) || !in_array($plano, $allowed_plans)) {
    header('Location: /planos');
    exit();
}

// --- Variáveis de Controle ---
$error = '';
$transactionData = null;
// Pega o email do utilizador logado a partir da sessão
$user_email = $_SESSION['user_email'] ?? 'Email não encontrado';

// --- Processamento do Formulário ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $nome_completo = $_POST['fullname'] ?? '';
    $cpf = $_POST['document'] ?? '';

    // Validação simples
    if (empty($nome_completo) || empty($cpf)) {
        $error = 'Por favor, preencha todos os campos.';
    } else {
        // Inclui e chama a API de pagamento
        require_once APP_ROOT . '/api/PaymentApiService.php';

        $userData = [
            'id' => $_SESSION['user_id'],
            'nome' => $nome_completo,
            'email' => $user_email, // Usa o email da sessão
            'cpf' => $cpf,
        ];
        
        $transactionData = createLiraPayTransaction($plano, $valor, $userData);

        if (!$transactionData || !isset($transactionData['pix']['payload'])) {
            $error = "Erro ao gerar o pagamento. Verifique se a chave da API de pagamento está configurada no painel de administração e tente novamente.";
        } else {
            // Salva a transação pendente no nosso banco de dados
            $stmt = $pdo->prepare("INSERT INTO pagamentos (user_id, transaction_id, external_id, plano, valor, status) VALUES (?, ?, ?, ?, ?, 'PENDING')");
            $stmt->execute([
                $_SESSION['user_id'],
                $transactionData['id'],
                $transactionData['external_id'],
                $plano,
                $valor
            ]);
        }
    }
}

?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto card p-6 sm:p-8 bg-gray-900/50 border-gray-800">
        
        <?php if (!$transactionData): // Se a transação ainda não foi gerada, mostra o formulário ?>
        
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-white">Finalizar Compra</h1>
                <p class="text-muted-foreground mt-2">Plano <span class="text-primary font-semibold"><?php echo htmlspecialchars($plano); ?></span> - R$ <?php echo number_format($valor, 2, ',', '.'); ?></p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-900/50 border border-red-700 text-red-300 text-sm rounded-lg p-3 mb-4 text-center">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/pagamento?plano=<?php echo htmlspecialchars($plano); ?>&valor=<?php echo htmlspecialchars($valor); ?>" class="space-y-4">
                <div>
                    <label for="fullname" class="block text-sm font-medium text-gray-300 mb-1">Nome Completo</label>
                    <input type="text" name="fullname" id="fullname" placeholder="Seu nome como no documento" required class="w-full bg-gray-800 border-gray-700 rounded p-3 text-sm text-white focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user_email); ?>" readonly class="w-full bg-gray-900 border-gray-700 rounded p-3 text-sm text-gray-400 cursor-not-allowed">
                </div>
                
                <div>
                    <label for="document" class="block text-sm font-medium text-gray-300 mb-1">CPF</label>
                    <input type="text" name="document" id="document" placeholder="000.000.000-00" required class="w-full bg-gray-800 border-gray-700 rounded p-3 text-sm text-white focus:ring-primary focus:border-primary">
                </div>

                <button type="submit" class="w-full btn-primary py-3 mt-4">Gerar PIX</button>
            </form>

        <?php else: // Se a transação foi gerada com sucesso, mostra o QR Code ?>

            <div class="text-center">
                <h1 class="text-2xl font-bold text-white">Pague com PIX</h1>
                <p class="text-muted-foreground mt-2">Escaneie o QR Code com o app do seu banco para finalizar a assinatura do plano <span class="text-primary font-semibold"><?php echo htmlspecialchars($plano); ?></span>.</p>
            </div>
            
            <div class="my-6 flex justify-center">
                <div id="qrcode" class="p-4 bg-white rounded-lg"></div>
            </div>

            <div class="space-y-3">
                <label for="pix-payload" class="sr-only">PIX Copia e Cola</label>
                <textarea id="pix-payload" readonly class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-xs text-center text-gray-300 h-24 resize-none"><?php echo htmlspecialchars($transactionData['pix']['payload']); ?></textarea>
                <button onclick="copyPixPayload()" class="w-full btn-primary py-2 flex items-center justify-center gap-2">
                    <i data-lucide="copy" class="w-4 h-4"></i>
                    Copiar Código PIX
                </button>
            </div>
            
            <p class="text-xs text-muted-foreground mt-6 text-center">Após o pagamento ser confirmado, seu plano será ativado automaticamente.</p>

        <?php endif; ?>

    </div>
</div>

<?php if ($transactionData): ?>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo addslashes($transactionData['pix']['payload']); ?>",
            width: 200, height: 200,
        });
        lucide.createIcons();
    });
    function copyPixPayload() {
        const textarea = document.getElementById('pix-payload');
        textarea.select();
        document.execCommand('copy');
        alert('Código PIX copiado!');
    }
</script>
<?php endif; ?>