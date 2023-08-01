<?php
// copiar_arquivo.php
function copiarParaRede($arquivo) {
    $destinoRede = '//files/MATRICULAS/100000/' . $arquivo;
    if (!copy($arquivo, $destinoRede)) {
        return false;
    }
    return true;
}

if (isset($_POST['arquivo'])) {
    $arquivo = __DIR__ . '/historico/' . $_POST['arquivo'];
    if (copiarParaRede($arquivo)) {
        echo 'Arquivo copiado com sucesso para a rede.';
    } else {
        echo 'Falha ao copiar arquivo para a rede.';
    }
}
