<?php
include 'historico.php'; // o arquivo que contém a função copiarParaRede()
if (isset($_POST['caminhoArquivo'])) {
    echo copiarParaRede($_POST['caminhoArquivo']);
}
?>
