<?php

function limparPastaUpload($pastaUpload) {
    // Obtém a lista de arquivos tif na pasta
    $arquivostif = glob($pastaUpload . '/*.tif');

    // Remove os arquivos tif da pasta
    foreach ($arquivostif as $arquivotif) {
        unlink($arquivotif);
    }
}

function converterPDFparatif($pastaPDF, $pastaUpload)
{
    // Mensagens de sucesso e erro
    $mensagensSucesso = [];
    $mensagensErro = [];

    // Loop através dos arquivos PDF
    foreach ($pastaPDF as $arquivoPDF) {
        // Gera o nome do arquivo tif de destino
        $nomeArquivotif = basename($arquivoPDF, '.pdf') . '.tif';
        $arquivotif = $pastaUpload . '/' . $nomeArquivotif;

        try {
            // Executa o comando do ImageMagick para converter o PDF em tif em preto e branco com 200 DPI e compressão
            $comandoImageMagick = "magick convert -density 200 -monochrome -compress Group4 {$arquivoPDF} {$arquivotif}";
            exec($comandoImageMagick, $output, $returnCode);

            // Verifica se a conversão foi bem-sucedida
            if ($returnCode === 0) {
                $mensagemSucesso = "Arquivo convertido com sucesso: {$arquivoPDF} -> {$arquivotif}";
                $mensagensSucesso[] = $mensagemSucesso;
            } else {
                $mensagemErro = "Erro ao converter o arquivo PDF em tif: {$arquivoPDF}";
                $mensagensErro[] = $mensagemErro;
            }
        } catch (Exception $e) {
            $mensagemErro = "Erro ao converter arquivo: {$arquivoPDF}";
            $mensagensErro[] = $mensagemErro;
        }
    }

    // Verifica se houve algum erro durante a conversão
    if (!empty($mensagensErro)) {
        return [
            'sucesso' => $mensagensSucesso,
            'erro' => $mensagensErro,
            'arquivo_zip' => null
        ];
    }

    // Cria o arquivo ZIP
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

    // Adiciona os arquivos tif ao arquivo ZIP
    $arquivostif = glob($pastaUpload . '/*.tif');
    foreach ($arquivostif as $arquivotif) {
        $nomeArquivo = basename($arquivotif);
        $zip->addFile($arquivotif, $nomeArquivo);
    }

    // Fecha o arquivo ZIP
    $zip->close();

    return [
        'sucesso' => $mensagensSucesso,
        'erro' => $mensagensErro,
        'arquivo_zip' => $arquivoZip
    ];
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pasta para salvar os arquivos PDF temporariamente
    $pastaPDF = __DIR__ . '/pdfs';

    // Verifica se a pasta de upload existe, caso contrário, cria-a
    if (!is_dir($pastaPDF)) {
        mkdir($pastaPDF, 0777, true);
    }

    // Move os arquivos PDF para a pasta temporária
    foreach ($_FILES['pdfs']['tmp_name'] as $key => $tmpName) {
        $nomeArquivoPDF = $_FILES['pdfs']['name'][$key];
        $arquivoPDF = $pastaPDF . '/' . $nomeArquivoPDF;
        move_uploaded_file($tmpName, $arquivoPDF);
    }

    // Pasta para salvar as imagens tif temporariamente
    $pastaUpload = __DIR__ . '/upload';

    // Limpa a pasta "upload" antes de iniciar as tarefas
    limparPastaUpload($pastaUpload);

    // Chama a função para converter os arquivos PDF para tif
    $resultado = converterPDFparatif(glob($pastaPDF . '/*.pdf'), $pastaUpload);

    // Faz o download do arquivo ZIP, se disponível
    if (!empty($resultado['arquivo_zip'])) {
        $nomeArquivo = basename($resultado['arquivo_zip']);

        // Copia o arquivo ZIP para a pasta "arquivos"
        $pastaArquivos = __DIR__ . '/arquivos';
        if (!is_dir($pastaArquivos)) {
            mkdir($pastaArquivos, 0777, true);
        }
        $arquivoZipCopia = $pastaArquivos . '/' . $nomeArquivo;
        copy($resultado['arquivo_zip'], $arquivoZipCopia);

        // Faz o download do arquivo ZIP
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$nomeArquivo}");
        readfile($resultado['arquivo_zip']);

        // Remove o arquivo ZIP da pasta "upload"
        unlink($resultado['arquivo_zip']);

        // Limpa a pasta "upload" após o download do arquivo ZIP
        limparPastaUpload($pastaUpload);

        // Remove os arquivos PDF da pasta "pdfs"
        foreach (glob($pastaPDF . '/*.pdf') as $arquivoPDF) {
            unlink($arquivoPDF);
        }
    }

    // Armazena as mensagens de sucesso e erro em variáveis
    $mensagensSucessoHTML = '';
    $mensagensErroHTML = '';

    // Loop através das mensagens de sucesso e as concatena em uma variável
    foreach ($resultado['sucesso'] as $mensagem) {
        $mensagensSucessoHTML .= "<div class='alert alert-success' role='alert'>{$mensagem}</div>";
    }

    // Loop através das mensagens de erro e as concatena em uma variável
    foreach ($resultado['erro'] as $mensagem) {
        $mensagensErroHTML .= "<div class='alert alert-danger' role='alert'>{$mensagem}</div>";
    }

    // Exibe as mensagens de sucesso após a chamada para header()
    echo $mensagensSucessoHTML;

    // Exibe as mensagens de erro após a chamada para header()
    echo $mensagensErroHTML;
}
