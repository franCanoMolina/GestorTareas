<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar la clase User antes de iniciar la sesión
require_once __DIR__ . '/models/User.php';

// Iniciar sesión una sola vez
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enrutador de controladores
if (isset($_GET['c'])) {
    $controller = __DIR__ . '/controllers/' . $_GET['c'] . 'Controller.php';
    if (file_exists($controller)) {
        require_once $controller;
        exit;
    }
}

// Controlador por defecto (revisa si tu archivo termina en s o no: mainControllers.php o mainController.php)
require_once __DIR__ . '/controllers/mainControllers.php';