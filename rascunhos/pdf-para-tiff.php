<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém o caminho digitado pelo usuário
    $pasta_pdf = $_POST["pasta_pdf"];

    // Executa o arquivo em lote apenas se a tarefa não foi executada anteriormente nesta sessão
    if (!isset($_SESSION["conversao_concluida"])) {
        $batFile = 'PDF-para-MultiTIFF.bat "' . $pasta_pdf . '"';
        exec($batFile);

        // Define a variável de sessão para indicar que a tarefa foi concluída
        $_SESSION["conversao_concluida"] = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>DocMark - PDF para Multi-TIFF</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    
</head>
<body>
    <header class="header">
        <div class="logo">
            <br>
            <img src="img/logo.png" alt="Logomarca do Software">
            <h1>DocMark</h1>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="cnm/index.php">Início</a></li>
                <li><a href="pdf-para-tiff/index.php">Converter PDF para TIFF</a></li>
                <li><a href="single-tif-para-pdf/index.php">Converter TIFF para PDF</a></li>
                <li><a href="contato.php">Contato</a></li>
                
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="cnm/configuracao.php">Configuração</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h3>CONVERSOR DE PDF PARA MULTI-TIFF</h3>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="pasta_pdf">Indique o caminho da pasta que contém os arquivos PDF:</label>
            <input type="text" id="pasta_pdf" name="pasta_pdf">
            <input type="submit" value="Converter">
        </form>

        <?php
        if (isset($_SESSION["conversao_concluida"])) {
            echo '<script>document.getElementById("loading").style.display = "block";</script>';
            echo '<h3>Conversão concluída com sucesso</h3>';
            unset($_SESSION["conversao_concluida"]); // Remove a variável de sessão para permitir a execução futura da tarefa
        } else {
            echo '<div class="conversion-message">';
            echo '<h3>Sem conversão realizada ou falha na conversão.</h3>';
            echo '</div>';
        }
        ?>

        <p class="observation">Observação: Os arquivos Multi-TIFF serão salvos na mesma pasta indicada onde estão os arquivos PDF.</p>
    
    <br>
    <br>
        <hr>
    <br>
    <br>
    <h3>COMO USAR O CONVERSOR DE PDF PARA TIFF</h3>

    <h2>Passo 1: Executando a Conversão</h2>
    <p>
      No campo "Indique o caminho da pasta que contém os arquivos PDF:" digite o caminho completo para a pasta que contém os arquivos PDF que você deseja converter.<br>
      Clique no botão "Converter" para iniciar o processo de conversão.<br>
      O conversor irá processar os arquivos PDF na pasta especificada e converter cada arquivo em uma imagem TIFF no formato multi-TIFF.<br>
      Aguarde até que o processo de conversão seja concluído. O tempo necessário dependerá da quantidade e do tamanho dos arquivos PDF a serem convertidos, bem como da capacidade do seu servidor.
    </p>

    <h2>Passo 2: Resultados da Conversão</h2>
    <p>
      Após a conclusão da conversão, você verá uma mensagem informando que a conversão foi concluída com sucesso.<br>
      Os arquivos TIFF convertidos serão salvos na mesma pasta em que os arquivos PDF originais estão localizados. Os nomes dos arquivos TIFF serão os mesmos dos arquivos PDF, com a extensão alterada para ".tiff".
    </p>

    <h3>Observações Importantes:</h3>
    <ul>
      <li>Certifique-se de fornecer o caminho correto para a pasta dos arquivos PDF. Verifique se o caminho é válido e se a pasta contém os arquivos PDF que você deseja converter.</li>
      <li>O conversor de PDF para TIFF foi projetado para converter arquivos PDF em imagens TIFF no formato multi-TIFF. Certifique-se de que o formato TIFF atenda aos requisitos específicos do seu caso de uso.</li>
      <li>Este conversor é adequado para conversões em massa.</li>
      <li>Tenha em mente que a velocidade e o desempenho da conversão podem variar dependendo do tamanho e do número de arquivos PDF a serem convertidos, bem como da capacidade do seu servidor.</li>
    </ul>
  </div>

  
</body>
</html>
