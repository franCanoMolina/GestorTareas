<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserRepository.php';
require_once __DIR__ . '/../models/ProjectRepository.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php?c=user&login");
    exit;
}

$currentUserId = $_SESSION['user']->getId();

// 1. CREAR PROYECTO
if (isset($_GET['create']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['project_name'] ?? '');
    if (!empty($name)) {
        $newId = ProjectRepository::createProject($name, $currentUserId);
        // Lleva directamente al proyecto recién creado
        if ($newId) {
            header("Location: index.php?c=project&view=" . $newId);
            exit;
        }
    }
    header("Location: index.php?c=project");
    exit;
}

// 2. VER UN PROYECTO ESPECÍFICO (CON SUS TAREAS COLABORATIVAS)
if (isset($_GET['view'])) {
    $projectId = (int)$_GET['view'];
    $project = ProjectRepository::getProjectById($projectId);

    if (!$project || !ProjectRepository::isMember($projectId, $currentUserId)) {
        header("Location: index.php?c=project");
        exit;
    }

    $projectTasks = ProjectRepository::getTasksByProject($projectId);
    $otherUsers = UserRepository::getAllExcept($currentUserId);

    require_once __DIR__ . '/../views/projectView.phtml';
    exit;
}

// 3. AÑADIR TAREA AL PROYECTO
if (isset($_GET['addTask']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = (int)$_POST['project_id'];
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (!empty($title) && ProjectRepository::isMember($projectId, $currentUserId)) {
        ProjectRepository::addTaskToProject($title, $description, $currentUserId, $projectId);
    }
    header("Location: index.php?c=project&view=" . $projectId);
    exit;
}

// 4. COMPARTIR PROYECTO
if (isset($_GET['share']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = (int)$_POST['project_id'];
    $targetUserId = (int)$_POST['user_id'];

    if ($projectId > 0 && $targetUserId > 0 && ProjectRepository::isMember($projectId, $currentUserId)) {
        ProjectRepository::addMember($projectId, $targetUserId);
    }
    header("Location: index.php?c=project&view=" . $projectId);
    exit;
}

// POR DEFECTO: PANTALLA INICIAL DE PROYECTOS (Lista de proyectos y formulario para crear uno)
$projects = ProjectRepository::getProjectsByUser($currentUserId);
require_once __DIR__ . '/../views/projectsListView.phtml';
exit;