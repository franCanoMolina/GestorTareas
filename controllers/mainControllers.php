<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserRepository.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/TaskRepository.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$tasks = [];
$otherUsers = [];

if (isset($_SESSION['user'])) {
    $currentId = $_SESSION['user']->getId();
    $tasks = TaskRepository::getTasksByUser($currentId);
    $otherUsers = UserRepository::getAllExcept($currentId);
}

require_once __DIR__ . '/../views/mainView.phtml';