<?php
/**
 * Webhook para receber notificações de status de transações da LiraPay.
 * Este endpoint é chamado automaticamente pela API de pagamentos.
 * ATUALIZADO CONFORME A NOVA DOCUMENTAÇÃO.
 */

// Define o caminho para a raiz da aplicação segura
define('APP_ROOT', __DIR__ . '/../ia_futebol_app');

// Carrega os ficheiros essenciais de configuração e banco de dados
require_once APP_ROOT . '/includes/config.php';
require_once APP_ROOT . '/includes/db.php';

// --- Captura e Validação do Payload ---

// Pega o corpo da requisição (payload) enviado pela LiraPay
$payload = file_get_contents('php://input');

// Cria a pasta de logs se ela não existir
$log_dir = APP_ROOT . '/logs';
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0775, true);
}
$log_file = $log_dir . '/webhook_log.txt';

// Escreve em um ficheiro de log para depuração
file_put_contents($log_file, date('Y-m-d H:i:s') . " - Payload Recebido: " . $payload . "\n", FILE_APPEND);

// Converte o JSON recebido para um array PHP
$data = json_decode($payload, true);

// Valida se o payload é um JSON válido e contém os campos essenciais da nova documentação
if (!$data || !isset($data['id']) || !isset($data['status'])) {
    http_response_code(400); // Bad Request
    $error_msg = "Payload inválido ou campos 'id'/'status' em falta.";
    echo $error_msg;
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - ERRO: " . $error_msg . "\n", FILE_APPEND);
    exit();
}

// Extrai as informações do payload conforme a nova documentação
$transaction_id = $data['id'];
$status = $data['status']; // Ex: "AUTHORIZED", "PENDING", etc.

try {
    // Inicia uma transação no banco de dados para garantir a integridade dos dados
    $pdo->beginTransaction();

    // Atualiza o status do pagamento na nossa tabela `pagamentos` usando o ID da transação
    $stmt = $pdo->prepare("UPDATE pagamentos SET status = ? WHERE transaction_id = ?");
    $stmt->execute([$status, $transaction_id]);

    // Se o pagamento foi aprovado ("AUTHORIZED"), atualiza o plano do utilizador
    if ($status === 'AUTHORIZED') {
        // Busca os detalhes da transação para saber qual utilizador e plano atualizar
        $stmt_trans = $pdo->prepare("SELECT user_id, plano FROM pagamentos WHERE transaction_id = ?");
        $stmt_trans->execute([$transaction_id]);
        $transaction = $stmt_trans->fetch(PDO::FETCH_ASSOC);

        if ($transaction) {
            $userId = $transaction['user_id'];
            $novoPlano = $transaction['plano'];

            // Define os limites de análises para cada plano
            $limites = [
                'Starter' => 10,
                'Pro' => 25,
                'Elite' => 999999 // Um número muito alto para simular "ilimitado"
            ];
            $novoLimite = $limites[$novoPlano] ?? 10; // Padrão é Starter

            // Atualiza a tabela de utilizadores com o novo plano e reseta os limites de análise
            $stmt_user = $pdo->prepare(
                "UPDATE usuarios SET plano = ?, limite_analises = ?, analises_restantes = ?, data_reset_analises = ? WHERE id = ?"
            );
            $stmt_user->execute([$novoPlano, $novoLimite, $novoLimite, date('Y-m-d'), $userId]);
            
            file_put_contents($log_file, date('Y-m-d H:i:s') . " - SUCESSO: Utilizador {$userId} atualizado para o plano {$novoPlano}.\n", FILE_APPEND);
        } else {
            file_put_contents($log_file, date('Y-m-d H:i:s') . " - AVISO: Transação {$transaction_id} autorizada, mas não encontrada no banco de dados.\n", FILE_APPEND);
        }
    }

    // Confirma a transação no banco de dados
    $pdo->commit();

} catch (Exception $e) {
    // Em caso de erro, desfaz todas as operações no banco de dados
    $pdo->rollBack();
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - ERRO DE BANCO DE DADOS: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500); // Internal Server Error
    echo "Erro interno ao processar o webhook.";
    exit();
}

// Responde à LiraPay com sucesso para confirmar o recebimento
http_response_code(200);
echo "Webhook recebido com sucesso.";
?>