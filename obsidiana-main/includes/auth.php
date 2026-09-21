<?php
/**
 * auth.php
 * Controle de sessão e proteção de páginas privadas.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLogado(): bool
{
    return isset($_SESSION['usuario_id']);
}

function exigirLogin(): void
{
    if (!isLogado()) {
        header('Location: login.php');
        exit;
    }
}

function usuarioAtual(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function fazerLogout(): void
{
    $_SESSION = [];
    session_destroy();
}
