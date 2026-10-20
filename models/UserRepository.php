<?php

require_once __DIR__ . '/User.php';
require_once __DIR__ . '/../db.php';

class UserRepository
{
    public static function register($username, $password)
    {
        $db = DB::connect();
        $username = $db->real_escape_string($username);
        
        // Comprobar si ya existe
        $check = $db->query("SELECT id FROM users WHERE username = '$username'");
        if ($check && $check->num_rows > 0) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hash')";
        
        if ($db->query($sql)) {
            return new User($db->insert_id, $username);
        }
        return false;
    }

    public static function login($username, $password){
        $db = DB::connect();
        $username = $db->real_escape_string($username);

        $sql = "SELECT id, username, password FROM users WHERE username = '$username' LIMIT 1";
        $res = $db->query($sql);

        if ($res && $fila = $res->fetch_assoc()) {
            // Valida con hash O con texto plano (para las cuentas viejas)
            if (password_verify($password, $fila['password']) || $password === $fila['password']) {
                return new User($fila['id'], $fila['username']);
            }
        }
        return false;
    }

    // Obtener todos los demás usuarios para poder compartir tareas
    public static function getAllExcept($currentUserId)
    {
        $db = DB::connect();
        $users = [];
        $sql = "SELECT id, username FROM users WHERE id != '$currentUserId' ORDER BY username ASC";
        $resultado = $db->query($sql);

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $users[] = new User($fila['id'], $fila['username']);
            }
        }
        return $users;
    }
}