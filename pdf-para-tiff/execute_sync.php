<?php
$batFilePath = 'sincronizar.bat';

// Use exec()
exec($batFilePath, $output, $returnVar);

// Verificar se a execução foi bem-sucedida
if ($returnVar === 0) {
    echo "O arquivo .bat foi executado com sucesso!";
} else {
    echo "Ocorreu um erro durante a execução do arquivo .bat.";
    // Para obter informações detalhadas sobre o erro, você pode imprimir o $output
    // ou usar outras funções, como 'shell_exec()'.
}
?>
