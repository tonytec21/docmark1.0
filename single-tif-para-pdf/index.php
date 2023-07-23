<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['files']['name'])) {
    if (count($_FILES['files']['name']) > 2000) {
        echo "Erro: Não é permitido selecionar mais de 2000 arquivos por vez.";
        exit;
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>DocMark</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header class="header">
    <div class="orb-container">
            <div class="orb"></div>
        </div>
            <h1>DocMark</h1>
        <nav class="menu">
            <ul>
                <li><a href="../index.php">Carimbo Digital</a></li>
                <li><a href="../pdf-para-tiff/index.php">Converter PDF para TIFF</a></li>
                <li><a href="index.php">Converter TIFF para PDF</a></li>
                <!-- <li><a href="../chancela/index.php">Chancela Mecânica</a></li> -->
                <li><a href="../cnm/configuracao.php">Configuração</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Converter arquivos TIFF para PDF</h1>

        <form action="index.php" method="POST" enctype="multipart/form-data">
            <label for="files">Selecione os arquivos TIFF:</label>
            <input type="file" name="files[]" id="files" multiple required><br>
            <input type="submit" value="Converter">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['files']['name'])) {
            $tiff_dir = __DIR__.'/tiff/';
            $pdf_dir = __DIR__.'/pdf/';
            $zip_dir = __DIR__.'/arquivos/';

            if (!file_exists($tiff_dir)) mkdir($tiff_dir, 0777, true);
            if (!file_exists($pdf_dir)) mkdir($pdf_dir, 0777, true);
            if (!file_exists($zip_dir)) mkdir($zip_dir, 0777, true);

            // Limpar a pasta 'tiff' antes do upload de novos arquivos
            array_map('unlink', glob($tiff_dir.'*.tiff'));
            array_map('unlink', glob($tiff_dir.'*.tif'));

            $file_names = $_FILES['files']['name'];
            $temp_names = $_FILES['files']['tmp_name'];
            for ($i = 0; $i < count($temp_names); $i++) {
                if (!move_uploaded_file($temp_names[$i], $tiff_dir.$file_names[$i])) {
                    die("Erro ao mover o arquivo para o diretório 'tiff'.");
                }
            }

            $command = 'Conversor-Single-TIFF-para-PDF.bat';
            $command .= ' "' . $tiff_dir . '"';
            $command .= ' "' . $pdf_dir . '"';
            $output = shell_exec($command);
            echo '<pre>' . $output . '</pre>';

            $zip = new ZipArchive();
            $date = date('Y-m-d_H-i-s');
            $zip_file = $zip_dir.'pdfs_'.$date.'.zip';
            if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
                $pdf_files = glob($pdf_dir.'*.pdf');
                foreach ($pdf_files as $pdf_file) {
                    if (!$zip->addFile($pdf_file, basename($pdf_file))) {
                        die("Erro ao adicionar o arquivo $pdf_file ao ZIP.");
                    }
                }
                $zip->close();
            } else {
                die("Erro ao criar o arquivo ZIP.");
            }

            array_map('unlink', glob($pdf_dir.'*.pdf'));

            if (file_exists($zip_file)) {
                $zip_download_url = 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/single-tif-para-pdf/arquivos/' . basename($zip_file);
                header('Location: ' . $zip_download_url);
                exit;
            } else {
                die("Erro ao fazer o download do arquivo ZIP.");
            }
        }
        ?>
    </div>

    <div class="container" style="margin-top: -38px;margin-bottom: 2%;">
    <h1>Histórico de Conversões</h1>

    <?php  
    $arquivos_dir = __DIR__.'/arquivos/';
    $arquivos = glob($arquivos_dir . '*.zip');
    if (empty($arquivos)) {
        echo '';
    } else {
        // Ordena os arquivos por data de modificação
        usort($arquivos, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        echo '<ul>';
        foreach ($arquivos as $arquivo) {
            $arquivo_nome = basename($arquivo);
            $dataHora = DateTime::createFromFormat('Y-m-d_H-i-s', substr($arquivo_nome, strlen('pdfs_'), -4));

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

        }
        echo '</ul>';
    }
    ?>

<canvas id="conversionChart"></canvas>
<?php

$dates = [];
foreach ($arquivos as $arquivo) {
    $arquivo_nome = basename($arquivo);
    $dataHora = DateTime::createFromFormat('Y-m-d_H-i-s', substr($arquivo_nome, strlen('pdfs_'), -4));
    $dates[] = $dataHora->format('Y-m-d');
}

echo '<script>';
echo 'var conversionDates = ' . json_encode(array_count_values($dates)) . ';';
echo '</script>';

?>

<script>
    var ctx = document.getElementById('conversionChart').getContext('2d');
    var dates = Object.keys(conversionDates);
    var counts = Object.values(conversionDates);
    
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                label: 'Conversões por dia',
                data: counts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
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


</div>

    <div class="container" style="margin-top: -38px;margin-bottom: 0%;">
    
    <?php  
    $arquivos_dir = __DIR__.'/arquivos/';
    $arquivos = glob($arquivos_dir . '*.zip');
    if (empty($arquivos)) {
        echo '<p>Nenhuma conversão encontrada no histórico.</p>';
    } else {
        // Ordena os arquivos por data de modificação
        usort($arquivos, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        echo '<ul>';
        foreach ($arquivos as $arquivo) {
            $arquivo_nome = basename($arquivo);
            $dataHora = DateTime::createFromFormat('Y-m-d_H-i-s', substr($arquivo_nome, strlen('pdfs_'), -4));

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
            echo '<li><b>Conversão realizada em ' . $dataHoraFormatada . ' </b><a class="btn-gradient" style="padding:5px 25px!important;" href="arquivos/' . $arquivo_nome . '"> Download</a></li>';
        }
        echo '</ul>';
    }
    ?>

</div>



    <footer>
        <p style="color: #fff;text-decoration: none"> <p><a style="color: #fff;text-decoration: none"  href="https://backupcloud.site/" target="_blank">&copy; <span id="year"></span> DocMark | By Backup Cloud. Todos os direitos reservados.</a></p></p>
    </footer>

    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
</body>
</html>
