<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Dados enviados pelo formulário
    $empresa_id = $_POST["empresa_id"];
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    // Carregar os dados do arquivo JSON
    $jsonData = file_get_contents('data.json');
    $data = json_decode($jsonData, true);

    // Encontrar o usuário com base no nome de usuário e empresa_id
    $foundUser = null;
    foreach ($data['usuarios'] as $user) {
        if ($user['empresa_id'] == $empresa_id && $user['usuario'] === $usuario) {
            $foundUser = $user;
            break;
        }
    }

    // Verifica se o usuário foi encontrado e se a senha está correta
    if ($foundUser && $senha === $foundUser['senha']) {
        // Credenciais corretas, iniciar a sessão
        session_start();
        $_SESSION["user_id"] = $foundUser['id'];
        $_SESSION["empresa_id"] = $foundUser['empresa_id'];

        

        // Redirecionar para o diretório da empresa
        $empresa = null;
        foreach ($data['empresas'] as $emp) {
            if ($emp['id'] == $empresa_id) {
                $empresa = $emp;
                break;
            }
        }

        if ($empresa) {
            header("Location: " . $empresa['diretorio_index']);
        } else {
            // Redirecionar para uma página de erro ou página padrão caso a empresa não seja encontrada
            header("Location: index.php");
        }
        exit();
    } else {
        // Credenciais inválidas, redirecionar para a página de login com mensagem de erro
        header("Location: login.php?error=1");
        exit();
    }
} else {
    // Se o método de requisição não for POST, redirecionar para a página de login
    header("Location: login.php");
    exit();
}