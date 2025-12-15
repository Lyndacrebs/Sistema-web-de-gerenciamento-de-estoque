-- ========================================================
-- SISTEMA DE GESTÃO DE ESTOQUE – SAEP_DB
-- Script de Criação e População do Banco de Dados
-- ========================================================

-- Exclui o banco se já existir (opcional, para recomeçar do zero)
DROP DATABASE IF EXISTS saep_db;

-- Cria o banco de dados
CREATE DATABASE saep_db;

-- Usa o banco
USE saep_db;

-- ========================================================
-- TABELA: usuarios
-- ========================================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- TABELA: produtos
-- ========================================================
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    codigo VARCHAR(50) UNIQUE,
    estoque_minimo INT NOT NULL DEFAULT 0,
    estoque_atual INT NOT NULL DEFAULT 0,
    CHECK (estoque_minimo >= 0),
    CHECK (estoque_atual >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- TABELA: movimentacoes
-- ========================================================
CREATE TABLE movimentacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    tipo ENUM('entrada', 'saida') NOT NULL,
    quantidade INT NOT NULL CHECK (quantidade > 0),
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- DADOS DE EXEMPLO – USUÁRIOS
-- ========================================================
INSERT INTO usuarios (nome, email, senha) VALUES
('João Silva', 'joao@almox.com', '123'),
('Maria Oliveira', 'maria@almox.com', '123'),
('Carlos Souza', 'carlos@almox.com', '123');

-- ========================================================
-- DADOS DE EXEMPLO – PRODUTOS (com tipos reais de ferramentas)
-- ========================================================
INSERT INTO produtos (nome, tipo, codigo, estoque_minimo, estoque_atual) VALUES
('Martelo de Borracha 500g', 'Martelo', 'MAR-BOR-500', 10, 25),
('Martelo de Aço Forjado 1kg', 'Martelo', 'MAR-ACO-1000', 8, 12),
('Martelo de Nylon 300g', 'Martelo', 'MAR-NYL-300', 5, 3),
('Chave de Fenda Philips #2 Imantada', 'Chave de Fenda', 'CHV-PH2-IM', 20, 35),
('Chave de Fenda Plana 6x100mm Isolada 1000V', 'Chave de Fenda', 'CHV-PL6-ISO', 15, 8),
('Chave de Fenda Precision Micro', 'Chave de Fenda', 'CHV-PREC-MIC', 10, 18),
('Alicate Universal Cromado 8"', 'Alicate', 'ALI-UNI-8', 12, 20),
('Alicate de Corte Diagonal 6"', 'Alicate', 'ALI-CORTE-6', 10, 7),
('Alicate de Bico Fino 7"', 'Alicate', 'ALI-BICO-7', 8, 15),
('Chave Inglesa Ajustável 10"', 'Chave Inglesa', 'CHV-ING-10', 10, 14),
('Chave Inglesa Ajustável 12"', 'Chave Inglesa', 'CHV-ING-12', 8, 6),
('Serrote de Arco 12" 24 dentes/pol', 'Serrote', 'SERR-12-24', 5, 9),
('Lima Metálica Redonda 8"', 'Lima', 'LIMA-RED-8', 6, 4),
('Escalpel Bicudo 5mm', 'Ferramenta de Corte', 'ESC-BIC-5', 10, 16),
('Chave Allen Jogo 9 peças', 'Chave Allen', 'CHV-ALLEN-9', 5, 0);

-- ========================================================
-- DADOS DE EXEMPLO – MOVIMENTAÇÕES INICIAIS
-- ========================================================
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_hora) VALUES
(1, 1, 'entrada', 30, '2025-04-01 09:00:00'),
(2, 2, 'entrada', 15, '2025-04-02 10:30:00'),
(3, 1, 'entrada', 5, '2025-04-03 14:15:00'),
(3, 2, 'saida', 2, '2025-04-04 11:20:00'),
(5, 3, 'saida', 7, '2025-04-04 16:45:00'),
(8, 1, 'saida', 3, '2025-04-05 08:30:00'),
(10, 2, 'entrada', 20, '2025-04-05 13:00:00');

-- ========================================================
-- FIM DO SCRIPT
-- ========================================================