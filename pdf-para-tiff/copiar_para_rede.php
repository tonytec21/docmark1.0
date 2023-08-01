<?php
header('Content-Type: application/json');

$arquivo = $_POST['arquivo'] ?? null;

if ($arquivo === null) {
    echo json_encode(['message' => 'Nenhum arquivo especificado.']);
    exit;
}

$pastaHistorico = __DIR__ . '/historico';
$origem = $pastaHistorico . '/' . $arquivo;

$destino = '//files/MATRICULAS/100000/' . $arquivo; // substitua pelo caminho real da pasta de rede

if (!file_exists($origem)) {
    echo json_encode(['message' => 'O arquivo de origem não existe: ' . $origem]);
    exit;
}

if (!is_writable(dirname($destino))) {
    echo json_encode(['message' => 'A pasta de destino não é gravável: ' . dirname($destino)]);
    exit;
}

if (!copy($origem, $destino)) {
    echo json_encode(['message' => 'Falha ao copiar arquivo para a rede.']);
} else {
    echo json_encode(['message' => 'Arquivo copiado com sucesso para a rede.']);
}
