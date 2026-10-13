<?php
require_once __DIR__ . '/models/User.php';
session_start();

if (isset($_GET['c'])) {
    $controllerName = $_GET['c'] . 'Controller.php';
    $file = __DIR__ . '/controllers/' . $controllerName;
    
    if (file_exists($file)) {
        require_once $file;
        exit; // Detener la ejecución para no cargar el controlador por defecto
    }
}

// Si no hay parámetro ?c= o el archivo no existe, carga el principal
require_once __DIR__ . '/controllers/mainControllers.php';