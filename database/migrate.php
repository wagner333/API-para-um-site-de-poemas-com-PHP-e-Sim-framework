<?php
require __DIR__ . '/db.php';

try {
    // Estrutura da tabela 'users'
    $sqlUsers = "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE
        )
    ";

    // Estrutura da tabela 'posts' (exemplo adicional)
    $sqlPosts = "
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ";

    // Estrutura da tabela 'poemas'
    $sqlPoemas = "
        CREATE TABLE IF NOT EXISTS poemas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            titulo TEXT NOT NULL,
            conteudo TEXT NOT NULL,
            autor TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";

    // Execução das tabelas
    $pdo->exec($sqlUsers);
    $pdo->exec($sqlPosts);
    $pdo->exec($sqlPoemas);

    echo "Tabelas criadas com sucesso!";
} catch (PDOException $e) {
    die("Erro ao criar tabelas: " . $e->getMessage());
}
