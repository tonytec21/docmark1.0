<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Login</title>
    <link rel="icon" href="img/logo.png" type="image/png">
    <link rel="stylesheet" href="css/style-login.css">

</head>
<body>
    
<?php
    // Verifica se há uma mensagem de erro
    if (isset($_GET['error']) && $_GET['error'] == 1) {
        echo '<div class="error-popup" id="errorPopup">';
        echo '<p class="error-message">Credenciais inválidas. Tente novamente.</p><br>';
        echo '<button class="close-btn" onclick="closeErrorPopup()">Ok</button>';
        echo '</div>';
    }
    ?>

    <form action="login_process.php" class="login" method="post">
    <h1>DocMark</h1>
        <h2>Acesso restrito</h2>
        <label for="empresa_id">Escolha seu cartório:</label>
        <select name="empresa_id" required>
            <option value="1">Esperantinópolis - Ofício Único</option>
        </select>
        <br>
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" required>
        <br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>
        <br>
        <input type="submit" class="bt" value="Entrar">
    </form>

    <script>
        // Função para fechar a mensagem de erro
        function closeErrorPopup() {
            var errorPopup = document.getElementById('errorPopup');
            errorPopup.style.display = 'none';
        }

        // Função para mostrar a mensagem de erro como pop-up
        window.onload = function() {
            var errorPopup = document.getElementById('errorPopup');
            errorPopup.style.display = 'flex';
        };
    </script>

</body>
</html>
