<?php
session_start();
// Cargar variables de entorno
$env = parse_ini_file(".env");
foreach ($env as $key => $value) {
    putenv("$key=$value"); // <-- Esto es lo que permite que getenv() funcione
}

require_once("db.php");
require_once("models/User.php");    // ← AÑADIR
require_once("models/Task.php");    // ← AÑADIR (si lo necesitas ya)
require_once("controllers/mainController.php");

?>