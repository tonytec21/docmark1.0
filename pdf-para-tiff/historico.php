<?php

$pastaHistorico = __DIR__ . '/historico';
$arquivos = glob($pastaHistorico . '/*');
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

?>

<?php

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
                    <table id="tabela-historico" class="display">
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
                        <?php foreach ($arquivos as $arquivo): ?>
                            <tr>
                                <td><?php echo str_replace('.tiff', '', basename($arquivo)); ?></td>
                                <td><?php echo strftime('%d de %B de %Y', filemtime($arquivo)); ?></td>
                                <td><?php echo strftime('%H:%M:%S', filemtime($arquivo)); ?></td>
                                <td><?php echo pathinfo($arquivo, PATHINFO_EXTENSION); ?></td>
                                <td><a class="btn-gradient" href="historico/<?php echo basename($arquivo); ?>" download>Download</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <script>
                $(document).ready(function() {
                    $('#tabela-historico').DataTable({
                        "order": [[ 0, "asc" ]]
                    });
                });
                </script>
    </div>

    <div class="container">
        <h3 style="margin: 0px 0;">Matrículas Faltantes</h3>
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

<?php include_once("../rodape.php");?>

</body>
</html>