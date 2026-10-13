<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/TaskRepository.php';

// Cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Obtener tareas si el usuario ha iniciado sesión
$tasks = [];
if (isset($_SESSION['user'])) {
    $tasks = TaskRepository::getTasksByUser($_SESSION['user']->getId());
}

// Cargar la vista principal
require_once __DIR__ . '/../views/mainView.phtml';