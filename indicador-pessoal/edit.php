

<?php
// Inclua a função verificar_sessao_ativa()
require_once 'funcoes.php';

// Verifique se a sessão está ativa
verificar_sessao_ativa();

$files = glob('matriculas/*.json');

function formatarDocumento($documento) {
    if (strlen($documento) === 11) {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $documento);
    } elseif (strlen($documento) === 14) {
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $documento);
    }
    return $documento;
}
?>

<?php
if (isset($_GET['matricula'])) {
    $filename = 'matriculas/' . $_GET['matricula'] . '.json';

    if (file_exists($filename)) {
        $data = json_decode(file_get_contents($filename), true);
        $entries = $data['entries'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Matrículas Cadastradas</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/chart.js"></script>
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
        .verificador {
            display: flex;
            align-content: center;
            flex-direction: column;
            flex-wrap: wrap;
        }
        form {
            display: flex;
            flex-direction: row;
            align-items: center;
        }
        .container {
            max-width: 90%;
        }

        form {
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
    }
 

input[type="text"]{
    color: #000;
    padding-left: 10px;
    padding-right: 10px;
    border-radius: 5px;
    padding-top: 2px;
    padding-bottom: 2px;
}

        .button3{
            color: #333 !important;
            border-radius: 5px;
            box-sizing: border-box;
            min-width: 1.5em;
            padding: 0.5em 0.9em;
            margin-bottom: 30px;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            border: none;
            margin-right: 15px;
            background: rgb(255 255 255);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(13.4px);
            -webkit-backdrop-filter: blur(13.4px);
            -webkit-transition: 0.7s;
        }
        .button3:hover{
            background: #02a146e8;
            color: #fff!important;
            -webkit-transition: 0.7s;
            scale: 1;
            border-color: none;
            box-shadow: 0 0px 30px;
        }
        input[type="submit"]{
            background: rgb(255 255 255);
        }

        </style>
        <script>
        function addField() {
        }

        function removeAto(element) {
            const fields = element.parentElement.querySelectorAll('input');
            fields.forEach(field => {
                field.value = '';
            });

            element.parentElement.style.display = 'none';
        }

        function addField() {
    const container = document.getElementById('additionalFields');
    const div = document.createElement('div');

    const fields = `
    <hr>
        <label>NOME:</label>
        <input type="text" name="nome[]" required><br>
        <label>CPF/CNPJ:</label>
        <input type="text" name="cpf[]" required><br>
        <label>TIPO DE ATO:</label>
        <input type="text" name="tipo_ato[]"><br>
        <label>DATA AV/R:</label>
        <input style="margin-bottom: 10px; border-radius: 5px; padding-left: 10px; padding-right: 10px; padding-bottom: 2px; padding-top: 2px;" type="date" name="data_avr[]" required><br>
        <label>DATA VENDA:</label>
        <input style="margin-bottom: 10px; border-radius: 5px; padding-left: 10px; padding-right: 10px; padding-bottom: 2px; padding-top: 2px;" type="date" name="data_venda[]"><br><br>
        <button type="button" class="button3" onclick="removeAto(this)">- Remove Proprietário</button><br><br>
    `;

    div.innerHTML = fields;
    container.appendChild(div);
}

    </script>
</head>
<body>

    <div class="orb-container">
            <div class="orb"></div>
    </div>
            <h1>DocMark - Indicador Pessoal</h1>
            <?php include_once("../menu.php");?>
            <!-- SINAL PÚBLICO E INDICADOR PESSOAL -->
            <div class="container">

    <h3>Editar Indicador Pessoal</h3>
    <form action="update.php" method="POST">
        <label>Nº MATRICULA:</label>
        <input type="text" name="matricula" value="<?php echo $_GET['matricula']; ?>" readonly><br>

        <div style="text-align: left;" id="additionalFields">
        <?php foreach ($entries as $entry): ?>
            <div>
                <label>NOME:</label>
                <input type="text" name="nome[]" value="<?php echo $entry['nome']; ?>" required><br>
                <label>CPF/CNPJ:</label>
                <input type="text" name="cpf[]" value="<?php echo $entry['cpf']; ?>" required><br>
                <label>TIPO DE ATO:</label>
                <input type="text" name="tipo_ato[]" value="<?php echo $entry['tipo_ato']; ?>"><br>
                <label>DATA AV/R:</label>
                <input style="margin-bottom: 10px; border-radius: 5px; padding-left: 10px; padding-right: 10px; padding-bottom: 2px; padding-top: 2px;" type="date" name="data_avr[]" value="<?php echo $entry['data_avr']; ?>" required><br>
                <label>DATA VENDA:</label>
                <input style="margin-bottom: 10px; border-radius: 5px; padding-left: 10px; padding-right: 10px; padding-bottom: 2px; padding-top: 2px;" type="date" name="data_venda[]" value="<?php echo $entry['data_venda']; ?>"><br>
                <br>
                <!-- <button type="button" class="button3" onclick="removeAto(this)">- Remove Proprietário</button><br><br>  -->

            </div>

        <?php endforeach; ?>
        </div>
        <button type="button" class="button3" onclick="addField()">+ Proprietários</button><br>
        <input type="submit" value="Atualizar">
    </form>

    </div>

    <?php include_once("../rodape.php");?>
</body>
</html>




