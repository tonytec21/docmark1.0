<?php
require_once('tcpdf/tcpdf.php');
require_once('fpdf/fpdf.php');
require_once 'src/autoload.php';

use setasign\Fpdi\Fpdi;

// Função para adicionar o carimbo digital em cada página do PDF
function addStampToPDF($pdfPath, $stampText) {
    // Instancia a classe TCPDF
    $pdf = new FPDI();

    // Adiciona as páginas do PDF existente ao TCPDF
    $pageCount = $pdf->setSourceFile($pdfPath);
    for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
        $pdf->AddPage();
        $templateId = $pdf->importPage($pageNumber);
        $pdf->useTemplate($templateId);

        // Define a fonte, o tamanho e a posição do carimbo
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(150, 4);

        // Adiciona o carimbo em cada página do PDF
        $pdf->Cell(0, 0, 'CNM: ' . $stampText, 0, false, 'L');
    }

    // Gera o novo arquivo PDF com o carimbo
    $newPdfPath = 'destino/' . basename($pdfPath);
    $pdf->Output($newPdfPath, 'F');

    // Retorna o caminho para o novo arquivo PDF
    return $newPdfPath;
}

// Verifica se um arquivo PDF foi enviado
if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
    // Diretório de destino onde o arquivo será salvo
    $uploadDir = 'upload/';

    // Obtém o nome original do arquivo PDF
    $pdfName = $_FILES['pdf_file']['name'];

    // Gera o caminho completo do arquivo PDF
    $pdfPath = $uploadDir . $pdfName;

    // Move o arquivo PDF para o diretório de destino
    move_uploaded_file($_FILES['pdf_file']['tmp_name'], $pdfPath);

    // Texto para o carimbo digital
    $stampText = $_POST['stamp_text'];

    // Adiciona o carimbo digital ao PDF
    $newPdfPath = addStampToPDF($pdfPath, $stampText);

    // Define o cabeçalho HTTP para forçar o download do arquivo
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($newPdfPath) . '"');
    readfile($newPdfPath);
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>DocMark</title>
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
        
        <li><a href="sobre.php">Sobre</a></li>
        <li><a href="cnm/configuracao.php">Configuração</a></li>
      </ul>
    </nav>
  </div>
  <div class="container">
  <h3>ADICIONAR CARIMBO DIGITAL EM PDF</h3>
    <form method="POST" enctype="multipart/form-data">
      <label for="pdf_file">Selecione o arquivo PDF:</label>
      <input type="file" name="pdf_file" id="pdf_file" required accept="application/pdf">
      <label for="stamp_text">Digite o texto para o carimbo:</label>
      <input type="text" name="stamp_text" id="stamp_text" required>
      <input type="submit" value="Adicionar Carimbo">
    </form>
    <p class="observation">Observação: O carimbo digital será inserido no canto superior direito de todas as páginas do PDF.</p>
 <br>
 <br>
 <hr>
 <br>
 <br>
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
  
</body>
</html>


