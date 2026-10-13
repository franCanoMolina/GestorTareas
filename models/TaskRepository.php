<?php

require_once __DIR__ . '/Task.php';

class TaskRepository
{
    // Obtiene todas las tareas que pertenecen a un usuario concreto
    public static function getTasksByUser($userId)
    {
        $db = new mysqli('127.0.0.1', 'root', '1234', 'task_manager', 3307);

        if ($db->connect_error) {
            die("Error de conexion: " . $db->connect_error);
        }

        $tasks = [];
        $sql = "SELECT * FROM tasks WHERE user_id = '$userId' ORDER BY id DESC";
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

    // Añadir un nuevo paso a una tarea
    public static function addTaskLine($taskId, $text)
    {
        $db = new mysqli('127.0.0.1', 'root', '1234', 'task_manager', 3307);
        $sql = "INSERT INTO task_lines (task_id, text, is_completed) VALUES ('$taskId', '$text', 0)";
        return $db->query($sql);
    }

    // Obtener todos los pasos de una tarea concreta
    public static function getLinesByTaskId($taskId)
    {
        $db = new mysqli('127.0.0.1', 'root', '1234', 'task_manager', 3307);
        $lines = [];
        $sql = "SELECT * FROM task_lines WHERE task_id = '$taskId'";
        $res = $db->query($sql);

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $lines[] = new TaskLine($row['id'], $row['task_id'], $row['text'], $row['is_completed']);
            }
        }
        return $lines;
    }

    // Crea una nueva tarea para el usuario
    public static function createTask($title, $description, $userId)
    {
        $db = new mysqli('127.0.0.1', 'root', '1234', 'task_manager', 3307);

        if ($db->connect_error) {
            die("Error de conexion: " . $db->connect_error);
        }

        $sql = "INSERT INTO tasks (title, description, is_completed, user_id) 
                VALUES ('$title', '$description', 0, '$userId')";

        return $db->query($sql);
    }

    // Cambia el estado de una tarea (completada o pendiente)
    public static function toggleTask($taskId, $currentStatus)
    {
        $db = new mysqli('127.0.0.1', 'root', '1234', 'task_manager', 3307);

        if ($db->connect_error) {
            die("Error de conexion: " . $db->connect_error);
        }

        // Si estaba en 1 pasa a 0, si estaba en 0 pasa a 1
        $newStatus = $currentStatus ? 0 : 1;
        $sql = "UPDATE tasks SET is_completed = '$newStatus' WHERE id = '$taskId'";

        return $db->query($sql);
    }

    // Elimina una tarea por su id
    public static function deleteTask($taskId)
    {
        $db = new mysqli('127.0.0.1', 'root', '1234', 'task_manager', 3307);

        if ($db->connect_error) {
            die("Error de conexion: " . $db->connect_error);
        }

        $sql = "DELETE FROM tasks WHERE id = '$taskId'";

        return $db->query($sql);
    }
}