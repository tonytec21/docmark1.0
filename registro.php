<!DOCTYPE html>
<html>
<head>
    <title>Página de Registro</title>
</head>
<body>
    <h2>Cadastro de Usuário</h2>
    <form action="cadastrar_usuario.php" method="post">
        <label for="empresa_id">Selecione a empresa:</label>
        <select name="empresa_id">
            <option value="1">Esperantinópolis</option>
            <option value="2">Bequimão</option>
            <!-- Adicione mais empresas conforme necessário -->
        </select>
        <br>
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" required>
        <br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>
        <br>
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
