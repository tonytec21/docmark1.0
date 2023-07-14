<!DOCTYPE html>
<html>
<head>
    <title>DocMark - PDF para Multi-TIFF</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <br>
            <img src="../img/logo.png" alt="Logomarca do Software">
            <h1>DocMark</h1>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="../cnm/index.php">Carimbo Digital</a></li>
                <li><a href="index.php">Converter PDF para TIFF</a></li>
                <li><a href="../single-tif-para-pdf/index.php">Converter TIFF para PDF</a></li>
                <!-- <li><a href="../contato.php">Contato</a></li>
                <li><a href="../sobre.php">Sobre</a></li> -->
                <li><a href="../cnm/configuracao.php">Configuração</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Conversão de PDF para TIFF</h1>

        <?php
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtém os caminhos das pastas dos PDFs e dos TIFFs
            $pastaPDF = $_POST['pasta-pdf'];
            $pastaTIFF = $_POST['pasta-tiff'];

            // Inclui o arquivo com a função de conversão
            include 'converter.php';

            // Chama a função para converter os arquivos PDF para TIFF
            $resultado = converterPDFparaTIFF($pastaPDF, $pastaTIFF);

            // Exibe as mensagens de sucesso em um alerta
            foreach ($resultado['sucesso'] as $mensagem) {
                echo "<div class='alert alert-success' role='alert'>{$mensagem}</div>";
            }

            // Exibe as mensagens de erro em um alerta
            foreach ($resultado['erro'] as $mensagem) {
                echo "<div class='alert alert-danger' role='alert'>{$mensagem}</div>";
            }
        }
        ?>

        <div class="form">
            <form method="POST" action="">
                <label for="pasta-pdf">Pasta dos PDFs:</label>
                <input type="text" id="pasta-pdf" name="pasta-pdf" placeholder="Caminho da pasta dos PDFs" required>
                <label for="pasta-tiff">Pasta dos TIFFs:</label>
                <input type="text" id="pasta-tiff" name="pasta-tiff" placeholder="Caminho da pasta dos TIFFs" required>
                <input type="submit" value="Converter">
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
