<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis do .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = AppFactory::create();

// Middleware para manipular erros
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Rotas
require __DIR__ . '/../src/Controllers/UserController.php';

$app->run();
