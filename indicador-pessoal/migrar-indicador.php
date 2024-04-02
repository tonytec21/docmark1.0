<?php
$xmlString = <<<XML



XML;

$xml = simplexml_load_string($xmlString);

foreach ($xml->INDIVIDUO as $individuo) {
    $matricula = (string)$individuo->NMATRICULA;
    $nome = (string)$individuo->NOME;
    $cpf = (string)$individuo->CNPJCPF;
    $tipo_ato = (string)$individuo->TIPODEATO;
    
    // Corrigindo o formato da data
    $data_avr = DateTime::createFromFormat('dmY', (string)$individuo->DTREGAVERB)->format('Y-m-d');
    
    $data_venda = (string)$individuo->DTVENDA;

    $jsonData = [
        'matricula' => $matricula,
        'entries' => [
            [
                'nome' => $nome,
                'cpf' => $cpf,
                'tipo_ato' => $tipo_ato,
                'data_avr' => $data_avr,
                'data_venda' => $data_venda,
            ],
        ],
    ];

    $jsonFileName = "indicador-migrado/{$matricula}.json";
    file_put_contents($jsonFileName, json_encode($jsonData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
?>