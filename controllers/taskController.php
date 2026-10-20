<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/TaskRepository.php';
require_once __DIR__ . '/../models/Category.php';

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

// COMPARTIR TAREA
if (isset($_GET['share']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = (int)$_POST['task_id'];
    $targetUserId = (int)$_POST['user_id'];

    if ($taskId > 0 && $targetUserId > 0) {
        TaskRepository::shareTask($taskId, $targetUserId);
    }
    header("Location: index.php");
    exit;
}

// 1. CREAR CATEGORÍA NUEVA (Este es el que te falta)
if (isset($_GET['newCategory']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = trim($_POST['category_name'] ?? '');
    if (!empty($categoryName)) {
        TaskRepository::createCategory($categoryName);
    }
    header("Location: index.php");
    exit;
}

// 2. PONER CATEGORIA A TAREA (El que ya tienes)
if (isset($_GET['categorize']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = (int)$_POST['task_id'];
    $categoryId = (int)$_POST['category_id'];

    if ($taskId > 0 && $categoryId > 0) {
        TaskRepository::categorizeTask($taskId, $categoryId);
    }
    header("Location: index.php");
    exit;
}

// ELIMINAR CATEGORÍA
if (isset($_GET['deleteCategory'])) {
    $categoryId = (int)$_GET['deleteCategory'];
    
    if ($categoryId > 0) {
        TaskRepository::deleteCategory($categoryId);
    }
    
    header("Location: index.php");
    exit;
}

// MOSTRAR TAREAS POR CATEGORÍA
if (isset($_GET['category'])) {
    $categoryId = (int)$_GET['category'];
    $tasks = TaskRepository::getTasksByCategory($categoryId, $userId);
    header("Location: index.php");
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
    TaskRepository::deleteTask($taskId, $userId);
    header("Location: index.php");
    exit;
}