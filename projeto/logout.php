<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('conexao.php');

// Destrói a sessão atual
session_destroy();

// Redireciona para a página de login
header("Location: login.php");