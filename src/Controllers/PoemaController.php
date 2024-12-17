<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Conectar ao banco de dados SQLite
try {
    $pdo = new PDO("sqlite:/home/wagner/Documentos/laravel estudo/minha-api/API/database/database.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}

class PoemaController {

    // Listar todos os poemas
    public function listarPoemas(Request $request, Response $response, $args) {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM poemas");
        $poemas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($poemas));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Exibir um poema específico
    public function mostrarPoema(Request $request, Response $response, $args) {
        global $pdo;
        $id = $args['id'];
        $stmt = $pdo->prepare("SELECT * FROM poemas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $poema = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($poema) {
            $response->getBody()->write(json_encode($poema));
        } else {
            $response->getBody()->write(json_encode(['error' => 'Poema não encontrado']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Criar um novo poema
    public function criarPoema(Request $request, Response $response, $args) {
        global $pdo;
        $data = json_decode($request->getBody(), true);

        if (isset($data['titulo']) && isset($data['conteudo'])) {
            $stmt = $pdo->prepare("INSERT INTO poemas (titulo, conteudo, autor) VALUES (:titulo, :conteudo, :autor)");
            $stmt->execute([
                'titulo' => $data['titulo'],
                'conteudo' => $data['conteudo'],
                'autor' => $data['autor'] ?? null
            ]);

            $response->getBody()->write(json_encode(['message' => 'Poema criado com sucesso!']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['error' => 'Dados insuficientes para criar o poema']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Atualizar um poema
    public function atualizarPoema(Request $request, Response $response, $args) {
        global $pdo;
        $id = $args['id'];
        $data = json_decode($request->getBody(), true);

        if (isset($data['titulo']) || isset($data['conteudo']) || isset($data['autor'])) {
            $updateFields = [];
            $updateValues = [];

            if (isset($data['titulo'])) {
                $updateFields[] = 'titulo = :titulo';
                $updateValues['titulo'] = $data['titulo'];
            }
            if (isset($data['conteudo'])) {
                $updateFields[] = 'conteudo = :conteudo';
                $updateValues['conteudo'] = $data['conteudo'];
            }
            if (isset($data['autor'])) {
                $updateFields[] = 'autor = :autor';
                $updateValues['autor'] = $data['autor'];
            }

            $updateValues['id'] = $id;

            $sql = "UPDATE poemas SET " . implode(', ', $updateFields) . " WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($updateValues);

            $response->getBody()->write(json_encode(['message' => 'Poema atualizado com sucesso!']));
        } else {
            $response->getBody()->write(json_encode(['error' => 'Dados insuficientes para atualizar o poema']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Deletar um poema
    public function deletarPoema(Request $request, Response $response, $args) {
        global $pdo;
        $id = $args['id'];

        $stmt = $pdo->prepare("SELECT * FROM poemas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $poema = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($poema) {
            $stmt = $pdo->prepare("DELETE FROM poemas WHERE id = :id");
            $stmt->execute(['id' => $id]);

            $response->getBody()->write(json_encode(['message' => 'Poema deletado com sucesso!']));
        } else {
            $response->getBody()->write(json_encode(['error' => 'Poema não encontrado']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}

// Criação da aplicação Slim
$app = AppFactory::create();

// Grupo de rotas
$app->group('/poemas', function () use ($app) {
    $app->get('/', PoemaController::class . ':listarPoemas');
    $app->get('/{id}', PoemaController::class . ':mostrarPoema');
    $app->post('', PoemaController::class . ':criarPoema');
    $app->put('/{id}', PoemaController::class . ':atualizarPoema');
    $app->delete('/{id}', PoemaController::class . ':deletarPoema');
});

// Iniciar a aplicação
$app->run();
