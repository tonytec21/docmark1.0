<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Carimbo digital</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header class="header">
    <div class="orb-container">
            <div class="orb"></div>
        </div>
            <h1>DocMark</h1>
        <nav class="menu">
            <ul>
                <li><a href="index.php">Carimbo Digital</a></li>
                <li><a href="../pdf-para-tiff/index.php">Converter PDF para TIFF</a></li>
                <li><a href="../single-tif-para-pdf/index.php">Converter TIFF para PDF</a></li>
                <!-- <li><a href="../chancela/index.php">Chancela Mecânica</a></li> -->
                <li><a href="configuracao.php">Configuração</a></li>
             </ul>
        </nav>
    </header>
    <div class="container" style="margin-bottom: 17%">
        <h1>Arquivos processados</h1>
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
            echo "<h2>Matrícula nº: " . $numeroMatricula . "</h2>";

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
        echo "<input type='submit' name='submit' value='Gerar o CNM e Carimbar'>";
        echo "</form>";
    }
}
?>

        </div>
        <br>
        <a href="index.php" class="btn-gradient">Processar novos arquivos</a>
    </div>
    <footer>
        <p style="color: #fff;text-decoration: none"> <p><a style="color: #fff;text-decoration: none"  href="https://backupcloud.site/" target="_blank">&copy; <span id="year"></span> DocMark | By Backup Cloud. Todos os direitos reservados.</a></p></p>
    </footer>
    <script>
        // Obtém o ano atual e insere no elemento de ID "year"
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
</body>
</html
