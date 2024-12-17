<?php
try {
    $pdo = new PDO("sqlite:/home/wagner/Documentos/laravel estudo/minha-api/API/database/database.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
