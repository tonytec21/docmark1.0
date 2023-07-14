<?php
require_once('tcpdf/tcpdf.php');
require_once('fpdf/fpdf.php');
require_once 'src/autoload.php';

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
        $pdf->SetXY(145, 6);

        // Adiciona o carimbo em cada página do PDF
        $pdf->Cell(0, 0, 'CNM: ' . $stampText, 0, false, 'L');
    }

    // Retorna o objeto PDF modificado
    return $pdf;
}

// Verifica se o arquivo "numero_matricula.json" existe
if (file_exists('numero_matricula.json')) {
    // Lê o conteúdo do arquivo "numero_matricula.json"
    $numeroMatriculaData = json_decode(file_get_contents('numero_matricula.json'), true);

    // Verifica se o campo 'nomeArquivo' e 'numeroMatricula' foram encontrados
    if (isset($numeroMatriculaData['nomeArquivo']) && isset($numeroMatriculaData['numeroMatricula'])) {
        $nomeArquivo = $numeroMatriculaData['nomeArquivo'];
        $numeroMatricula = $numeroMatriculaData['numeroMatricula'];

        // Verifica se o arquivo PDF existe na pasta de upload
        $pdfPath = 'upload/' . $nomeArquivo;
        if (file_exists($pdfPath)) {
            // Verifica se o arquivo "cnm.json" existe
            if (file_exists('cnm.json')) {
                // Lê o conteúdo do arquivo "cnm.json"
                $cnmData = json_decode(file_get_contents('cnm.json'), true);

                // Verifica se o campo 'cnm' foi encontrado
                if (isset($cnmData['cnm'])) {
                    $cnm = $cnmData['cnm'];

                    // Adiciona o carimbo digital ao PDF
                    $pdf = addStampToPDF($pdfPath, $cnm);

                    // Define o nome do arquivo com os zeros à esquerda
                    $zeroPaddedNumeroMatricula = str_pad($numeroMatricula, 8, '0', STR_PAD_LEFT);
                    $newFileName = $zeroPaddedNumeroMatricula . '.pdf';

                    // Define o cabeçalho HTTP para forçar o download do arquivo com o nome original
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="' . $newFileName . '"');
                    $pdf->Output('D', $newFileName);

                } else {
                    echo "Campo 'cnm' não encontrado no arquivo 'cnm.json'.";
                }
            } else {
                echo "Arquivo 'cnm.json' não encontrado.";
            }
        } else {
            echo "Arquivo PDF não encontrado.";
        }
    } else {
        echo "Campo 'nomeArquivo' e/ou 'numeroMatricula' não encontrados no arquivo 'numero_matricula.json'.";
    }
} else {
    echo "Arquivo 'numero_matricula.json' não encontrado.";
}
