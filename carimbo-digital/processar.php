<?php
require_once 'funcoes.php';

// Verifique se a sessão está ativa
verificar_sessao_ativa();
?>
<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Carimbo digital</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/pop-up.js"></script>
</head>
<body>

    <div class="orb-container">
            <div class="orb"></div>
        </div>
            <h1>DocMark - Carimbo Digital</h1>
            <?php include_once("../menu.php");?>
    
    <div class="container" style="margin-bottom: 17%">
        <h3>Arquivos processados</h3>
        <div class="result">

        <?php
// Função para extrair o número de matrícula do nome do arquivo PDF
function extrairNumeroMatricula($nomeArquivoPDF) {
    $padrao = '/(\d+)\.pdf/';
    preg_match($padrao, $nomeArquivoPDF, $matches);

    if (isset($matches[1])) {
        return ltrim($matches[1], '0'); // Remover zeros à esquerda do número
    } else {
        return false;
    }
}

// Verificar se o formulário foi enviado
if (isset($_POST['submit'])) {
    // Verificar se os arquivos foram selecionados
    if (isset($_FILES['arquivoPDF'])) {
        // Array para guardar dados de todos os arquivos
        $todosDados = array();

        foreach ($_FILES['arquivoPDF']['tmp_name'] as $key => $caminhoTemporario) {
            $nomeArquivo = $_FILES['arquivoPDF']['name'][$key];

            // Extrair o número de matrícula do nome do arquivo
            $numeroMatricula = extrairNumeroMatricula($nomeArquivo);

            // Exibir o número de matrícula
            echo "<h3>Matrícula nº: " . $numeroMatricula . "</h3>";

            // Salvar o número de matrícula em um array
            $dados = array('numeroMatricula' => $numeroMatricula, 'nomeArquivo' => $nomeArquivo);
            array_push($todosDados, $dados);

            // Diretório de destino onde o arquivo será salvo
            $diretorioDestino = 'upload/';

            // Verificar se o diretório de destino existe, se não, criar
            if (!is_dir($diretorioDestino)) {
                mkdir($diretorioDestino, 0755, true);
            }

            // Mover o arquivo para o diretório de destino
            $caminhoArquivoDestino = $diretorioDestino . $nomeArquivo;
            if (!move_uploaded_file($caminhoTemporario, $caminhoArquivoDestino)) {
                echo "<h2>Erro ao fazer upload do arquivo.</h2>";
            }
        }

        // Salvar os números de matrícula em um arquivo JSON
        $json = json_encode($todosDados);
        file_put_contents('numero_matricula.json', $json);

        echo "<form action='gerar_cnm.php' method='POST'>";
        echo "<input type='hidden' name='numeroMatricula' value='" . $numeroMatricula . "'>";
        echo "<input type='submit' name='submit' value='Gerar o CNM e Carimbar' onclick='onSubmitForm()'>";
        echo "</form>";
    }
}
?>

        </div>

    </div>

    <?php include_once("../rodape.php");?>
</body>
</html
