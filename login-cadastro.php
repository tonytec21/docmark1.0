<?php
session_start();
// Verifica se o formulário de login foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém as credenciais do formulário
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];
    // Lê os dados de usuários do arquivo JSON
    $data = json_decode(file_get_contents('data.json'), true);

    // Verifica se as credenciais correspondem a um usuário existente
    foreach ($data['usuarios'] as $user) {
        if ($user['usuario'] === $usuario && $user['senha'] === $senha) {
            // Se as credenciais estiverem corretas, redireciona para a página de cadastro
            $_SESSION['usuario'] = $usuario;
            header("Location: cadastrar-usuario.php");
            exit;
        }
    }
    // Se as credenciais estiverem incorretas, exibe uma mensagem de erro
    $error = "Usuário ou senha incorretos. Por favor, tente novamente.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Login</title>
    <link rel="icon" href="img/logo.png" type="image/png">
    <link rel="stylesheet" href="css/style-login.css">

</head>
<body>
        <form class="login" method="post">        
            <h1>DocMark</h1>
            <h2>Acesso restrito</h2>
                <?php if (isset($error)) : ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
                <label for="empresa_id">Login de Cadastro:</label>
                <select name="empresa_id" required>
                    <option value="1">Registro de Imóveis</option>
                </select>
                <br>
                <label for="usuario">Usuário:</label>
                    <input type="text" id="usuario" name="usuario" required><br><br>
                <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required><br><br>
                    <input type="submit" class="bt" value="Entrar">
        </form>
  </body>
</html
