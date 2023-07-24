<?php

function limparPastaUpload($pastaUpload) {
    $arquivosTIFF = glob($pastaUpload . '/*.tiff');
    foreach ($arquivosTIFF as $arquivoTIFF) {
        unlink($arquivoTIFF);
    }
}

function normalizarNomeArquivo($nomeArquivo) {
    $normalizado = iconv('UTF-8', 'ASCII//TRANSLIT', $nomeArquivo);
    $normalizado = preg_replace('/[^a-zA-Z0-9.-]/', '_', $normalizado);
    return $normalizado;
}

function converterPDFparaTIFF($pastaPDF, $pastaUpload)
{
    $mensagensSucesso = [];
    $mensagensErro = [];

    foreach ($pastaPDF as $arquivoPDF) {
        preg_match_all('!\d+!', basename($arquivoPDF, '.pdf'), $matches);
        $numeroArquivo = implode("", $matches[0]);
        $nomeArquivoTIFF = str_pad($numeroArquivo, 8, '0', STR_PAD_LEFT) . '.tiff';
        $nomeArquivoTIFF = normalizarNomeArquivo($nomeArquivoTIFF);
        $arquivoTIFF = $pastaUpload . '/' . $nomeArquivoTIFF;

        try {
            $comandoImageMagick = "magick convert -density 200 -monochrome -compress Group4 {$arquivoPDF} {$arquivoTIFF}";
            exec($comandoImageMagick, $output, $returnCode);

            if ($returnCode === 0) {
                $mensagemSucesso = "Arquivo convertido com sucesso: {$arquivoPDF} -> {$arquivoTIFF}";
                $mensagensSucesso[] = $mensagemSucesso;
            } else {
                $mensagemErro = "Erro ao converter o arquivo PDF em TIFF: {$arquivoPDF}";
                $mensagensErro[] = $mensagemErro;
            }
        } catch (Exception $e) {
            $mensagemErro = "Erro ao converter arquivo: {$arquivoPDF}";
            $mensagensErro[] = $mensagemErro;
        }
    }

    if (!empty($mensagensErro)) {
        return [
            'sucesso' => $mensagensSucesso,
            'erro' => $mensagensErro,
            'arquivo_zip' => null
        ];
    }

    $arquivoZip = $pastaUpload . '/arquivos_' . date('YmdHis') . '.zip';
    $zip = new ZipArchive();
    if ($zip->open($arquivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        $mensagemErro = "Erro ao criar o arquivo ZIP";
        $mensagensErro[] = $mensagemErro;
        return [
            'sucesso' => $mensagensSucesso,
            'erro' => $mensagensErro,
            'arquivo_zip' => null
        ];
    }

    $arquivosTIFF = glob($pastaUpload . '/*.tiff');
    foreach ($arquivosTIFF as $arquivoTIFF) {
        $nomeArquivo = basename($arquivoTIFF);
        $zip->addFile($arquivoTIFF, $nomeArquivo);
    }

    $zip->close();

    return [
        'sucesso' => $mensagensSucesso,
        'erro' => $mensagensErro,
        'arquivo_zip' => $arquivoZip
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pastaPDF = __DIR__ . '/pdfs';

    if (!is_dir($pastaPDF)) {
        mkdir($pastaPDF, 0777, true);
    }

    foreach ($_FILES['pdfs']['tmp_name'] as $key => $tmpName) {
        $nomeArquivoPDF = normalizarNomeArquivo($_FILES['pdfs']['name'][$key]);
        $arquivoPDF = $pastaPDF . '/' . $nomeArquivoPDF;
        move_uploaded_file($tmpName, $arquivoPDF);
    }

    $pastaUpload = __DIR__ . '/upload';
    limparPastaUpload($pastaUpload);
    $resultado = converterPDFparaTIFF(glob($pastaPDF . '/*.pdf'), $pastaUpload);

    if (!empty($resultado['arquivo_zip'])) {
        $nomeArquivo = basename($resultado['arquivo_zip']);
        $pastaArquivos = __DIR__ . '/arquivos';
        if (!is_dir($pastaArquivos)) {
            mkdir($pastaArquivos, 0777, true);
        }
        $arquivoZipCopia = $pastaArquivos . '/' . $nomeArquivo;
        copy($resultado['arquivo_zip'], $arquivoZipCopia);
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$nomeArquivo}");
        readfile($resultado['arquivo_zip']);
        unlink($resultado['arquivo_zip']);
        limparPastaUpload($pastaUpload);
        foreach (glob($pastaPDF . '/*.pdf') as $arquivoPDF) {
            unlink($arquivoPDF);
        }
    }

    $mensagensSucessoHTML = '';
    $mensagensErroHTML = '';
    foreach ($resultado['sucesso'] as $mensagem) {
        $mensagensSucessoHTML .= "<div class='alert alert-success' role='alert'>{$mensagem}</div>";
    }
    foreach ($resultado['erro'] as $mensagem) {
        $mensagensErroHTML .= "<div class='alert alert-danger' role='alert'>{$mensagem}</div>";
    }
    echo $mensagensSucessoHTML;
    echo $mensagensErroHTML;
}

?>
