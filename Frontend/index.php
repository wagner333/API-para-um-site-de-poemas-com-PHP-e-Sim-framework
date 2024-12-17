<?php
// URL da sua API de login
$url = "http://localhost:8000/login"; 

// Mensagens de erro ou sucesso
$login_message = "";
$error_message = "";

// Verifica se o formulário de login foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Preparando os dados para enviar via POST para a API
    $data = json_encode([
        'email' => $username,
        'password' => $password
    ]);

    // Inicializa cURL
    $ch = curl_init($url);
    
    // Configurações para enviar a requisição POST
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    // Envia a requisição para a API
    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Verifica o status da resposta
    if ($status_code == 200) {
        $response_data = json_decode($response, true);
        $login_message = $response_data['message'] ?? "Login realizado com sucesso!";
    } else {
        $error_data = json_decode($response, true);
        $error_message = $error_data['error'] ?? "Erro desconhecido!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
</head>
<body>

    <h1>Login</h1>
    <form method="POST">
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Entrar</button>
    </form>

    <?php if ($login_message) { ?>
        <p style="color: green;"><?= $login_message ?></p>
    <?php } ?>

    <?php if ($error_message) { ?>
        <p style="color: red;"><?= $error_message ?></p>
    <?php } ?>

</body>
</html>
