<?php

class UserSession
{
    public function __construct()
    {
        // Verificar si ya hay una sesión activa antes de intentar iniciar una nueva
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function setCurrentUser($user)
    {
        $_SESSION['user'] = $user;
    }

    public function getCurrentUser()
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    public function closeSession()
    {
        session_unset();
        session_destroy();
    }

    public function checkSession()
    {
        $currentPage = basename($_SERVER['PHP_SELF']);

        // Si no hay una sesión y no está en la página de inicio, redirige a la página de inicio
        if (!$this->getCurrentUser() && $currentPage !== 'index.php') {
            header('Location: /Banco/index.php');
            exit();
        }
    }
}

// Crear una instancia de UserSession y realizar la verificación al inicio
$userSession = new UserSession();
$userSession->checkSession();
