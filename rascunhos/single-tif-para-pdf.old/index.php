<!DOCTYPE html>
<html>
<head>
    <title>DocMark</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../styles.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> -->
</head>
<body>
    <header class="header">
        <div class="logo">
            <br>
            <img src="../img/logo.png" alt="Logo">
            <h1>DocMark</h1>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="../index.php">Carimbo Digital</a></li>
                <li><a href="../pdf-para-tiff/index.php">Converter PDF para TIFF</a></li>
                <li><a href="index.php">Converter TIFF para PDF</a></li>
                <!-- <li><a href="../contato.php">Contato</a></li>
                <li><a href="../sobre.php">Sobre</a></li> -->
                <li><a href="../cnm/configuracao.php">Configuração</a></li>
            </ul>
        </nav>
    </header>

    <div class="container" style="margin-bottom: 15%;">
        <h1>Converter arquivos TIFF para PDF</h1>

        <form action="index.php" method="POST">
            <label for="input_dir">Caminho da pasta com os arquivos TIFF:</label>
            <input type="text" name="input_dir" id="input_dir" required><br>

            <label for="output_dir">Caminho da pasta para salvar os PDFs:</label>
            <input type="text" name="output_dir" id="output_dir" required><br>

            <input type="submit" value="Converter">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input_dir = $_POST['input_dir'];
            $output_dir = $_POST['output_dir'];

            $command = 'Conversor-Single-TIFF-para-PDF.bat';
            $command .= ' "' . $input_dir . '"';
            $command .= ' "' . $output_dir . '"';

            // Executar o comando do arquivo de lote
            $output = shell_exec($command);

            echo '<pre>' . $output . '</pre>';
        }
        ?>
    </div>

    <footer>
    
    <p style="color: #fff;text-decoration: none"> <p><a style="color: #fff;text-decoration: none"  href="https://backupcloud.site/" target="_blank">&copy; <span id="year"></span> DocMark | By Backup Cloud. Todos os direitos reservados.</a></p></p>
    
  </footer>
  
  <script>
    // Obtém o ano atual e insere no elemento de ID "year"
    document.getElementById("year").textContent = new Date().getFullYear();
  </script>
</body>
</html>