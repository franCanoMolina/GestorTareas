<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserRepository.php';

// Si el usuario ya está autenticado, mandarlo al inicio directamente
if (isset($_SESSION['user']) && !isset($_GET['logout'])) {
    header("Location: index.php");
    exit;
}

// REGISTRO
if (isset($_GET['register'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!empty($username) && !empty($password)) {
            $user = UserRepository::register($username, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                header("Location: index.php");
                exit;
            } else {
                $error = "Error al registrar el usuario o nombre ya ocupado.";
            }
        }
    }
    require_once __DIR__ . '/../views/register.phtml';
    exit;
}

// LOGIN
if (isset($_GET['login'])) {
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $user = UserRepository::login($username, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            // Redirige directamente a la pantalla de proyectos antes del gestor personal
            header("Location: index.php?c=project");
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    }

    require_once __DIR__ . '/../views/login.phtml';
    exit;
}