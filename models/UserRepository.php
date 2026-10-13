<?php

require_once __DIR__ . '/User.php';

class UserRepository
{
    // Registra un nuevo usuario en la base de datos
    public static function register($username, $password)
    {
        $db = new mysqli('127.0.0.1', 'root', '1234', 'task_manager', 3307);

        if ($db->connect_error) {
            die("Error de conexion: " . $db->connect_error);
        }

        // Ciframos la contraseña antes de guardarla
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";

        if ($db->query($sql)) {
            // Devolvemos el nuevo usuario creado como objeto User
            return new User($db->insert_id, $username);
        }

        return false;
    }

    // Comprueba el usuario y la contraseña para iniciar sesion
    public static function login($username, $password)
    {
        $db = new mysqli('127.0.0.1', 'root', '1234', 'task_manager', 3307);

        if ($db->connect_error) {
            die("Error de conexion: " . $db->connect_error);
        }

        $sql = "SELECT * FROM users WHERE username = '$username'";
        $resultado = $db->query($sql);

        if ($resultado && $fila = $resultado->fetch_assoc()) {
            // Verificamos si la contraseña coincide con el hash guardado
            if (password_verify($password, $fila['password'])) {
                return new User($fila['id'], $fila['username']);
            }
        }

        return false;
    }
}