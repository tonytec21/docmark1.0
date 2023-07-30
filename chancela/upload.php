<?php
require_once('tcpdf/tcpdf.php');
require_once('fpdf/fpdf.php');
require_once 'src/autoload.php';

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

function deleteFiles($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); 
        foreach( $files as $file ){ 
            if(is_dir($file)) 
                deleteFiles( $file ); 
            else
                unlink( $file );  
        }
    }
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

    // clean the upload and temp directories
    deleteFiles($upload_dir);
    deleteFiles($temp_dir);

    if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE) {
        foreach ($file_names as $key => $file_name) {
            $file_tmp = $file_tmps[$key];
            $file_parts = explode('.', $file_name);
            $file_ext = strtolower(end($file_parts));

            if (in_array($file_ext, $extensions) === false) {
                $errors[] = "Extension not allowed, please choose a PDF file.";
            }

            if (empty($errors) == true) {
                $dest_file_path = $upload_dir . $file_name;
                // move the file to the upload directory
                move_uploaded_file($file_tmp, $dest_file_path);

                // Verify if the file is properly moved
                if (!file_exists($dest_file_path)) {
                    die("Error: Failed to move the file to the upload directory.");
                }

                // create new PDF
                $pdf = new Fpdi();

                // Verify if the file can be read by FPDI
                try {
                    // get the page count
                    $pageCount = $pdf->setSourceFile($dest_file_path);
                } catch (Exception $e) {
                    die("Error: Failed to read the file using FPDI. " . $e->getMessage());
                }

                // iterate through all pages
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $pdf->AddPage();
                    // import a page
                    $templateId = $pdf->importPage($pageNo);
                    $pdf->useTemplate($templateId);

                    // stamp the image
                    $pdf->Image($stamp_image, 190, 2, 18, 0, 'PNG');
                    // stamp the image 2
                    $pdf->Image($stamp_image_2, 5, 200, 10, 0, 'PNG');
                }
                
                // Output the new PDF
                $output_file = $temp_dir . $file_name;
                $pdf->Output('F', $output_file);

                // add the output file into the zip
                $zip->addFile($output_file, basename($output_file));
            } else {
                print_r($errors);
            }
        }

        $zip->close();

        // Enviar um sinal ao JavaScript informando que o processamento foi concluído
        echo '<script>onProcessingComplete();</script>';
        
        // Após o processamento, redirecionar para o arquivo ZIP para download
        header('Location: ' . $zip_path);
        exit();

        
        // // Set headers and output the zip file for download
        // header('Content-Type: application/zip');
        // header('Content-Disposition: attachment; filename="' . basename($zip_path) . '"');
        // header('Content-Length: ' . filesize($zip_path));


        // ob_clean();
        // flush();
        // readfile($zip_path);
        // exit();

    } else {
        echo 'Falha ao compactar o arquivo.';
    }
    
} else {
    echo "Nenhum arquivo enviado.";
}

?>
