<!DOCTYPE html>
<html>
<head>
    <title>Página de Login</title>
</head>
<body>
    <?php
    // Verifica se há uma mensagem de erro
    if (isset($_GET['error']) && $_GET['error'] == 1) {
        echo '<p style="color: red;">Credenciais inválidas. Tente novamente.</p>';
    }
    ?>
    <h2>Selecione a empresa:</h2>
    <form action="login_process.php" method="post">
        <label for="empresa_id">Selecione a empresa:</label>
        <select name="empresa_id">
            <option value="1">Esperantinópolis</option>
            <option value="2">Bequimão</option>
        </select>
        <br>
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" required>
        <br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>
        <br>
        <input type="submit" value="Entrar">
    </form>
</body>
</html>
