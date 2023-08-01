<?php
if (isset($_POST['filePath'])) {
    $filePath = $_POST['filePath'];

    // Caminho absoluto para o arquivo .bat
    $batchFilePath = 'copy.bat';

    // Executa o arquivo .bat com o caminho do arquivo como argumento
    $output = shell_exec("cmd /c \"{$batchFilePath} {$filePath}\"");

    echo "Arquivo copiado para a rede com sucesso";
} else {
    echo "Nenhum arquivo especificado";
}
?>
