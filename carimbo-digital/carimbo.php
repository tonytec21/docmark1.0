<?php
require_once('tcpdf/tcpdf.php');
require_once('fpdf/fpdf.php');
require_once('src/autoload.php');

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use setasign\Fpdi\FpdiTpl;

// Função para adicionar o carimbo digital em cada página do PDF
function addStampToPDF($pdfPath, $stampText) {
    // Instancia a classe FPDI
    $pdf = new Fpdi();

    // Adiciona as páginas do PDF existente ao FPDI
    $pageCount = $pdf->setSourceFile($pdfPath);
    for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
        $pdf->AddPage();
        $templateId = $pdf->importPage($pageNumber);
        $pdf->useTemplate($templateId);

        // Define a fonte, o tamanho e a posição do carimbo
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(145, 4);

        // Adiciona o carimbo em cada página do PDF
        $pdf->Cell(0, 0, 'CNM: ' . $stampText, 0, false, 'L');
    }

    // Retorna o objeto PDF modificado
    return $pdf;
}

// Função para copiar arquivo TIFF para diretório histórico
function copyToHistory($tiffFileName) {
    $source = 'upload/' . $tiffFileName;
    $destination = '../pdf-para-tiff/historico/' . $tiffFileName;
    if (!copy($source, $destination)) {
        echo "Erro ao copiar $tiffFileName para o diretório histórico.";
    }
}

// Verifica se o arquivo "cnm.json" existe
if (file_exists('cnm.json')) {
    // Lê o conteúdo do arquivo "cnm.json"
    $cnmData = json_decode(file_get_contents('cnm.json'), true);

    // Array para guardar os nomes dos arquivos PDF
    $pdfFiles = array();

    // Processa cada item em cnmData
    foreach ($cnmData as $item) {
        // Verifica se o campo 'cnm' e 'nomeArquivo' foram encontrados
        if (isset($item['cnm']) && isset($item['nomeArquivo'])) {
            $cnm = $item['cnm'];
            $nomeArquivo = $item['nomeArquivo'];

            // Verifica se o arquivo PDF existe na pasta de upload
            $pdfPath = 'upload/' . $nomeArquivo;
            if (file_exists($pdfPath)) {
                // Adiciona o carimbo digital ao PDF
                $pdf = addStampToPDF($pdfPath, $cnm);

                // Extrai o número de matrícula do nome do arquivo
                $numeroMatricula = preg_replace('/\D/', '', $nomeArquivo); // Remove non-numeric characters from the file name
                $numeroMatricula = str_pad($numeroMatricula, 8, '0', STR_PAD_LEFT); // Pad the number with zeros

                // Define o nome do arquivo com o número de matrícula
                $newFileName = $numeroMatricula . '.pdf';

                // Adiciona o nome do arquivo ao array de arquivos PDF
                array_push($pdfFiles, $newFileName);

                // Salva o PDF com carimbo na pasta de upload
                $pdf->Output('F', 'upload/' . $newFileName);

                // Convert PDF to TIFF using magick command
                $tiffFileName = $numeroMatricula . '.tiff';
                $command = 'magick convert -density 200 -monochrome -compress Group4 "upload/' . $newFileName . '" "upload/' . $tiffFileName . '"';
                exec($command);

                // Copy TIFF file to history directory
                copyToHistory($tiffFileName);

                // Add the TIFF file to the array of PDF files
                array_push($pdfFiles, $tiffFileName);

                // Delete the temporary PDF file
                unlink('upload/' . $newFileName);
            } else {
                echo "Arquivo PDF não encontrado.";
            }
        } else {
            echo "Campo 'cnm' e/ou 'nomeArquivo' não encontrados no item do arquivo 'cnm.json'.";
        }
    }

    // Define a data e hora de geração
    $dateTime = new DateTime();
    $timestamp = $dateTime->format('Ymd_His');

    // Define o nome do arquivo zip com a data e hora de geração
    $zipFileName = 'arquivos_' . $timestamp . '.zip';

    // Define o caminho do arquivo zip com a pasta e o nome do arquivo
    $zipFilePath = 'arquivos/' . $zipFileName;

    // Instancia a classe ZipArchive
    $zip = new ZipArchive;
    if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
        // Adiciona cada arquivo PDF ao arquivo zip
        foreach ($pdfFiles as $pdfFile) {
            $zip->addFile('upload/' . $pdfFile, $pdfFile);
        }

        // Fecha o arquivo zip
        $zip->close();

                // Enviar um sinal ao JavaScript informando que o processamento foi concluído
                echo '<script>onProcessingComplete();</script>';
        

        // Força o download do arquivo zip
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$zipFileName}");
        readfile($zipFilePath);

        // Exclui todos os arquivos PDF e TIFF da pasta de upload
        foreach ($pdfFiles as $pdfFile) {
            unlink('upload/' . $pdfFile);
        }

        // Delete all PDF files in the "upload" directory
        $pdfFilesToDelete = glob('upload/*.pdf');
        foreach ($pdfFilesToDelete as $file) {
            unlink($file);
        }
    } else {
        echo "Erro ao criar o arquivo zip.";
    }
} else {
    echo "Arquivo 'cnm.json' não encontrado.";
}
?>
