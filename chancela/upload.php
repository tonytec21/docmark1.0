<?php
require_once('tcpdf/tcpdf.php');
require_once('fpdf/fpdf.php');
require_once 'src/autoload.php';

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

function deleteFiles($target) {
    if (is_dir($target)) {
        $files = glob($target . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file))
                deleteFiles($file);
            else
                unlink($file);
        }
    }
}

function formatarNomeArquivo($nomeOriginal) {
    preg_match_all('/\d+/', $nomeOriginal, $matches);
    $numeros = implode('', $matches[0]);

    if (!$numeros) {
        throw new Exception("Nome do arquivo precisa conter ao menos um número.");
    }

    $numeros = str_pad($numeros, 8, '0', STR_PAD_LEFT);
    return $numeros . '.pdf';
}

if (isset($_FILES['pdf'])) {
    $errors = array();
    $file_names = $_FILES['pdf']['name'];
    $file_tmps = $_FILES['pdf']['tmp_name'];

    $extensions = array("pdf");
    $upload_dir = "pdfs/";
    $temp_dir = "temp/";
    $output_dir = "arquivos/";
    $stamp_image = 'config/chancela.png';
    $stamp_image_2 = 'config/chancela-2.png';
    $zip_file = 'arquivos_' . date('Ymd_His') . '.zip';
    $zip_path = $output_dir . $zip_file;
    $zip = new ZipArchive;

    deleteFiles($upload_dir);
    deleteFiles($temp_dir);

    if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE) {
        foreach ($file_names as $key => $file_name) {
            $formatted_name = formatarNomeArquivo($file_name);
            $file_tmp = $file_tmps[$key];

            if (in_array(pathinfo($file_name, PATHINFO_EXTENSION), $extensions) === false) {
                $errors[] = "Extension not allowed, please choose a PDF file.";
            }

            if (empty($errors) == true) {
                $dest_file_path = $upload_dir . $formatted_name;
                move_uploaded_file($file_tmp, $dest_file_path);

                if (!file_exists($dest_file_path)) {
                    die("Error: Failed to move the file to the upload directory.");
                }

                $pdf = new Fpdi();

                try {
                    $pageCount = $pdf->setSourceFile($dest_file_path);
                } catch (Exception $e) {
                    die("Error: Failed to read the file using FPDI. " . $e->getMessage());
                }

                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $pdf->AddPage();
                    $templateId = $pdf->importPage($pageNo);
                    $pdf->useTemplate($templateId);

                    $pdf->Image($stamp_image, 190, 2, 18, 0, 'PNG');
                    $pdf->Image($stamp_image_2, 5, 200, 10, 0, 'PNG');
                }

                $output_file = $temp_dir . $formatted_name;
                $pdf->Output('F', $output_file);

                $zip->addFile($output_file, basename($output_file));

                // Convertendo para TIFF e salvando em "historico"
                try {
                    $nomeArquivoTIFF = str_replace('.pdf', '.tiff', $formatted_name);
                    $arquivoTIFF = "../pdf-para-tiff/historico/" . $nomeArquivoTIFF;
                    $comandoImageMagick = "magick convert -density 200 -monochrome -compress Group4 {$output_file} {$arquivoTIFF}";
                    exec($comandoImageMagick);
                } catch (Exception $e) {
                    die("Erro: " . $e->getMessage());
                }
            } else {
                print_r($errors);
            }
        }

        $zip->close();
        echo '<script>onProcessingComplete();</script>';
        header('Location: ' . $zip_path);
        exit();

    } else {
        echo 'Falha ao compactar o arquivo.';
    }
} else {
    echo "Nenhum arquivo enviado.";
}
?>
