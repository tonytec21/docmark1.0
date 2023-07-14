<?php
// Função para ler as credenciais do arquivo JSON
function lerCredenciais() {
    $dados = array(
        'clientId' => '',
        'hash' => ''
    );

    $credenciaisFile = 'credenciais.json';

    if (file_exists($credenciaisFile)) {
        $credenciaisData = file_get_contents($credenciaisFile);
        $dados = json_decode($credenciaisData, true);
    }

    return $dados;
}

// Função para salvar as credenciais no arquivo JSON
function salvarCredenciais($clientId, $hash) {
    $dados = array(
        'clientId' => $clientId,
        'hash' => $hash
    );

    $credenciaisFile = 'credenciais.json';
    $credenciaisData = json_encode($dados);

    file_put_contents($credenciaisFile, $credenciaisData);
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ler as credenciais do formulário
    $clientId = $_POST['clientId'];
    $hash = $_POST['hash'];

    // Salvar as credenciais no arquivo JSON
    salvarCredenciais($clientId, $hash);
}

// Ler as credenciais do arquivo JSON
$credenciais = lerCredenciais();
$clientId = $credenciais['clientId'];
$hash = $credenciais['hash'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Configurações de Credenciais</title>
</head>
<body>
    <h1>Configurações de Credenciais</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="clientId">Client ID:</label>
        <input type="text" id="clientId" name="clientId" value="<?php echo $clientId; ?>" required><br><br>

        <label for="hash">Hash:</label>
        <input type="text" id="hash" name="hash" value="<?php echo $hash; ?>" required><br><br>

        <input type="submit" value="Salvar">
    </form>
</body>
</html>
