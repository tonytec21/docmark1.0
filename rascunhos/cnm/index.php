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
        <li><a href="index.php">Carimbo Digital</a></li>
        <li><a href="../pdf-para-tiff/index.php">Converter PDF para TIFF</a></li>
        <li><a href="../single-tif-para-pdf/index.php">Converter TIFF para PDF</a></li>
        <!-- <li><a href="../contato.php">Contato</a></li>
        <li><a href="../sobre.php">Sobre</a></li> -->
        <li><a href="configuracao.php">Configuração</a></li>
            </ul>
        </nav>
    </header>
    <div class="container" style="margin-bottom: 20%;">
        <h1>Processar arquivo para carimbo digital</h1>
        <form action="processar.php" method="POST" enctype="multipart/form-data">
            <label for="arquivoPDF">Selecione um arquivo PDF:</label>
            <input type="file" name="arquivoPDF" id="arquivoPDF">
            <input type="submit" name="submit" value="Processar">
        </form>
    </div>

    <footer>
    
    <p style="color: #fff;text-decoration: none"> <p><a style="color: #fff;text-decoration: none"  href="https://backupcloud.site/" target="_blank">&copy; <span id="year"></span> DocMark | By Backup Cloud. Todos os direitos reservados.</a></p></p>
    
  </footer>

  <script>
    // Obtém o ano atual e insere no elemento de ID "year"
    document.getElementById("year").textContent = new Date().getFullYear();
  </script>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->

</body>
</html>
