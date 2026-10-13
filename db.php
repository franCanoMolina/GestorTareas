<?php

class DB
{
    public static function connect()
    {
        $conexion = new mysqli('127.0.0.1', 'root', '1234', 'task_manager', 3307);

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $conexion->set_charset("utf8mb4");
        return $conexion;
    }
}