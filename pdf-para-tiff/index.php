<?php
// Inclua a função verificar_sessao_ativa()
require_once 'funcoes.php';

// Verifique se a sessão está ativa
verificar_sessao_ativa();
?>
<?php
ob_start();
// Pasta onde estão armazenados os arquivos ZIP
$pastaArquivos = __DIR__ . '/arquivos';
$pastaPDFs = __DIR__ . '/pdfs';

// Obtém a lista de arquivos ZIP na pasta
$arquivosZIP = glob($pastaArquivos . '/*.zip');

// Função para obter a data e hora de um arquivo ZIP
function getDataHoraArquivoZIP($arquivoZIP)
{
    $nomeArquivo = basename($arquivoZIP, '.zip');
    $dataHora = substr($nomeArquivo, strlen('arquivos_'));
    return DateTime::createFromFormat('YmdHis', $dataHora);
}

// Ordena os arquivos ZIP por data e hora
usort($arquivosZIP, function ($a, $b) {
    $dataHoraA = getDataHoraArquivoZIP($a);
    $dataHoraB = getDataHoraArquivoZIP($b);
    return $dataHoraA > $dataHoraB ? -1 : 1;
});

?>

<!DOCTYPE html>
<html>
<head>
    <title>DocMark - PDF para TIFF</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/chart.js"></script>
    <?php include_once("../menu.php");?>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/pop-up.js"></script>
</head>
<body>

    <div class="orb-container">
            <div class="orb"></div>
        </div>
            <h1>DocMark - PDF para TIFF</h1>

    <div class="container">
        <h3>Converter arquivos PDF para TIFF</h3>

        <?php
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verifica se arquivos PDF foram enviados no formulário
            if (empty($_FILES['pdfs']['tmp_name'])) {
                echo "<div class='alert alert-danger' role='alert'>Nenhum arquivo PDF selecionado.</div>";
                exit;
            }

            // Obtém os arquivos PDF selecionados pelo usuário
            $arquivosPDF = $_FILES['pdfs']['tmp_name'];

            // Inclui o arquivo com a função de conversão
            include 'converter.php';

            // Chama a função para converter os arquivos PDF para TIFF
            $resultado = converterPDFparaTIFF($arquivosPDF, $pastaPDFs);

            // Verifica se ocorreram erros durante a conversão
            if (!empty($resultado['erro'])) {
                // Exibe as mensagens de erro em um alerta
                foreach ($resultado['erro'] as $mensagem) {
                    echo "<div class='alert alert-danger' role='alert'>{$mensagem}</div>";
                }
            } else {
                // Faz o download do arquivo zip
                if (file_exists($resultado['arquivo_zip'])) {
                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename="' . basename($resultado['arquivo_zip']) . '"');
                    header('Content-Length: ' . filesize($resultado['arquivo_zip']));
                    readfile($resultado['arquivo_zip']);

                    // Exclui o arquivo zip
                    unlink($resultado['arquivo_zip']);

                    // Limpa a pasta pdfs
                    $arquivosTIFF = glob($pastaPDFs . '/*.tiff');
                    foreach ($arquivosTIFF as $arquivoTIFF) {
                        unlink($arquivoTIFF);
                    }

                    exit;
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Erro ao fazer o download do arquivo zip.</div>";
                }
            }
        }
        ?>

        <div class="form">
            <form method="POST" action="" enctype="multipart/form-data" onsubmit="return onSubmitForm();">
                <label for="pdfs">Selecione os arquivos PDF:</label>
                <input type="file" id="pdfs" name="pdfs[]" accept=".pdf" required multiple><br>
                <input type="submit" value="Converter">
            </form>
        </div>
    </div>

    <div class="container" style="margin-top: -38px;margin-bottom: 2%;">
    <h3>Histórico de Conversões</h3>
<ul>


<?php
    $conversoesPorDia = [];

    if (empty($arquivosZIP)) : ?>
        
<?php else : ?>
    <?php foreach ($arquivosZIP as $arquivoZIP) :
        $dataHora = getDataHoraArquivoZIP($arquivoZIP);
        $data = $dataHora->format('Y-m-d');

        if (!isset($conversoesPorDia[$data])) {
            $conversoesPorDia[$data] = 0;
        }

        $conversoesPorDia[$data]++;
    ?>
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
    <?php endforeach; ?>
<?php endif; ?>

<div>
    <canvas id="conversoesChart"></canvas>
</div>

<script>
    var ctx = document.getElementById('conversoesChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($conversoesPorDia)); ?>,
            datasets: [{
                label: 'Conversões por dia',
                data: <?php echo json_encode(array_values($conversoesPorDia)); ?>,
                backgroundColor: 'rgb(255 255 255 / 50%)',
                borderColor: 'rgb(255 255 255 / 50%)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Quantidade de Conversões'
                                }
                            }
            }
        }
    });
</script>
</ul>


    <ul>


    
        <?php
            $conversoesPorDia = [];

            if (empty($arquivosZIP)) : ?>
                <p style="color: #fff">Nenhuma conversão encontrada no histórico.</p>
        <?php else : ?>
            <?php foreach ($arquivosZIP as $arquivoZIP) :
                $dataHora = getDataHoraArquivoZIP($arquivoZIP);
                $data = $dataHora->format('Y-m-d');

                if (!isset($conversoesPorDia[$data])) {
                    $conversoesPorDia[$data] = 0;
                }

                $conversoesPorDia[$data]++;
            ?>
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
                <li>
                    <span>Conversão realizada em <?= $dataHoraFormatada ?></span>
                    <a href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/pdf-para-tiff/arquivos/' . basename($arquivoZIP) ?>" class="btn-gradient" style="padding:5px 25px!important;">Download</a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<?php include_once("../rodape.php");?>

</body>
</html>