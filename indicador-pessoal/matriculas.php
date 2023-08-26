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

<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Matrículas Cadastradas</title>
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
    </style>

<script>
    $(document).ready(function() {
        $('#matriculasTable').DataTable({
            "columnDefs": [
                { "width": "7%", "targets": 0 },  // MATRÍCULA
                { "width": "33%", "targets": 1 },  // PROPRIETÁRIOS
                { "width": "15%", "targets": 2 },  // CPF/CNPJ
                { "width": "15%", "targets": 3 },  // DATA DE ATUALIZAÇÃO
                { "width": "10%", "targets": 4 },  // VISUALIZAR
                { "width": "2%", "targets": 5 }   // EXCLUIR
            ],
            "autoWidth": false
        });
    });
</script>
<style>
    form {
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
    }
    #sincronizar2{
        margin-left: 60%;
        margin-top: -7%;
    }
    
    /* Estilos para o modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0 0 0 / 59%);

}

.modal-content {
    margin: 10px auto 50px;
    padding: 20px;
    border-radius: 50px;
    background: 0 4px 30px rgba(0, 0, 0, 0.1);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(6.2px);
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

input[type="text"]{
    color: #000;
    padding-left: 10px;
    padding-right: 10px;
    border-radius: 5px;
    padding-top: 2px;
    padding-bottom: 2px;
}
.close {
    color: #fff;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
#sincronizar3{
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
}
    </style>
</head>
<body>
    <div class="orb-container">
        <div class="orb"></div>
    </div>
    <h1>DocMark - Matrículas Cadastradas</h1><br><br><br>
    <div class="container">
    <div id="sincronizar3">
                <button class="btn2 first" id="modalcadastro">Cadastrar Matrícula</button>
                <button class="btn2 first" id="visualizar-indicador">Visualizar XML do Indicador Pessoal</button>
                <button class="btn2 first" id="modalgerarXML">Gerar Arquivo XML</button>
    </div>
        <h2>Indicador Pessoal</h2>
        <table id="matriculasTable" class="display">
            <thead>
                <tr>
                    <th>MATRÍCULA</th>
                    <th>PROPRIETÁRIO(S)</th>
                    <th>CPF/CNPJ</th>
                    <th>DATA DE ATUALIZAÇÃO</th>
                    <th>VISUALIZAR</th>
                    <th>EXCLUIR</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($files as $file) {
                    $content = json_decode(file_get_contents($file), true);
                    $nomes = [];
                    $cpfs = [];
                    foreach ($content['entries'] as $entry) {
                        $nomes[] = $entry['nome'];
                        $cpfs[] = $entry['cpf'];
                    }
                    $nomeString = implode(", ", $nomes);
                    $cpfFormatted = array_map('formatarDocumento', $cpfs);
                    $cpfString = implode(", ", $cpfFormatted);
                    $filename = basename($file, '.json');
                    $lastModified = date("d/m/Y", filemtime($file));
                ?>
                    <tr>
                        <td><?php echo $filename; ?></td>
                        <td><?php echo $nomeString; ?></td>
                        <td><?php echo $cpfString; ?></td>
                        <td><?php echo $lastModified; ?></td>
                        <td><a class="btn first" href='edit.php?matricula=<?php echo $filename; ?>'>Visualizar</a></td>
                        <td><a class="btn2-gradient delete-link" style="background: rgb(255 99 132 / 53%)" href='delete.php?matricula=<?php echo $filename; ?>'><i class="fa fa-trash-o fa-1x" style="color: #fff" aria-hidden="true"></i></a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function() {
                $('#matriculasTable').DataTable();
            });
        </script>


<div id="xmlModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Gerar Arquivo XML do Indicador Pessoal</h3>
        <form action="processXML.php" method="post">
            <label>Data Inicial:</label>
            <input style="margin-bottom: 10px; border-radius: 5px; padding-left: 10px; padding-right: 10px; padding-bottom: 2px; padding-top: 2px;" type="date" name="start_date" required>
            <br>

            <label>Data Final:</label>
            <input style="margin-bottom: 10px; border-radius: 5px; padding-left: 10px; padding-right: 10px; padding-bottom: 2px; padding-top: 2px;" type="date" name="end_date" required>
            <br>

            <input type="submit" value="Gerar XML">
        </form>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Pega o modal
            var modal = document.getElementById("xmlModal");

            // Pega o botão que abre o modal
            var btn = document.getElementById("modalgerarXML");

            // Pega o elemento <span> que fecha o modal
            var span = document.querySelector(".close");

            // Quando o usuário clica no botão, abre o modal 
            btn.addEventListener('click', function() {
                modal.style.display = "block";
            });

            // Quando o usuário clica em <span> (x), fecha o modal
            span.addEventListener('click', function() {
                modal.style.display = "none";
            });

            // Quando o usuário clica fora do modal, fecha-o
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        });

        document.getElementById('visualizar-indicador').addEventListener('click', function () {
                    showProcessingPopup(); // Mostrar pop-up de processamento
                    
                    fetch('indicador-pessoal.php')
                        .then(response => response.text())
                        .then(output => {
                            hideProcessingPopup(); // Esconder pop-up de processamento
                            
                            // Abre a página em uma nova guia
                            window.open('indicador-pessoal.php', '_blank');
                        })
                        .catch(error => {
                            hideProcessingPopup(); // Esconder pop-up de processamento em caso de erro
                            // Adicionar lógica de manipulação de erro aqui, se necessário
                        });
                });

    </script>



<div id="cadastroModal" class="modal">
    <div class="modal-content">

    <script>
        function addField() {
            const container = document.getElementById('additionalFields');

            const div = document.createElement('div');
            div.innerHTML = `
                <label>NOME:</label>
                <input type="text" name="nome[]"><br>
                <label>CPF/CNPJ:</label>
                <input type="text" name="cpf[]" oninput="validateNumberOnly(this)"><br>
                <label>TIPO DE ATO:</label>
                <input type="text" name="tipo_ato[]"><br>
                <label>DATA AV/R:</label>
                <input type="date" name="data_avr[]"><br>
                <label>DATA VENDA:</label>
                <input type="date" name="data_venda[]"><br><br>
            `;

            container.appendChild(div);
        }

        function validateNumberOnly(input) {
            input.value = input.value.replace(/[^\d]/g, '');
        }
    </script>
    <style>
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

<span class="close">&times;</span>
<h3>Cadastrar Matricula</h3>
<form action="save.php" method="POST">
    <label>Nº MATRICULA:</label>
    <input type="text" name="matricula" oninput="validateNumberOnly(this)" required><br>

    <div style="text-align: left;" id="additionalFields">
        <!-- Inicial Proprietário Fields -->
        <div class="owner-fields">
            <label>NOME:</label>
            <input type="text" name="nome[]" required><br>
            <label>CPF/CNPJ:</label>
            <input type="text" name="cpf[]" oninput="validateNumberOnly(this)" required><br>
            <label>TIPO DE ATO:</label>
            <input type="text" name="tipo_ato[]"><br>
            <label>DATA AV/R:</label>
            <input style="margin-bottom: 10px; border-radius: 5px; padding-left: 10px; padding-right: 10px; padding-bottom: 2px; padding-top: 2px;" type="date" name="data_avr[]" required><br>
            <label>DATA VENDA:</label>
            <input style="margin-bottom: 10px; border-radius: 5px; padding-left: 10px; padding-right: 10px; padding-bottom: 2px; padding-top: 2px;" type="date" name="data_venda[]"><br><br>
        </div>
        <!-- End of Inicial Proprietário Fields -->
    </div>

    <button type="button" class="button3" onclick="addField()">+ Proprietários</button><br><br>
    <input type="submit" class="button3" value="Cadastrar">
</form>

<script>
    function addField() {
        var ownerFields = document.querySelector(".owner-fields").cloneNode(true);
        var removeBtn = document.createElement("button");
        
        removeBtn.innerText = "- Remove Proprietário";
        removeBtn.type = "button";
        removeBtn.classList.add("button3");
        removeBtn.addEventListener("click", function() {
            this.parentElement.remove();
        });

        ownerFields.appendChild(removeBtn);
        document.getElementById("additionalFields").appendChild(ownerFields);
    }
</script>

</div>
</div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var modal = document.getElementById("cadastroModal");
            var btn = document.getElementById("modalcadastro");
            var closeElements = document.querySelectorAll(".close");  
            
            // Exibir o modal ao clicar no botão
            btn.onclick = function() {
                modal.style.display = "block";
            }

            // Adiciona evento de fechar para todos os elementos '.close'
            closeElements.forEach(function(span) {
                span.onclick = function() {
                    modal.style.display = "none";
                }
            });

            // Fechar o modal ao clicar fora dele
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script>


    </div>
    <?php include_once("../rodape.php");?>
</body>
</html>
