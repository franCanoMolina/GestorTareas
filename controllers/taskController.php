<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/TaskRepository.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php?c=user&login");
    exit;
}

$userId = $_SESSION['user']->getId();

// CREAR TAREA
if (isset($_GET['new'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!empty($title)) {
            TaskRepository::createTask($title, $description, $userId);
            header("Location: index.php");
            exit;
        }
    }

    require_once __DIR__ . '/../views/createTaskView.phtml';
    exit;
}

// COMPLETAR / DESMARCAR TAREA
if (isset($_GET['toggle'])) {
    $taskId = (int)$_GET['toggle'];
    $currentStatus = (int)($_GET['status'] ?? 0);

    TaskRepository::toggleTask($taskId, $currentStatus);
    header("Location: index.php");
    exit;
}

// ELIMINAR TAREA
if (isset($_GET['delete'])) {
    $taskId = (int)$_GET['delete'];
    TaskRepository::deleteTask($taskId);
    header("Location: index.php");
    exit;
}