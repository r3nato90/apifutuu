-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 17/10/2025 às 09:49
-- Versão do servidor: 11.8.3-MariaDB-log
-- Versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u864690811_apifutebol`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `analises`
--

CREATE TABLE `analises` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `home_team` varchar(255) DEFAULT NULL,
  `away_team` varchar(255) DEFAULT NULL,
  `league` varchar(255) DEFAULT NULL,
  `match_date` datetime DEFAULT NULL,
  `recommendation` text DEFAULT NULL,
  `confidence` int(11) DEFAULT NULL,
  `reasoning` text DEFAULT NULL,
  `statistics` text DEFAULT NULL,
  `detailed_stats` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detailed_stats`)),
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Analise_Partida`
--

CREATE TABLE `Analise_Partida` (
  `id` int(11) NOT NULL,
  `jogo_id` int(11) DEFAULT NULL,
  `analise_ataque` text DEFAULT NULL,
  `analise_defesa` text DEFAULT NULL,
  `fatores_chave` text DEFAULT NULL,
  `fatores_decisivos` text DEFAULT NULL,
  `riscos` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `Analise_Partida`
--

INSERT INTO `Analise_Partida` (`id`, `jogo_id`, `analise_ataque`, `analise_defesa`, `fatores_chave`, `fatores_decisivos`, `riscos`) VALUES
(1, 1, 'O Palmeiras tem um ataque forte, mas extremamente influenciado por um único jogo...', 'Ambas as defesas são vulneráveis...', 'O fator teoricamente favorece o Palmeiras...', 'O São Paulo tem se mostrado inconsistente...', 'A inconsistência do Palmeiras é um risco...');

-- --------------------------------------------------------

--
-- Estrutura para tabela `api_configs`
--

CREATE TABLE `api_configs` (
  `config_key` varchar(255) NOT NULL,
  `config_value` text DEFAULT NULL,
  `last_updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `api_configs`
--

INSERT INTO `api_configs` (`config_key`, `config_value`, `last_updated`) VALUES
('FOOTBALL_API_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2Nlc3NLZXkiOiIxMjM0NTY3OCIsImlhdCI6MTc2MDY1MTM5M30.LXSNc1XC0dqsJthth3IyAWLrH5AboyE1b33wGDC-QTk', '2025-10-16 22:11:36'),
('GEMINI_API_KEY', 'AIzaSyC84XsOQkpWAVmPYW7A0qOgso1b9rrb_IU', '2025-10-16 20:35:31'),
('LIRAPAY_API_SECRET', 'sk_21dc45b0fb7584fce751295be40296a7466c4d85ea43620a09d9ab0843f0c61bd6fd5240d0a4905f101424025ac7e9a7e7fa10d358a3aa30058c898c16a86829', '2025-10-16 20:45:44');

-- --------------------------------------------------------

--
-- Estrutura para tabela `Apostas`
--

CREATE TABLE `Apostas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `jogo_id` int(11) DEFAULT NULL,
  `tipo_aposta` varchar(255) DEFAULT NULL,
  `resultado_esperado` varchar(255) DEFAULT NULL,
  `data_aposta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Configuracoes_API`
--

CREATE TABLE `Configuracoes_API` (
  `chave` varchar(255) NOT NULL,
  `valor` text DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `Configuracoes_API`
--

INSERT INTO `Configuracoes_API` (`chave`, `valor`, `descricao`) VALUES
('FOOTBALL_API_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2Nlc3NLZXkiOiIxMjM0NTY3OCIsImlhdCI6MTc2MDY1MTM5M30.LXSNc1XC0dqsJthth3IyAWLrH5AboyE1b33wGDC-QTk', 'Chave da API do Futebol'),
('GEMINI_API_KEY', 'AIzaSyC84XsOQkpWAVmPYW7A0qOgso1b9rrb_IU', 'Chave da API do Gemini'),
('LIRAPAY_API_SECRET', 'sk_21dc45b0fb7584fce751295be40296a7466c4d85ea43620a09d9ab0843f0c61bd6fd5240d0a4905f101424025ac7e9a7e7fa10d358a3aa30058c898c16a86829', 'Chave secreta da API LiraPay'),
('LIRAPAY_API_URL', 'https://api.lirapaybr.com', 'URL da API de Pagamento LiraPay'),
('LIRAPAY_WEBHOOK_URL', 'https://teste1.1512bet.online/webhook_pagamento.php', 'URL do Webhook de Pagamento');

-- --------------------------------------------------------

--
-- Estrutura para tabela `Desfalques`
--

CREATE TABLE `Desfalques` (
  `id` int(11) NOT NULL,
  `jogador_id` int(11) DEFAULT NULL,
  `jogo_id` int(11) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Estatisticas`
