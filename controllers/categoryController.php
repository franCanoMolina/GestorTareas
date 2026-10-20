<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/TaskRepository.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php?c=user&login");
    exit;
}

// CREAR CATEGORÍA
if (isset($_GET['create']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = trim($_POST['category_name'] ?? '');

    if (!empty($categoryName)) {
        TaskRepository::createCategory($categoryName);
    }

    header("Location: index.php");
    exit;
}

// ELIMINAR CATEGORÍA
if (isset($_GET['delete'])) {
    $categoryId = (int)$_GET['delete'];
    $db = DB::connect();
    $db->query("DELETE FROM categories WHERE id = $categoryId");

    header("Location: index.php");
    exit;
}