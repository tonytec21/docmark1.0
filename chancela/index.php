<?php
// Inclua a função verificar_sessao_ativa()
require_once 'funcoes.php';

// Verifique se a sessão está ativa
verificar_sessao_ativa();
?>
<?php
ob_start();
function getDataHoraArquivoZIP($arquivoZIP) {
    $nomeArquivo = basename($arquivoZIP);
    $padrao = '/arquivos_(\d{8}_\d{6})\.zip/';
    preg_match($padrao, $nomeArquivo, $matches);
    $dataHoraString = str_replace('_', '', $matches[1]);
    $dataHora = DateTime::createFromFormat('YmdHis', $dataHoraString);
    return $dataHora;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Sinal Público</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/chart.js"></script>
    <script src="path/to/chart-config.js"></script>
    <?php include_once("../menu.php");?>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/pop-up.js"></script>
</head>
<body>

<div class="orb-container">
    <div class="orb"></div>
</div>
<h1>DocMark - Sinal Público</h1><br><br><br>

    <div class="container">
        <h3>Processar arquivos para adicionar o sinal público</h3>
        <form action="upload.php" method="post" enctype="multipart/form-data" onsubmit="return onSubmitForm();">
            <label for="pdf">Selecione os arquivos PDF:</label>
            <input type="file" name="pdf[]" id="pdf" multiple><br>
            <input type="submit" value="Processar" name="submit" class="button">
        </form>
    </div>

    <div class="container" style="margin-top: -38px;margin-bottom: 2%;">
    <h3>Histórico de Processamento</h3>
    <ul>
        <?php
        $arquivosZIP = glob('arquivos/arquivos_*.zip');
        $conversoesPorDia = [];

        if (empty($arquivosZIP)) :
            ?>
           
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
        <canvas id="conversionChart" style="height: 435px;"></canvas>
    </div>
            <script>

                // Obtém os dados das conversões por dia
                const conversionData = <?= json_encode($conversoesPorDia) ?>;

                // Obtém os dias e quantidades de conversões
                const labels = Object.keys(conversionData);
                const data = Object.values(conversionData);

                // Configuração do gráfico
                const ctx = document.getElementById('conversionChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Conversões por Dia',
                            data: data,
                            backgroundColor: 'rgb(255 255 255 / 50%)', // Cor das barras
                            borderColor: 'rgb(255 255 255 / 50%)', // Cor da borda das barras
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Quantidade de Conversões'
                                }
                            },
                            
                        }
                    }
                });
            </script>
        
    
    </ul>

    <ul>
        <?php
        $arquivosZIP = array_reverse(glob('arquivos/arquivos_*.zip'));
        $conversoesPorDia = [];

        if (empty($arquivosZIP)) :
            ?>
            <p style="color: #fff">Nenhum processamento encontrada no histórico.</p>
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
                    <a href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/chancela/arquivos/' . basename($arquivoZIP) ?>" class="btn-gradient" style="padding:5px 25px!important;">Download</a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<?php include_once("../rodape.php");?>

</body>
</html>
