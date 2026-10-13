<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserRepository.php';

// Cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// INICIO DE SESIÓN
if (isset($_GET['login'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $user = UserRepository::login($username, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            header("Location: index.php");
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    }
    require_once __DIR__ . '/../views/login.phtml';
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
                $error = "El usuario ya existe o hubo un error al registrarlo.";
            }
        } else {
            $error = "Por favor, completa todos los campos.";
        }
    }
    require_once __DIR__ . '/../views/register.phtml';
    exit;
}