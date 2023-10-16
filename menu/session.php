<?php

$sessionTimeout = 7200; // 30 minutos
session_set_cookie_params($sessionTimeout);
session_start();


if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $sessionTimeout) {
    // Sessão expirada, redireciona o usuário para a tela de login
    session_destroy();
    header('Location: /login'); 
    exit();
}

if (!isset($_SESSION['USUID'])) {
    // Usuário não autenticado, redireciona o usuário para a tela de login
    session_destroy();
    header('Location: /login'); 
    exit();
}

// Atualiza o tempo da última atividade
$_SESSION['last_activity'] = time();

?>