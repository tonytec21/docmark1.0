<?php
// Inclua a função verificar_sessao_ativa()
require_once 'funcoes.php';

// Verifique se a sessão está ativa
verificar_sessao_ativa();
?>
<?php
error_reporting(0);
ini_set('display_errors', 0);

$pastaHistorico = __DIR__ . '/historico';
$arquivos = glob($pastaHistorico . '/*');
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

// Obtém os números dos nomes dos arquivos
$numerosArquivos = array();
foreach ($arquivos as $arquivo) {
    $numeroArquivo = (int) str_replace('.tiff', '', basename($arquivo));
    $numerosArquivos[] = $numeroArquivo;
}

// Obtém o intervalo de números
$minimo = 1; // agora o mínimo é sempre 1
$maximo = max($numerosArquivos);

// Obtém os números faltantes
$numerosFaltantes = array();
for ($i = $minimo; $i <= $maximo; $i++) {
    if (!in_array($i, $numerosArquivos)) {
        $numerosFaltantes[] = str_pad($i, 8, '0', STR_PAD_LEFT);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Controle de Conversões</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/chart.js"></script>
    <?php include_once("../menu.php");?>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/pop-up.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/chart.js"></script>
    <script src="js/chartjs-plugin-datalabels.js"></script>

    <script type="text/javascript" charset="utf8" src="js/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>

</head>
<body>

    <div class="orb-container">
            <div class="orb"></div>
    </div>
            <h1>DocMark - Controle de Conversões</h1>

    <div class="container">
        <h3>Histórico de Matrículas Convertidas</h3>
        
        <div id="sincronizar">
                <button class="btn2 first" id="sincronizar-button">Sincronizar com NexCloud</button>
                <button class="btn2 first" id="visualizar-button">Atualizar Visualização</button>
        </div>

        <script>
        document.getElementById('sincronizar-button').addEventListener('click', function () {
            fetch('execute_sync.php')
                .then(response => response.text())
                .then(output => {
                    alert('Comando executado com sucesso! ' + output + ' matrículas foram copiadas.');
                });
        });
        </script>

        <script>
        document.getElementById('visualizar-button').addEventListener('click', function () {
            fetch('atualizar-visualizacao.php')
                .then(response => response.text())
                .then(output => {
                    alert('Visualização das Matrículas atualizada com sucesso! ' + output + ' matrículas foram atualizadas.');
                });
        });
        </script>




<br>

                    <table id="tabela-historico" class="display">
                    <thead>
                        <tr>
                            <th>Matrícula Nº</th>
                            <th>Data da última conversão</th>
                            <th>Horário</th>
                            <!-- <th>Tipo de Arquivo</th> -->
                            <th>Download</th>
                            <th>Visualizar</th>
                            <th>Excluir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($arquivos as $arquivo): ?>
                            <tr>
                                <td><?php echo str_replace('.tiff', '', basename($arquivo)); ?></td>
                                <td><?php echo strftime('%d de %B de %Y', filemtime($arquivo)); ?></td>
                                <td><?php echo strftime('%H:%M:%S', filemtime($arquivo)); ?></td>
                                <!-- <td><?php echo pathinfo($arquivo, PATHINFO_EXTENSION); ?></td> -->
                                <td><a class="btn first" style="text-align: center!important;" href="historico/<?php echo basename($arquivo); ?>" download>Download</a></td>
                                <td><a class="btn first" href="pdf-viw/<?php echo str_replace('.tiff', '.pdf', basename($arquivo)); ?>" target="_blank">Visualizar</a></td>
                                <td><a class="btn2-gradient delete-link" style="background: rgb(255 99 132 / 53%)" href="delete.php?file=<?php echo urlencode(basename($arquivo)); ?>"><i class="fa fa-trash-o fa-1x" style="color: #fff" aria-hidden="true"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <script>
                    $(document).ready(function() {
                        $('.delete-link').on('click', function(e) {
                            e.preventDefault();
                            let href = $(this).attr('href');
                            let confirmBox = confirm('Tem certeza de que deseja excluir esta matrícula?');
                            if (confirmBox) {
                                window.location.href = href;
                            }
                        });
                    });
                </script>

                <script>
                $(document).ready(function() {
                    $('#tabela-historico').DataTable({
                        "order": [[ 0, "asc" ]]
                    });
                });
                </script>

    </div>

    <div class="container">
        <h3 style="margin: 0px 0;">Matrículas Faltantes - <?php echo count($numerosFaltantes); ?></h3>
            <h3 style="margin: 0px 0;">Intervalo verificado: <?php echo $minimo . ' - ' . $maximo; ?></h3>

            <table id="tabela-historico2" class="display">
            <thead>
                <tr>
                    <th>Matrícula Nº</th>
                    <th>Data da última conversão</th>
                    <th>Horário</th>
                    <th>Tipo de Arquivo</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($numerosFaltantes as $numeroFaltante): ?>
                    <tr>
                        <td><?php echo $numeroFaltante; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <script>
        $(document).ready(function() {
            $('#tabela-historico2').DataTable({
                "order": [[ 0, "asc" ]]
            });
        });
        </script>
</div>

<div class="container">
        <h3 style="margin: 0px 0;">Relatório de Conversão</h3>

        <div id="chart-container">
            <canvas id="grafico"></canvas>
        </div>

        <script>
    $(document).ready(function(){
        $.ajax({
            url: 'data.php',
            method: 'GET',
            dataType: 'json',
            success: function(data){
                var ctx = document.getElementById('grafico').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Matriculas Convertidas', 'Matrículas Faltantes'],
                        datasets: [{
                            data: [data.convertidos, data.faltantes],
                            backgroundColor: ['rgb(75 192 192 / 69%)', 'rgb(255 99 132 / 53%)'],
                            borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            datalabels: {
                                color: '#fff',
                                formatter: function(value, context) {
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = ((value / total) * 100).toFixed(2) + '%';
                                    return value + ' (' + percentage + ')';
                                }
                            },
                            legend: {
                                position: 'top',
                            },
                        }
                    },
                });
            }
        });
    });
    </script>
</div>

<?php include_once("../rodape.php");?>

</body>
</html>