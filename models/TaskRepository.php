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
}