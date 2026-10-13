<?php
ini_set('display_errors', 1);
require_once("models/User.php");    // ← antes del session_start
session_start();

$env = parse_ini_file(".env");
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

require_once("db.php");
//require_once("models/Task.php");
require_once("controllers/mainControllers.php");

?>