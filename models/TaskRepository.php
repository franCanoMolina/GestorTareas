<?php

require_once __DIR__ . '/Task.php';
require_once __DIR__ . '/../db.php';

class TaskRepository
{
    // Obtiene las tareas propias Y las compartidas con el usuario
    public static function getTasksByUser($userId)
    {
        $db = DB::connect();
        $tasks = [];
        
        $sql = "
            SELECT DISTINCT t.* 
            FROM tasks t
            LEFT JOIN task_shares ts ON t.id = ts.task_id
            WHERE t.user_id = '$userId' OR ts.user_id = '$userId'
            ORDER BY t.id DESC
        ";

        $resultado = $db->query($sql);
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $tasks[] = new Task(
                    $fila['id'],
                    $fila['title'],
                    $fila['description'],
                    $fila['is_completed'],
                    $fila['user_id']
                );
            }
        }
        return $tasks;
    }

    public static function createTask($title, $description, $userId)
    {
        $db = DB::connect();
        $title = $db->real_escape_string($title);
        $description = $db->real_escape_string($description);
        $sql = "INSERT INTO tasks (title, description, is_completed, user_id) 
                VALUES ('$title', '$description', 0, '$userId')";
        return $db->query($sql);
    }

    public static function toggleTask($taskId, $currentStatus)
    {
        $db = DB::connect();
        $newStatus = $currentStatus ? 0 : 1;
        $sql = "UPDATE tasks SET is_completed = '$newStatus' WHERE id = '$taskId'";
        return $db->query($sql);
    }

    public static function deleteTask($taskId, $userId)
    {
        $db = DB::connect();
        // Solo el creador original puede borrar la tarea
        $sql = "DELETE FROM tasks WHERE id = '$taskId' AND user_id = '$userId'";
        return $db->query($sql);
    }

    // Compartir una tarea con otro usuario
    public static function shareTask($taskId, $targetUserId)
    {
        $db = DB::connect();
        $sql = "INSERT IGNORE INTO task_shares (task_id, user_id) VALUES ('$taskId', '$targetUserId')";
        return $db->query($sql);
    }

    // Poner categoria a una tarea
    public static function categorizeTask($taskId, $categoryId){
        $db = DB::connect();
        $sql = "UPDATE tasks SET category_id = '$categoryId' WHERE id = '$taskId'";
        return $db->query($sql);
    }

    // Crear una nueva categoria
    public static function createCategory($name)
    {
        $db = DB::connect();
        $name = $db->real_escape_string($name);
        $sql = "INSERT INTO categories (name) VALUES ('$name')";
        return $db->query($sql);
    }

    // Obtener todas las categorias
    public static function getAllCategories()
    {
        $db = DB::connect();
        $categories = [];
        $sql = "SELECT id, name FROM categories ORDER BY name ASC";
        $resultado = $db->query($sql);

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $categories[] = [
                    'id' => (int)$fila['id'],
                    'name' => $fila['name']
                ];
            }
        }
        return $categories;
    }

    // Guardar categoria
    public static function saveCategory($categoryId, $name)
    {
        $db = DB::connect();
        $name = $db->real_escape_string($name);
        $sql = "UPDATE categories SET name = '$name' WHERE id = '$categoryId'";
        return $db->query($sql);
    }

    // ELIMINAR CATEGORÍA
    public static function deleteCategory($categoryId)
    {
        $db = DB::connect();
        $categoryId = (int)$categoryId;

        // 1. Quitar la categoría de las tareas que la tengan asignada
        $db->query("UPDATE tasks SET category_id = NULL WHERE category_id = $categoryId");

        // 2. Eliminar la categoría de la tabla categories
        $sql = "DELETE FROM categories WHERE id = $categoryId";
        return $db->query($sql);
    }
}