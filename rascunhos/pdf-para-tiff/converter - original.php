<?php
function converterPDFparaTIFF($pastaPDF, $pastaTIFF)
{
    // Obtém a lista de arquivos PDF na pasta
    $arquivosPDF = glob($pastaPDF . '/*.pdf');

    // Mensagens de sucesso e erro
    $mensagensSucesso = [];
    $mensagensErro = [];

    // Loop através dos arquivos PDF
    foreach ($arquivosPDF as $arquivoPDF) {
        // Gera o nome do arquivo TIFF de destino
        $nomeArquivoTIFF = basename($arquivoPDF, '.pdf') . '.tiff';
        $arquivoTIFF = $pastaTIFF . '/' . $nomeArquivoTIFF;

        try {
            // Executa o comando do ImageMagick para converter o PDF em TIFF em preto e branco com 200 DPI e compressão
            $comandoImageMagick = "magick convert -density 200 -monochrome -compress Group4 {$arquivoPDF} {$arquivoTIFF}";
            exec($comandoImageMagick, $output, $returnCode);

            // Verifica se a conversão foi bem-sucedida
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

    return [
        'sucesso' => $mensagensSucesso,
        'erro' => $mensagensErro
    ];
}
