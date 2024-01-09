<?php

function limparPastaUpload($pastaUpload)
{
    $arquivosTIFF = glob($pastaUpload . '/*.tiff');
    foreach ($arquivosTIFF as $arquivoTIFF) {
        unlink($arquivoTIFF);
    }
}

function normalizarNomeArquivo($nomeArquivo)
{
    $normalizado = iconv('UTF-8', 'ASCII//TRANSLIT', $nomeArquivo);
    $normalizado = preg_replace('/[^a-zA-Z0-9.-]/', '_', $normalizado);
    return $normalizado;
}

function copiarParaHistorico($arquivo, $pastaHistorico, $tipoArquivo)
{
    $nomeArquivo = basename($arquivo);
    $destino = $pastaHistorico . '/' . $tipoArquivo . '/' . $nomeArquivo;

    if (!copy($arquivo, $destino)) {
        throw new Exception("Falha ao copiar {$arquivo} para {$pastaHistorico}");
    }
}

function converterPDFparaTIFF($pastaPDF, $pastaUpload)
{
    $mensagensSucesso = [];
    $mensagensErro = [];
    $pastaHistorico = __DIR__ . '/historico';
    $pastaHistoricoPDF = __DIR__ . '/pdf-viw';

    if (!is_dir($pastaHistorico)) {
        mkdir($pastaHistorico, 0777, true);
    }

    if (!is_dir($pastaHistoricoPDF)) {
        mkdir($pastaHistoricoPDF, 0777, true);
    }

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
                copiarParaHistorico($arquivoTIFF, $pastaHistorico, '');
                $mensagemSucesso = "Arquivo convertido com sucesso: {$arquivoPDF} -> {$arquivoTIFF}";
                $mensagensSucesso[] = $mensagemSucesso;
            } else {
                $mensagemErro = "Erro ao converter o arquivo PDF em TIFF: {$arquivoPDF}";
                $mensagensErro[] = $mensagemErro;
            }
        } catch (Exception $e) {
            $mensagemErro = "Erro ao converter arquivo: {$arquivoPDF}. Detalhes: " . $e->getMessage();
            $mensagensErro[] = $mensagemErro;
        }

        $nomeArquivoPDFNovo = str_pad($numeroArquivo, 8, '0', STR_PAD_LEFT) . '.pdf';
        $nomeArquivoPDFNovo = normalizarNomeArquivo($nomeArquivoPDFNovo);
        $arquivoPDFNovo = $pastaHistoricoPDF . '/' . $nomeArquivoPDFNovo;
        copy($arquivoPDF, $arquivoPDFNovo);
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

    // Mensagem de confirmação e redirecionamento
    echo '<script>alert("Processamento concluído! Clique em OK para ir para o histórico."); window.location.href = "historico.php";</script>';
    // echo '<div class="alert alert-success" role="alert">Processamento concluído com sucesso!</div>';
    // echo '<button style="cursor: pointer;" onclick="window.location.href=\'historico.php\'">Ir para o histórico</button>';
    exit();
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
