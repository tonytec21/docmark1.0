<?php
// Verifica se a função não existe antes de declará-la
if (!function_exists('verificar_sessao_ativa')) {
    function verificar_sessao_ativa() {
        // Inicia a sessão
        session_start();

        // Verifica se o usuário está autenticado (possui uma sessão ativa)
        if (!isset($_SESSION["user_id"]) || !isset($_SESSION["empresa_id"])) {
            // Se não estiver autenticado, redireciona para a página de login
            header("Location: ../login.php");
            exit();
        }
    }
}