--

CREATE TABLE `Estatisticas` (
  `id` int(11) NOT NULL,
  `jogo_id` int(11) DEFAULT NULL,
  `finalizacoes_totais` int(11) DEFAULT NULL,
  `finalizacoes_no_gol` int(11) DEFAULT NULL,
  `finalizacoes_fora` int(11) DEFAULT NULL,
  `passes_totais` int(11) DEFAULT NULL,
  `passes_completos` int(11) DEFAULT NULL,
  `desarmes` int(11) DEFAULT NULL,
  `pressao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estatisticas_detalhadas`
--

CREATE TABLE `estatisticas_detalhadas` (
  `id` int(11) NOT NULL,
  `analise_id` int(11) NOT NULL,
  `home_shots_total` int(11) DEFAULT 0,
  `home_shots_on_target` int(11) DEFAULT 0,
  `home_shots_off_target` int(11) DEFAULT 0,
  `home_shots_blocked` int(11) DEFAULT 0,
  `away_shots_total` int(11) DEFAULT 0,
  `away_shots_on_target` int(11) DEFAULT 0,
  `away_shots_off_target` int(11) DEFAULT 0,
  `away_shots_blocked` int(11) DEFAULT 0,
  `home_passes_total` int(11) DEFAULT 0,
  `home_passes_accuracy` int(11) DEFAULT 0,
  `home_key_passes` int(11) DEFAULT 0,
  `away_passes_total` int(11) DEFAULT 0,
  `away_passes_accuracy` int(11) DEFAULT 0,
  `away_key_passes` int(11) DEFAULT 0,
  `home_tackles` int(11) DEFAULT 0,
  `home_interceptions` int(11) DEFAULT 0,
  `home_clearances` int(11) DEFAULT 0,
  `home_fouls_committed` int(11) DEFAULT 0,
  `away_tackles` int(11) DEFAULT 0,
  `away_interceptions` int(11) DEFAULT 0,
  `away_clearances` int(11) DEFAULT 0,
  `away_fouls_committed` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Estatisticas_Jogador`
--

CREATE TABLE `Estatisticas_Jogador` (
  `id` int(11) NOT NULL,
  `jogador_id` int(11) DEFAULT NULL,
  `jogo_id` int(11) DEFAULT NULL,
  `gols` int(11) DEFAULT NULL,
  `assistencias` int(11) DEFAULT NULL,
  `passes_totais` int(11) DEFAULT NULL,
  `passes_completos` int(11) DEFAULT NULL,
  `desarmes` int(11) DEFAULT NULL,
  `faltas_cometidas` int(11) DEFAULT NULL,
  `faltas_sofridas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Estatisticas_Jogo`
--

CREATE TABLE `Estatisticas_Jogo` (
  `id` int(11) NOT NULL,
  `jogo_id` int(11) DEFAULT NULL,
  `escanteios_time_casa` int(11) DEFAULT NULL,
  `escanteios_time_fora` int(11) DEFAULT NULL,
  `cartões_amarelos_time_casa` int(11) DEFAULT NULL,
  `cartões_amarelos_time_fora` int(11) DEFAULT NULL,
  `cartões_vermelhos_time_casa` int(11) DEFAULT NULL,
  `cartões_vermelhos_time_fora` int(11) DEFAULT NULL,
  `finalizacoes_time_casa` int(11) DEFAULT NULL,
  `finalizacoes_time_fora` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `Estatisticas_Jogo`
--

INSERT INTO `Estatisticas_Jogo` (`id`, `jogo_id`, `escanteios_time_casa`, `escanteios_time_fora`, `cartões_amarelos_time_casa`, `cartões_amarelos_time_fora`, `cartões_vermelhos_time_casa`, `cartões_vermelhos_time_fora`, `finalizacoes_time_casa`, `finalizacoes_time_fora`) VALUES
(0, 1, 10, 10, 5, 5, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `Historico_Analises`
--

CREATE TABLE `Historico_Analises` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `jogo_id` int(11) DEFAULT NULL,
  `analise` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`analise`)),
  `data_analise` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Jogadores`
--

CREATE TABLE `Jogadores` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `time_id` int(11) DEFAULT NULL,
  `posicao` varchar(50) DEFAULT NULL,
  `nacionalidade` varchar(100) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Jogos`
--

CREATE TABLE `Jogos` (
  `id` int(11) NOT NULL,
  `time_casa_id` int(11) DEFAULT NULL,
  `time_fora_id` int(11) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `resultado` varchar(20) DEFAULT NULL,
  `gols_time_casa` int(11) DEFAULT NULL,
  `gols_time_fora` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `Jogos`
--

INSERT INTO `Jogos` (`id`, `time_casa_id`, `time_fora_id`, `data`, `resultado`, `gols_time_casa`, `gols_time_fora`) VALUES
(1, NULL, NULL, '2025-10-16 22:49:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos`
--

CREATE TABLE `pagamentos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `plano` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `planos`
--

CREATE TABLE `planos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `limite_analises` int(11) NOT NULL,
  `disponivel` tinyint(1) NOT NULL DEFAULT 1,
  `destaque` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `planos`
--

INSERT INTO `planos` (`id`, `nome`, `preco`, `limite_analises`, `disponivel`, `destaque`) VALUES
(1, 'Starter', 55.00, 10, 1, 0),
(2, 'Pro', 77.00, 25, 0, 1),
(3, 'Elite', 200.00, 99999, 1, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `Times`
--

CREATE TABLE `Times` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `plano` varchar(50) DEFAULT 'Starter',
  `analises_diarias` int(11) DEFAULT 10,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `telefone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `is_admin`, `status`, `plano`, `analises_diarias`, `created_at`, `telefone`) VALUES
(1, 'Renato Gomes da Conceição Júnior', 'r3nato90@hotmail.com', '$2y$10$o8z0ebzIizqyBzSrrSBzROHRxetjxOUDX.bk8Rod.0QeTFaY5wGvG', 1, 'approved', 'Starter', 10, '2025-10-16 20:24:04', '');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `analises`
--
ALTER TABLE `analises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `Analise_Partida`
--
ALTER TABLE `Analise_Partida`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jogo_id` (`jogo_id`);

--
-- Índices de tabela `api_configs`
--
ALTER TABLE `api_configs`
  ADD PRIMARY KEY (`config_key`);

--
-- Índices de tabela `Apostas`
--
ALTER TABLE `Apostas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jogo_id` (`jogo_id`);

--
-- Índices de tabela `Configuracoes_API`
--
ALTER TABLE `Configuracoes_API`
  ADD PRIMARY KEY (`chave`);

--
-- Índices de tabela `Desfalques`
--
ALTER TABLE `Desfalques`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jogador_id` (`jogador_id`),
  ADD KEY `jogo_id` (`jogo_id`);

--
-- Índices de tabela `Estatisticas`
--
ALTER TABLE `Estatisticas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jogo_id` (`jogo_id`);

--
-- Índices de tabela `estatisticas_detalhadas`
--
ALTER TABLE `estatisticas_detalhadas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `analise_id` (`analise_id`);

--
-- Índices de tabela `Estatisticas_Jogador`
--
ALTER TABLE `Estatisticas_Jogador`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jogador_id` (`jogador_id`),
  ADD KEY `jogo_id` (`jogo_id`);

--
-- Índices de tabela `Estatisticas_Jogo`
--
ALTER TABLE `Estatisticas_Jogo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jogo_id` (`jogo_id`);

--
-- Índices de tabela `Historico_Analises`
--
ALTER TABLE `Historico_Analises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `jogo_id` (`jogo_id`);

--
-- Índices de tabela `Jogadores`
--
ALTER TABLE `Jogadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_id` (`time_id`);

--
-- Índices de tabela `Jogos`
--
ALTER TABLE `Jogos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_casa_id` (`time_casa_id`),
  ADD KEY `time_fora_id` (`time_fora_id`);

--
-- Índices de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `planos`
--
ALTER TABLE `planos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `Times`
--
ALTER TABLE `Times`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `analises`
--
ALTER TABLE `analises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Analise_Partida`
--
ALTER TABLE `Analise_Partida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `estatisticas_detalhadas`
--
ALTER TABLE `estatisticas_detalhadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `planos`
--
ALTER TABLE `planos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `analises`
--
ALTER TABLE `analises`
  ADD CONSTRAINT `analises_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `Analise_Partida`
--
ALTER TABLE `Analise_Partida`
  ADD CONSTRAINT `Analise_Partida_ibfk_1` FOREIGN KEY (`jogo_id`) REFERENCES `Jogos` (`id`);

--
-- Restrições para tabelas `Apostas`
--
ALTER TABLE `Apostas`
  ADD CONSTRAINT `Apostas_ibfk_1` FOREIGN KEY (`jogo_id`) REFERENCES `Jogos` (`id`);

--
-- Restrições para tabelas `Desfalques`
--
ALTER TABLE `Desfalques`
  ADD CONSTRAINT `Desfalques_ibfk_1` FOREIGN KEY (`jogador_id`) REFERENCES `Jogadores` (`id`),
  ADD CONSTRAINT `Desfalques_ibfk_2` FOREIGN KEY (`jogo_id`) REFERENCES `Jogos` (`id`);

--
-- Restrições para tabelas `Estatisticas`
--
ALTER TABLE `Estatisticas`
  ADD CONSTRAINT `Estatisticas_ibfk_1` FOREIGN KEY (`jogo_id`) REFERENCES `Jogos` (`id`);

--
-- Restrições para tabelas `estatisticas_detalhadas`
--
ALTER TABLE `estatisticas_detalhadas`
  ADD CONSTRAINT `estatisticas_detalhadas_ibfk_1` FOREIGN KEY (`analise_id`) REFERENCES `analises` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `Estatisticas_Jogador`
--
ALTER TABLE `Estatisticas_Jogador`
  ADD CONSTRAINT `Estatisticas_Jogador_ibfk_1` FOREIGN KEY (`jogador_id`) REFERENCES `Jogadores` (`id`),
  ADD CONSTRAINT `Estatisticas_Jogador_ibfk_2` FOREIGN KEY (`jogo_id`) REFERENCES `Jogos` (`id`);

--
-- Restrições para tabelas `Estatisticas_Jogo`
--
ALTER TABLE `Estatisticas_Jogo`
  ADD CONSTRAINT `Estatisticas_Jogo_ibfk_1` FOREIGN KEY (`jogo_id`) REFERENCES `Jogos` (`id`);

--
-- Restrições para tabelas `Historico_Analises`
--
ALTER TABLE `Historico_Analises`
  ADD CONSTRAINT `Historico_Analises_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `Usuarios` (`id`),
  ADD CONSTRAINT `Historico_Analises_ibfk_2` FOREIGN KEY (`jogo_id`) REFERENCES `Jogos` (`id`);

--
-- Restrições para tabelas `Jogadores`
--
ALTER TABLE `Jogadores`
  ADD CONSTRAINT `Jogadores_ibfk_1` FOREIGN KEY (`time_id`) REFERENCES `Times` (`id`);

--
-- Restrições para tabelas `Jogos`
--
ALTER TABLE `Jogos`
  ADD CONSTRAINT `Jogos_ibfk_1` FOREIGN KEY (`time_casa_id`) REFERENCES `Times` (`id`),
  ADD CONSTRAINT `Jogos_ibfk_2` FOREIGN KEY (`time_fora_id`) REFERENCES `Times` (`id`);

--
-- Restrições para tabelas `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD CONSTRAINT `pagamentos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
