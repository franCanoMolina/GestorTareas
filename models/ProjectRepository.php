<?php

require_once __DIR__ . '/Project.php';
require_once __DIR__ . '/Task.php';
require_once __DIR__ . '/../db.php';

class ProjectRepository
{
    public static function createProject($name, $ownerId)
    {
        $db = DB::connect();
        $name = $db->real_escape_string($name);
        $ownerId = (int)$ownerId;

        $db->query("INSERT INTO projects (name, owner_id) VALUES ('$name', $ownerId)");
        $projectId = $db->insert_id;

        if ($projectId > 0) {
            $db->query("INSERT IGNORE INTO project_members (project_id, user_id) VALUES ($projectId, $ownerId)");
        }
        return $projectId;
    }

    public static function getProjectsByUser($userId)
    {
        $db = DB::connect();
        $userId = (int)$userId;
        $projects = [];

        $sql = "
            SELECT p.id, p.name, p.owner_id 
            FROM projects p
            INNER JOIN project_members pm ON p.id = pm.project_id
            WHERE pm.user_id = $userId
            ORDER BY p.id DESC
        ";

        $res = $db->query($sql);
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $projects[] = new Project($row['id'], $row['name'], $row['owner_id']);
            }
        }
        return $projects;
    }

    public static function getProjectById($projectId)
    {
        $db = DB::connect();
        $projectId = (int)$projectId;
        $res = $db->query("SELECT * FROM projects WHERE id = $projectId LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return new Project($row['id'], $row['name'], $row['owner_id']);
        }
        return null;
    }

    public static function addMember($projectId, $userId)
    {
        $db = DB::connect();
        $projectId = (int)$projectId;
        $userId = (int)$userId;
        $sql = "INSERT IGNORE INTO project_members (project_id, user_id) VALUES ($projectId, $userId)";
        return $db->query($sql);
    }

    public static function isMember($projectId, $userId)
    {
        $db = DB::connect();
        $projectId = (int)$projectId;
        $userId = (int)$userId;
        $res = $db->query("SELECT id FROM project_members WHERE project_id = $projectId AND user_id = $userId LIMIT 1");
        return $res && $res->num_rows > 0;
    }

    public static function addTaskToProject($title, $description, $userId, $projectId)
    {
        $db = DB::connect();
        $title = $db->real_escape_string($title);
        $description = $db->real_escape_string($description);
        $userId = (int)$userId;
        $projectId = (int)$projectId;

        $sql = "INSERT INTO tasks (title, description, is_completed, user_id, project_id) 
                VALUES ('$title', '$description', 0, $userId, $projectId)";
        return $db->query($sql);
    }

    public static function getTasksByProject($projectId)
    {
        $db = DB::connect();
        $projectId = (int)$projectId;
        $tasks = [];

        $sql = "SELECT * FROM tasks WHERE project_id = $projectId ORDER BY id DESC";
        $res = $db->query($sql);
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $tasks[] = new Task(
                    $row['id'],
                    $row['title'],
                    $row['description'] ?? '',
                    $row['is_completed'] ?? 0,
                    $row['user_id']
                );
            }
        }
        return $tasks;
    }
}