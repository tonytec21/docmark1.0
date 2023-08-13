<?php
// Inclua a função verificar_sessao_ativa()
require_once 'funcoes.php';

// Verifique se a sessão está ativa
verificar_sessao_ativa();

function formatCNPJCPF($value) {
    if (strlen($value) == 11) {
        // Formato CPF: 000.000.000-00
        return substr($value, 0, 3) . '.' . substr($value, 3, 3) . '.' . substr($value, 6, 3) . '-' . substr($value, 9, 2);
    } elseif (strlen($value) == 14) {
        // Formato CNPJ: 00.000.000/0000-00
        return substr($value, 0, 2) . '.' . substr($value, 2, 3) . '.' . substr($value, 5, 3) . '/' . substr($value, 8, 4) . '-' . substr($value, 12, 2);
    }
    return $value;
}

function formatDateToBrazilian($dateString) {
    if (strlen($dateString) == 8) {
        return substr($dateString, 0, 2) . '/' . substr($dateString, 2, 2) . '/' . substr($dateString, 4, 4);
    }
    return $dateString;
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Logs do Indicador Pessoal</title>
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
    <script type="text/javascript" charset="utf8" src="js/jquery.dataTables2.js"></script>
   
   <style>
    select {
        color: #0d181b;
        margin-left: 5px;
        margin-right: 5px;
        border-radius: 50px;
        padding: 5px;
        background: rgb(255 255 255 / 52%);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(6.9px);
        -webkit-backdrop-filter: blur(6.9px);
        color: #333;
    }
    .verificador{
    display: flex;
    align-content: center;
    flex-direction: column;
    flex-wrap: wrap;
    }
    form{
        display: flex;
    flex-direction: row;
    align-items: center;
    }
    .container{
        max-width: 90%;
    }
    </style>
</head>
<body>

    <div class="orb-container">
            <div class="orb"></div>
    </div>
            <h1>DocMark - Indicador Pessoal</h1><br><br><br>
            <div class="container">
            <?php
    $files = glob("../pdf-para-tiff/historico-indicador/*.xml");

    function fixEncodingWithIconv($input) {
        // Tentamos converter de "UTF-8" para "ISO-8859-1" e depois corretamente para "UTF-8"
        return iconv("UTF-8", "ISO-8859-1//TRANSLIT", $input);
    }
?>

<form method="post">
    <select name="selected_file">
        <option value="">Selecione</option>
        <?php foreach($files as $file): ?>
            <option value="<?php echo htmlspecialchars($file); ?>" 
            <?php if (isset($_POST['selected_file']) && $_POST['selected_file'] == $file) echo 'selected="selected"'; ?>>
                INDICADOR DO DIA <?php echo date("d/m/Y", filemtime($file)); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Carregar arquivo" />
</form>

<?php
    if(isset($_POST['selected_file']) && in_array($_POST['selected_file'], $files)) {
        $fileContent = file_get_contents($_POST['selected_file']);
        
        $xml = simplexml_load_string($fileContent);
        
        // Início da tabela
        echo '<table id="example" class="display" style="width:100%">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>NOME</th>';
        echo '<th>CNPJ/CPF</th>';
        echo '<th>MATRICULA</th>';
        echo '<th>ATO</th>';
        echo '<th>DATA R/AV</th>';
        echo '<th>DATA DE VENDA</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Loop pelos indivíduos e exibe os dados em linhas da tabela
        foreach ($xml->INDIVIDUO as $individuo) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars(fixEncodingWithIconv($individuo->NOME)) . '</td>';
            echo '<td>' . htmlspecialchars(formatCNPJCPF(fixEncodingWithIconv($individuo->CNPJCPF))) . '</td>';
            echo '<td>' . htmlspecialchars(fixEncodingWithIconv($individuo->NMATRICULA)) . '</td>';
            echo '<td>' . htmlspecialchars(fixEncodingWithIconv($individuo->TIPODEATO)) . '</td>';
            echo '<td>' . htmlspecialchars(formatDateToBrazilian($individuo->DTREGAVERB)) . '</td>';
            echo '<td>' . htmlspecialchars(formatDateToBrazilian($individuo->DTVENDA)) . '</td>';            
            echo '</tr>';
        }

        // Fim da tabela
        echo '</tbody>';
        echo '</table>';
    }
?>
    </div>

<script>
    $(document).ready( function () {
        $('#example').DataTable();
    } );
</script>
    <?php include_once("../rodape.php");?>
</body>
</html>
