<?php
// Pasta onde estão armazenados os arquivos ZIP
$pastaArquivos = __DIR__ . '/arquivos';

// Obtém a lista de arquivos ZIP na pasta
$arquivosZIP = glob($pastaArquivos . '/*.zip');

// Função para obter a data e hora de um arquivo ZIP
function getDataHoraArquivoZIP($arquivoZIP)
{
    $nomeArquivo = basename($arquivoZIP, '.zip');
    $dataHora = substr($nomeArquivo, strlen('arquivos_'));
    return DateTime::createFromFormat('YmdHis', $dataHora);
}

// Ordena os arquivos ZIP por data e hora (mais recentes primeiro)
usort($arquivosZIP, function ($a, $b) {
    $dataHoraA = getDataHoraArquivoZIP($a);
    $dataHoraB = getDataHoraArquivoZIP($b);
    return $dataHoraA > $dataHoraB ? -1 : 1;
});

?>

<!DOCTYPE html>
<html>

<head>
    <title>Histórico de Conversões</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .container ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .container li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .container li span {
            font-weight: bold;
            margin-right: 10px;
        }

        .container .button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .container .button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="logo">
            <br>
            <img src="../img/logo.png" alt="Logomarca do Software">
            <h1>DocMark</h1>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="../cnm/index.php">Carimbo Digital</a></li>
                <li><a href="index.php">Converter PDF para TIFF</a></li>
                <li><a href="../single-tif-para-pdf/index.php">Converter TIFF para PDF</a></li>
                <!-- <li><a href="../contato.php">Contato</a></li>
                <li><a href="../sobre.php">Sobre</a></li> -->
                <li><a href="../cnm/configuracao.php">Configuração</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Histórico de Conversões</h1>
        <ul>
            <?php foreach ($arquivosZIP as $arquivoZIP) : ?>
                <?php $dataHora = getDataHoraArquivoZIP($arquivoZIP); ?>
                <li>
                    <?php 

                    $meses = [
                        1 => 'janeiro',
                        2 => 'fevereiro',
                        3 => 'março',
                        4 => 'abril',
                        5 => 'maio',
                        6 => 'junho',
                        7 => 'julho',
                        8 => 'agosto',
                        9 => 'setembro',
                        10 => 'outubro',
                        11 => 'novembro',
                        12 => 'dezembro'
                    ];

                    $dia = $dataHora->format('d');
                    $mes = $meses[(int)$dataHora->format('m')];
                    $ano = $dataHora->format('Y');
                    $hora = $dataHora->format('H:i:s');

                    $dataHoraFormatada = $dia . ' de ' . $mes . ' de ' . $ano . ', às ' . $hora;

                    ?>
                    <span>Conversão realizada em <?= $dataHoraFormatada ?></span>
                    <a href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/pdf-para-tiff/arquivos/' . basename($arquivoZIP) ?>" class="button">Download</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <footer>
        <p>&copy; 2023 Nome da Empresa. Todos os direitos reservados.</p>
    </footer>
</body>

</html>
