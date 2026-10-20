<?php

class DB
{
    public static function connect()
    {
        $conexion = new mysqli('127.0.0.1', 'root', 'root', 'task_manager', 3306);

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $conexion->set_charset("utf8mb4");
        return $conexion;
    }
}