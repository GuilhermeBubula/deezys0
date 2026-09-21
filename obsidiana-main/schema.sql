CREATE DATABASE IF NOT EXISTS deezys CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE deezys;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    cnpj VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL,
    setor VARCHAR(100) NOT NULL,
    endereco VARCHAR(255),
    created_at DATETIME NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    empresa_id INT NOT NULL,
    descricao TEXT NOT NULL,
    referencias VARCHAR(255),
    tipo_servico VARCHAR(100) NOT NULL,
    prazo DATE NULL,
    comentario TEXT,
    status ENUM('pendente', 'em_andamento', 'concluido') NOT NULL DEFAULT 'pendente',
    created_at DATETIME NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE
);
