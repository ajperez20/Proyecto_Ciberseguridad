<?php
session_start();

// --- Configuración ---
$usersFile = 'users.txt'; // Archivo con credenciales en texto plano (SOLO PARA PRUEBAS)

// --- Lógica de Autenticación ---
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        // Registro de nuevo usuario
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $passphrase = $_POST['passphrase'] ?? '';

        if (empty($username) || empty($password) || empty($passphrase)) {
            $message = '<p class="error">Todos los campos son requeridos</p>';
        } else {
            // Comprobar si el usuario ya existe
            if (file_exists($usersFile)) {
                $lines = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $parts = explode(':', $line);
                    if ($username === $parts[0]) {
                        $message = '<p class="error">El usuario ya existe</p>';
                        break;
                    }
                }
            }

            // Si el usuario no existe, agregarlo
            if (empty($message)) {
                // Hashear la contraseña y la passphrase
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $hashedPassphrase = password_hash($passphrase, PASSWORD_DEFAULT);
                addTestUser ($username, $hashedPassword, $hashedPassphrase);
                $message = '<p class="success">Usuario registrado correctamente. Puedes iniciar sesión ahora.</p>';
            }
        }
    } elseif (isset($_POST['login'])) {
        // Lógica de inicio de sesión
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $passphrase = $_POST['passphrase'] ?? '';

        if (empty($username) || empty($password) || empty($passphrase)) {
            $message = '<p class="error">Todos los campos son requeridos</p>';
        } else {
            if (file_exists($usersFile)) {
                $lines = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $authenticated = false;
               
                foreach ($lines as $line) {
                    $parts = explode(':', $line);
                    if (count($parts) === 3) {
                        list($storedUser , $storedPass, $storedPhrase) = $parts;
                       
                        // Verificar la contraseña y la passphrase hasheadas
                        if ($username === $storedUser  &&
                            password_verify($password, $storedPass) &&
                            password_verify($passphrase, $storedPhrase)) {
                            $authenticated = true;
                            break;
                        }
                    }
                }

                if ($authenticated) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                } else {
                    $message = '<p class="error">Credenciales incorrectas</p>';
                }
            } else {
                $message = '<p class="error">El archivo de usuarios no existe. Crea primero algunos usuarios.</p>';
            }
        }
    }
}

// Función para añadir usuarios de prueba
function addTestUser ($user, $pass, $phrase) {
    $line = "$user:$pass:$phrase" . PHP_EOL;
    file_put_contents('users.txt', $line, FILE_APPEND);
}

// ==========================================
// CREACIÓN DE USUARIOS DE PRUEBA (descomenta estas líneas si es necesario)
// addTestUser ('admin', 'admin123', 'mi passphrase secreta');
// ==========================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Autenticación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .error {
            color: #e74c3c;
            text-align: center;
        }
        .success {
            color: #2ecc71;
            text-align: center;
        }
        .btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .toggle-btn {
            margin-top: 1rem;
            background-color: #e67e22;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <?php if (empty($_SESSION['loggedin'])): ?>
            <h2>Registro / Iniciar Sesión</h2>
            <?php echo $message; ?>
           
            <div id="login-form">
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Usuario:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                   
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                   
                    <div class="form-group">
                        <label for="passphrase">Passphrase (frase secreta):</label>
                        <input type="password" id="passphrase" name="passphrase" required>
                    </div>
                   
                    <button type="submit" name="login" class="btn">Ingresar</button>
                </form>
            </div>

            <button class="btn toggle-btn" onclick="toggleForms()">Registrar Usuario</button>

            <div id="register-form" style="display: none;">
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Usuario:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                   
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                   
                    <div class="form-group">
                        <label for="passphrase">Passphrase (frase secreta):</label>
                        <input type="password" id="passphrase" name="passphrase" required>
                    </div>
                   
                    <button type="submit" name="register" class="btn">Registrar</button>
                </form>
            </div>

            <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem; color: #666;">
                Usuario de prueba: admin / admin123 / mi passphrase secreta
            </p>
        <?php else: ?>
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p class="success">¡Has iniciado sesión correctamente!</p>
            <p>Este es un área restringida.</p>
            <a href="?logout=1" style="display: inline-block; width: 100%; text-align: center; margin-top: 1rem; color: #3498db;">Cerrar sesión</a>
        <?php endif; ?>
    </div>

    <script>
        function toggleForms() {
            var loginForm = document.getElementById('login-form');
            var registerForm = document.getElementById('register-form');
            if (loginForm.style.display === "none") {
                loginForm.style.display = "block";
                registerForm.style.display = "none";
            } else {
                loginForm.style.display = "none";
                registerForm.style.display = "block";
            }
        }
    </script>
</body>
</html>
<?php
// Manejo de cierre de sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ".strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}
?>