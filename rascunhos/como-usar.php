<!DOCTYPE html>
<html>
<head>
  <title>DocMark - Como Usar</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="header">
    <div class="logo">
      <br>
      <img src="img/logo.png" alt="Logomarca do Software">
      <h1>DocMark</h1>
    </div>
    <nav class="menu">
      <ul>
        <li><a href="index.php">Início</a></li>
        <li><a href="pdf-para-tiff.php">Converter PDF para TIFF</a></li>
        <li><a href="tiff-para-pdf.php">Converter TIFF para PDF</a></li>
        <li><a href="contato.php">Contato</a></li>
        <li><a href="como-usar.php">Como Usar</a></li>
        <li><a href="sobre.php">Sobre</a></li>
      </ul>
    </nav>
  </div>
  <div class="container">
    <h3>COMO USAR O DOCMARK</h3>
    <h2>Passo 1: Selecione o arquivo PDF</h2>
    <p>Para começar, clique no botão "Escolher Arquivo" e selecione o arquivo PDF que deseja adicionar o carimbo digital.</p>

    <h2>Passo 2: Insira o texto do carimbo digital</h2>
    <p>Após selecionar o arquivo PDF, digite o numero do CNM que deseja inserir como carimbo digital.</p>

    <h2>Passo 3: Adicione o carimbo</h2>
    <p>Depois de selecionar o arquivo PDF e inserir o texto do carimbo digital, clique no botão "Adicionar Carimbo". O software irá processar o arquivo e adicionar o carimbo digital em todas as páginas do PDF.</p>

    <h2>Passo 4: Download do PDF com o carimbo digital</h2>
    <p>Após adicionar o carimbo, o novo arquivo PDF com o carimbo digital será gerado. O arquivo será baixado automaticamente após o prcessamento.</p>

    <h2>Observações</h2>
    <p>- Certifique-se de selecionar um arquivo PDF válido e que o texto do carimbo esteja correto antes de adicionar o carimbo.</p>
    <p>- O carimbo digital será inserido no canto superior direito de todas as páginas do PDF.</p>
  </div>
  <footer>
    <div class="container">
    <a style="color: #fff;text-decoration: none"  href="https://backupcloud.site/" target="_blank"> <p>&copy; <span id="year"></span> DocMark | By Backup Cloud. Todos os direitos reservados.</p></a>
    </div>
  </footer>

  <script>
    // Obtém o ano atual e insere no elemento de ID "year"
    document.getElementById("year").textContent = new Date().getFullYear();
  </script>
</body>
</html>
