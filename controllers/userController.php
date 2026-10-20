<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserRepository.php';

// Si el usuario ya está autenticado, mandarlo al inicio directamente
if (isset($_SESSION['user']) && !isset($_GET['logout'])) {
    header("Location: index.php");
    exit;
}

// REGISTRO
if (isset($_GET['register'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!empty($username) && !empty($password)) {
            $user = UserRepository::register($username, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                header("Location: index.php");
                exit;
            } else {
                $error = "Error al registrar el usuario o nombre ya ocupado.";
            }
        }
    }
    require_once __DIR__ . '/../views/register.phtml';
    exit;
}

if (isset($_GET['login'])) {
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // 1. Conexión directa para inspeccionar la base de datos
        $db = DB::connect();
        $uEscaped = $db->real_escape_string($username);
        $res = $db->query("SELECT * FROM users WHERE username = '$uEscaped' LIMIT 1");

        echo "<pre style='background:#f4f4f4; padding:15px; border:1px solid #ccc;'>";
        echo "<h3>Depuración del Login:</h3>";
        echo "Usuario introducido: " . htmlspecialchars($username) . "\n";
        echo "Contraseña introducida: " . htmlspecialchars($password) . "\n";

        if ($res && $fila = $res->fetch_assoc()) {
            echo "Usuario encontrado en BD (ID: {$fila['id']})\n";
            echo "Password en BD: {$fila['password']}\n";

            $esBcrypt = password_verify($password, $fila['password']);
            $esPlano  = ($password === $fila['password']);

            echo "¿Coincide password_verify()?: " . ($esBcrypt ? 'SÍ' : 'NO') . "\n";
            echo "¿Coincide texto plano?: " . ($esPlano ? 'SÍ' : 'NO') . "\n";

            if ($esBcrypt || $esPlano) {
                $_SESSION['user'] = new User($fila['id'], $fila['username']);
                echo "\n¡Autenticación correcta! Redirigiendo a index.php...";
                echo "<br><a href='index.php'>Haz clic aquí si no redirige automáticamente</a>";
                header("Location: index.php");
                exit;
            } else {
                echo "\nERROR: La contraseña no coincide con la guardada en la base de datos.\n";
            }
        } else {
            echo "\nERROR: No se encontró ningún usuario con el nombre '$username' en la tabla users.\n";
        }
        echo "</pre>";
        exit;
    }

    require_once __DIR__ . '/../views/login.phtml';
    exit;
}