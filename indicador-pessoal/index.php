<?php
// Inclua a função verificar_sessao_ativa()
require_once 'funcoes.php';

// Verifique se a sessão está ativa
verificar_sessao_ativa();
?>

<?php
// Array para armazenar os logs
$logs = [];

// O arquivo de log selecionado pelo usuário
$nome = '';

// Função para formatar o número do documento
function formatarDocumento($numero) {
    $numero = preg_replace("/\D/", '', $numero);

    if (strlen($numero) === 11) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $numero);
    } else if (strlen($numero) === 14) {
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $numero);
    } else {
        return $numero;
    }
}

// Verifica se um arquivo de log foi selecionado
if (isset($_POST['log'])) {
    // O arquivo de log selecionado pelo usuário
    $nome = $_POST['log'];

    // O caminho completo do arquivo
    $arquivo = 'logs/' . $nome;

    // Verifica se o arquivo existe
    if (!file_exists($arquivo)) {
        die("Arquivo não encontrado: $arquivo");
    }

    // Lê o arquivo e retorna um array de linhas
    $linhas = file($arquivo);

    // Itera por cada linha
    foreach ($linhas as $linha) {
        // Verifica se a linha contém o texto desejado
        if (strpos($linha, 'Número de documento inválido:') !== false) {
            // Extrai as partes relevantes da linha
            $partes = explode('Número de documento inválido:', $linha);
            $data = explode(' ', $partes[0])[0];
            $documento = trim($partes[1]);

            // Ignora se o número do documento estiver vazio
            if (empty($documento)) {
                continue;
            }

            // Formata o documento
            $documentoFormatado = formatarDocumento($documento);

            // Determina o tipo do documento pelo tamanho
            $tipo = strlen($documento) == 11 ? 'CPF' : 'CNPJ';

            // Adiciona o log ao array
            $logs[] = ['erro' => 'Número de documento inválido', 'documento' => $documentoFormatado, 'tipo' => $tipo, 'data' => $data];
        }
    }
}

// Função para imprimir o formulário de seleção de arquivo de log
function imprimirFormulario() {
    // Obtém o nome do arquivo selecionado, se houver um
    $arquivoSelecionado = isset($_POST['log']) ? $_POST['log'] : '';

    // Lista todos os arquivos .txt no diretório "logs"
    $arquivos = glob('logs/*.txt');

    // Cria arrays para armazenar as datas dos arquivos e os nomes dos arquivos correspondentes
    $datas = [];
    $nomes = [];

    // Itera por cada arquivo
    foreach ($arquivos as $arquivo) {
        // Remove o caminho do diretório e a extensão .txt do nome do arquivo
        $nome = basename($arquivo, '.txt');

        // Verifica se o nome do arquivo corresponde a um número de 8 dígitos
        if (!preg_match('/^\d{8}$/', $nome)) {
            continue;  // Ignora este arquivo e continua para o próximo
        }

        // Cria um objeto DateTime a partir da data extraída
        $dataObj = DateTime::createFromFormat('Ymd', $nome);

        // Adiciona a data e o nome do arquivo aos arrays correspondentes
        $datas[] = $dataObj;
        $nomes[] = $nome;
    }

    // Ordena os arrays de datas e nomes, com as datas mais recentes primeiro
    array_multisort($datas, SORT_DESC, $nomes);

    echo '<form action="index.php" method="post">';
    // echo '<label for="log">Selecione um arquivo de log:</label><br>';
    echo '<select id="log" name="log">';

    // Itera por cada nome de arquivo, que agora estão ordenados pelas datas correspondentes
    foreach ($nomes as $nome) {
        // Formata a data no formato brasileiro
        $dataFormatada = DateTime::createFromFormat('Ymd', $nome)->format('d/m/Y');

        // Se este arquivo for o selecionado, definir como selecionado
        $selecionado = $nome . '.txt' === $arquivoSelecionado ? 'selected' : '';
        echo "<option value=\"$nome.txt\" $selecionado>Log do dia $dataFormatada</option>";
    }

    echo '</select><br>';
    echo '<input type="submit" value="Verificar">';
    echo '</form>';
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
    <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>
   
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
    </style>
</head>
<body>

    <div class="orb-container">
            <div class="orb"></div>
    </div>
            <h1>DocMark - Logs do Indicador Pessoal</h1>
            <div class="container">
            <div id="sincronizar">
                <button class="btn2 first" id="sincronizar-button">Sincronizar arquivos de Log</button><?php imprimirFormulario(); ?>
                </div>    <br>
                

        <div id="popup" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background-color:rgba(0,0,0,0.5); text-align:center;">
            <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px; border-radius:5px;">
                <p>Executando a tarefa, por favor aguarde...</p>
            </div>
        </div>

        <script>
            function showSuccessPopup(message) {
                    const successHtml = `
                        <div id="success-modal" style="
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background-color: rgba(0, 0, 0, 0.5);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            z-index: 9999;
                        ">
                            <div style="
                                padding: 20px;
                                background-color: #f1f1f1;
                                border: 1px solid #ccc;
                                border-radius: 4px;
                            ">
                                <p>${message}</p>
                                <button id="close-success-btn" style="
                                    display: block;
                                    margin: 10px auto;
                                    padding: 5px 10px;
                                ">Fechar</button>
                            </div>
                        </div>
                    `;
                    $('body').append(successHtml);

                    $('#close-success-btn').on('click', function() {
                        hideSuccessPopup();
                    });
                }

                function hideSuccessPopup() {
                    $('#success-modal').remove();
                }

                document.getElementById('sincronizar-button').addEventListener('click', function () {
                    showProcessingPopup(); // Mostrar pop-up de processamento
                    fetch('execute_sync.php')
                        .then(response => response.text())
                        .then(output => {
                            hideProcessingPopup(); // Esconder pop-up de processamento
                            showSuccessPopup('Os arquivos de logs foram importados com sucesso.'); // Mostrar pop-up de sucesso
                        })
                        .catch(error => {
                            hideProcessingPopup(); // Esconder pop-up de processamento em caso de erro
                            // Adicionar lógica de manipulação de erro aqui, se necessário
                        });
                });
                </script>

            <h3 style="margin: 0px 0;">Lista de Erros</h3>
    <table id="logsTable">
        <thead>
            <tr>
                <th>Tipo de Erro</th>
                <th>Tipo de Documento</th>
                <th>Número do Documento</th>
                <th>Data do Log</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($logs as $log) {
                $data = date_create_from_format('Y-m-d', $log['data']);
                $dataFormatada = date_format($data, 'd/m/Y');

                echo "<tr>";
                echo "<td>" . htmlspecialchars($log['erro']) . "</td>";
                echo "<td>" . htmlspecialchars($log['tipo']) . "</td>";
                echo "<td>" . htmlspecialchars($log['documento']) . "</td>";
                echo "<td>" . htmlspecialchars($dataFormatada) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    </div>

    <script>
        $(document).ready(function () {
            $('#logsTable').DataTable();
        });
    </script>
    <?php include_once("../rodape.php");?>
</body>
</html>
