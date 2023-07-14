<?php
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
    <title>DocMark</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="path/to/chart-config.js"></script>
</head>
<body>
    <header class="header">
        <div class="logo">
            <br>
            <img src="../img/logo.png" alt="Logo">
            <h1>DocMark</h1>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="index.php">Carimbo Digital</a></li>
                <li><a href="../pdf-para-tiff/index.php">Converter PDF para TIFF</a></li>
                <li><a href="../single-tif-para-pdf/index.php">Converter TIFF para PDF</a></li>
                <li><a href="../chancela/index.php">Chancela Mecânica</a></li> 
                <li><a href="configuracao.php">Configuração</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h1>Processar arquivos para carimbo digital</h1>
        <form action="processar.php" method="POST" enctype="multipart/form-data">
            <label for="arquivoPDF">Selecione os arquivos PDF:</label>
            <input type="file" name="arquivoPDF[]" id="arquivoPDF" multiple><br>
            <input type="submit" name="submit" value="Processar">
        </form>
    </div>


    <div class="container" style="margin-top: -38px;margin-bottom: 2%;">
    <h1>Histórico de Processamento</h1>
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
                            backgroundColor: 'rgba(54, 162, 235, 0.5)', // Cor das barras
                            borderColor: 'rgba(54, 162, 235, 1)', // Cor da borda das barras
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

</div>


    <div class="container" style="margin-top: -38px;margin-bottom: 2%;">
    <ul>
        <?php
        $arquivosZIP = array_reverse(glob('arquivos/arquivos_*.zip'));
        $conversoesPorDia = [];

        if (empty($arquivosZIP)) :
            ?>
            <p>Nenhuma processamento encontrada no histórico.</p>
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
                    <a href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/cnm/arquivos/' . basename($arquivoZIP) ?>" class="btn-gradient" style="padding:5px 25px!important;">Download</a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

    <footer>
        <p style="color: #fff;text-decoration: none"> <p><a style="color: #fff;text-decoration: none"  href="https://backupcloud.site/" target="_blank">&copy; <span id="year"></span> DocMark | By Backup Cloud. Todos os direitos reservados.</a></p></p>
    </footer>
    <script>
        // Obtém o ano atual e insere no elemento de ID "year"
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
</body>
</html>
