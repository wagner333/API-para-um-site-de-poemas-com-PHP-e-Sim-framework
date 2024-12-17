<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
//-----------------------------------------
try {
    $pdo = new PDO("sqlite:/home/wagner/Documentos/laravel estudo/minha-api/API/database/database.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
//-------------database---------------
$app->post('/poemas', function (Request $request, Response $response, $args) use ($pdo) {
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
});

// Rota para listar todos os poemas
$app->get('/poemas', function (Request $request, Response $response, $args) use ($pdo) {
    $stmt = $pdo->query("SELECT * FROM poemas");
    $poemas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response->getBody()->write(json_encode($poemas));
    return $response->withHeader('Content-Type', 'application/json');
});

// Rota para mostrar um poema específico
$app->get('/poemas/{id}', function (Request $request, Response $response, $args) use ($pdo) {
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
});

// Rota para atualizar um poema
$app->put('/poemas/{id}', function (Request $request, Response $response, $args) use ($pdo) {
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
});

// Rota para deletar um poema
$app->delete('/poemas/{id}', function (Request $request, Response $response, $args) use ($pdo) {
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
});


$app->post('/users', function (Request $request, Response $response, $args) use ($pdo) {
    $data = json_decode($request->getBody(), true);

    if (isset($data['name']) && isset($data['email']) && isset($data['password'])) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);

        $response->getBody()->write(json_encode(['message' => 'Usuário criado com sucesso!']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['error' => 'Dados insuficientes para criar o usuário']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

$app->get('/users', function (Request $request, Response $response, $args) use ($pdo) {
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response->getBody()->write(json_encode($users));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/users/{id}', function (Request $request, Response $response, $args) use ($pdo) {
    $id = $args['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $response->getBody()->write(json_encode($user));
    } else {
        $response->getBody()->write(json_encode(['error' => 'Usuário não encontrado']));
    }

    return $response->withHeader('Content-Type', 'application/json');
});
$app->put('/users/{id}', function (Request $request, Response $response, $args) use ($pdo) {
    $id = $args['id'];
    $data = json_decode($request->getBody(), true);

    // Verificando se os dados a serem atualizados foram passados
    if (isset($data['name']) || isset($data['email']) || isset($data['password'])) {
        $updateFields = [];
        $updateValues = [];

        if (isset($data['name'])) {
            $updateFields[] = 'name = :name';
            $updateValues['name'] = $data['name'];
        }
        if (isset($data['email'])) {
            $updateFields[] = 'email = :email';
            $updateValues['email'] = $data['email'];
        }
        if (isset($data['password'])) {
            $updateFields[] = 'password = :password';
            $updateValues['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        // Adiciona o ID à lista de valores para a atualização
        $updateValues['id'] = $id;

        // Atualizando no banco de dados
        $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($updateValues);

        $response->getBody()->write(json_encode(['message' => 'Usuário atualizado com sucesso!']));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $response->getBody()->write(json_encode(['error' => 'Dados insuficientes para atualizar o usuário']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});
$app->delete('/users/{id}', function (Request $request, Response $response, $args) use ($pdo) {
    $id = $args['id'];
    
    // Verificando se o usuário existe
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Deletando o usuário
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $response->getBody()->write(json_encode(['message' => 'Usuário deletado com sucesso!']));
    } else {
        $response->getBody()->write(json_encode(['error' => 'Usuário não encontrado']));
    }

    return $response->withHeader('Content-Type', 'application/json');
});
$app->post('/login', function (Request $request, Response $response, $args) use ($pdo) {
    session_start();
    $data = json_decode($request->getBody(), true);

    if (isset($data['email']) && isset($data['password'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe e se a senha está correta
        if ($user && password_verify($data['password'], $user['password'])) {
            // Cria a sessão do usuário
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            $response->getBody()->write(json_encode(['message' => 'Login realizado com sucesso!']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['error' => 'Email ou senha inválidos']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Dados insuficientes']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});
$app->post('/logout', function (Request $request, Response $response, $args) {
    session_start();
    session_destroy();
    $response->getBody()->write(json_encode(['message' => 'Logout realizado com sucesso!']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});
$authMiddleware = function (Request $request, RequestHandler $handler): Response {
    if (!isset($_SESSION['user_id'])) {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['error' => 'Acesso negado. Faça login para continuar.']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
    // Continua o fluxo
    return $handler->handle($request);
};

$app->get('/perfil', function (Request $request, Response $response, $args) {
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
        $user = [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name']
        ];
        $response->getBody()->write(json_encode($user));
        return $response->withHeader('Content-Type', 'application/json');
    }

    $response->getBody()->write(json_encode(['error' => 'Usuário não autenticado']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
})->add($authMiddleware);


