SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Criação e Seleção do Banco de Dados: `music`
--
CREATE DATABASE IF NOT EXISTS `music`
    DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `music`;

--
-- Estrutura para tabela `musicas`
--
CREATE TABLE `musicas` (
    `id` INT(11) NOT NULL,
    `titulo` VARCHAR(255) NOT NULL,
    `caminho_arquivo` VARCHAR(512) NOT NULL,
    `artista` VARCHAR(100) DEFAULT NULL,
    `album` VARCHAR(100) DEFAULT NULL,
    `duracao_segundos` INT(11) DEFAULT NULL,
    `tamanho_bytes` BIGINT(20) DEFAULT NULL,
    `uploaded_by` INT(11) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estrutura para tabela `tentativas_login`
--
CREATE TABLE `tentativas_login` (
    `id` INT(11) NOT NULL,
    `usuario` VARCHAR(50) NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `success` TINYINT(1) DEFAULT 0,
    `attempted_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Inserção de dados para a tabela `tentativas_login`
--
INSERT INTO `tentativas_login` (`id`, `usuario`, `ip_address`, `success`, `attempted_at`) VALUES
(1, 'infogyba', '127.0.0.1', 0, '2025-11-10 18:22:05'),
(2, 'infogyba', '127.0.0.1', 0, '2025-11-10 18:30:03');

--
-- Estrutura para tabela `usuarios`
--
CREATE TABLE `usuarios` (
    `id` INT(11) NOT NULL,
    `usuario` VARCHAR(50) NOT NULL,
    `senha` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    `last_login` TIMESTAMP NULL DEFAULT NULL,
    `status` ENUM('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Inserção de dados para a tabela `usuarios`
--
INSERT INTO `usuarios` (`id`, `usuario`, `senha`, `email`, `created_at`, `updated_at`, `last_login`, `status`) VALUES
(1, 'Admin', '$2y$12$H0qNhe0LGeAYo4EZiqibZOxNVXUnnDlbSDlkXrxslChkaWn/vmC/S', 'infogyba@devel.com', '2025-11-10 18:20:47', '2025-11-10 18:20:47', NULL, 'active'),
(2, 'infogyba', '$2y$12$vlUOXpa3Z9LHIKmzalb4oOBjvlmal.HQAbAKr.CHGVUoSCh9hXQIC', 'infogyba@devel.com', '2025-11-10 18:20:47', '2025-11-10 18:20:47', NULL, 'active');

--
-- Índices e Chaves Primárias
--
-- Tabela `musicas`
ALTER TABLE `musicas`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `caminho_arquivo` (`caminho_arquivo`),
    ADD KEY `uploaded_by` (`uploaded_by`),
    ADD FULLTEXT KEY `idx_busca` (`titulo`,`artista`,`album`);

-- Tabela `tentativas_login`
ALTER TABLE `tentativas_login`
    ADD PRIMARY KEY (`id`),
    ADD KEY `idx_usuario` (`usuario`),
    ADD KEY `idx_attempted_at` (`attempted_at`);

-- Tabela `usuarios`
ALTER TABLE `usuarios`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `usuario` (`usuario`),
    ADD KEY `idx_usuario` (`usuario`),
    ADD KEY `idx_status` (`status`);

--
-- Configuração de AUTO_INCREMENT
--
ALTER TABLE `musicas`
    MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tentativas_login`
    MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `usuarios`
    MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições de Chaves Estrangeiras
--
ALTER TABLE `musicas`
    ADD CONSTRAINT `musicas_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
