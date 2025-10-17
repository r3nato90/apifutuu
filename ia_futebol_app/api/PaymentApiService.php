<?php
if (!defined('APP_ROOT')) { die('Acesso direto não permitido.'); }

/**
 * Cria uma nova transação de pagamento na LiraPay.
 * @param string $plano O nome do plano (ex: "Pro").
 * @param float $valor O valor do plano.
 * @param array $userData Dados do utilizador (id, nome, email, cpf).
 * @return array|null Retorna os dados da transação ou null em caso de erro.
 */
function createLiraPayTransaction($plano, $valor, $userData) {
    // Busca as credenciais do .env
    $apiUrl = getenv('LIRAPAY_API_URL') . '/v1/transactions';
    $apiSecret = getenv('LIRAPAY_API_SECRET');
    $webhookUrl = getenv('LIRAPAY_WEBHOOK_URL');

    // Validação de segurança
    if (empty($apiSecret)) {
        error_log("LIRAPAY_API_SECRET não está configurado no ficheiro .env");
        return null;
    }
    
    // Identificador único para esta tentativa de pagamento no seu sistema
    $external_id = 'user' . $userData['id'] . '_plan' . $plano . '_' . time();

    // Monta o corpo da requisição conforme a documentação da LiraPay
    $requestBody = [
        'external_id' => $external_id,
        'total_amount' => (float)$valor,
        'payment_method' => 'PIX',
        'webhook_url' => $webhookUrl,
        'items' => [
            [
                'id' => '1',
                'title' => 'Assinatura Plano ' . $plano,
                'description' => 'Acesso ao plano ' . $plano . ' do IA Football',
                'price' => (float)$valor,
                'quantity' => 1,
                'is_physical' => false
            ]
        ],
        'ip' => $_SERVER['REMOTE_ADDR'],
        'customer' => [
            'name' => $userData['nome'],
            'email' => $userData['email'],
            'phone' => '99999999999', // Campo obrigatório, pode ser um placeholder
            'document_type' => 'CPF',
            'document' => preg_replace('/[^0-9]/', '', $userData['cpf']) // Envia apenas os números do CPF
        ]
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'api-secret: ' . $apiSecret // Autenticação conforme a documentação
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 && $httpCode !== 201) { // 201 também significa "Created"
        error_log("Erro ao criar transação LiraPay: HTTP {$httpCode} - Resposta: {$response}");
        return null;
    }

    return json_decode($response, true);
}
?>