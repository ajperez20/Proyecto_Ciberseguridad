<?php
session_start(); // Iniciar la sesión para manejar el estado del usuario

// --- Lógica del Backend PHP ---
$message = ''; // Para mostrar mensajes al usuario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Ruta al archivo de credenciales
    $usersFile = 'users.txt'; // Asegúrate de que este archivo exista en el mismo directorio

    $authenticated = false;

    if (file_exists($usersFile)) {
        $lines = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($storedUsername, $storedPassword) = explode(':', $line);
            // Comparar credenciales
            if ($username === $storedUsername && $password === $storedPassword) {
                $authenticated = true;
                break;
            }
        }
    }

    if ($authenticated) {
        // Credenciales correctas
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        // No redirigimos aquí para mantener todo en la misma página,
        // simplemente cambiamos el contenido a mostrar.
    } else {
        // Credenciales incorrectas
        $message = '<p class="error-message">Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.</p>';
        $_SESSION['loggedin'] = false; // Asegurarse de que no esté logueado
    }
} elseif (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // --- Lógica de Cerrar Sesión ---
    $_SESSION = array(); // Destruir todas las variables de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy(); // Destruir la sesión
    header("Location: index.php"); // Redirigir para limpiar la URL
    exit();
}

// --- Determinar qué contenido mostrar (formulario o bienvenida) ---
$showLoginForm = true;
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $showLoginForm = false;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $showLoginForm ? 'Iniciar Sesión' : 'Bienvenido'; ?></title>
    <style>
        /* --- Estilos CSS (Directamente Incrustados) --- */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .container h2 {
            margin-bottom: 25px;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .input-group input[type="text"],
        .input-group input[type="password"] {
            width: calc(100% - 20px); /* Adjust for padding */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        button[type="submit"], .logout-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 15px; /* Added for spacing */
        }

        button[type="submit"]:hover, .logout-button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($showLoginForm): ?>
            <h2>Iniciar Sesión</h2>
            <?php echo $message; // Muestra mensajes de error ?>
            <form action="index.php" method="post">
                <div class="input-group">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Entrar</button>
            </form>
        <?php else: ?>
            <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Has iniciado sesión correctamente.</p>
            <p>Esta es tu página privada.</p>
            <button class="logout-button" onclick="window.location.href='index.php?logout=true'">Cerrar Sesión</button>
        <?php endif; ?>
    </div>
</body>
</html>