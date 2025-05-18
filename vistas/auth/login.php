<?php session_start(); ?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Paraíso - Login</title>
    <link rel="stylesheet" href="style.css">
    <script type="importmap">
        {
            "imports": {
                "canvas-confetti": "https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"
            }
        }
    </script>
</head>
<body>
    <div class="login-container">
        <img src="../../recursos/imagenes/logo.png" alt="Hotel Paraíso Logo" class="logo">
        <h1>Bienvenido a Hotel Paraíso</h1>
        <?php if (isset($_GET['error'])): ?>
            <p class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php elseif (isset($_GET['success'])): ?>
            <p class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php endif; ?>
        <form id="loginForm" action="../../controladores/AuthController.php?action=login" method="POST">
            <div class="input-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required placeholder="ej: huesped123">
            </div>
            <div class="input-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="contrasena" required placeholder="********">
            </div>
            <div class="input-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol_id" required>
                    <option value="" disabled selected>Seleccione su rol</option>
                    <option value="3">Usuario</option>
                    <option value="1">Administrador</option>
                    <option value="2">Recepcionista</option>
                    <option value="4">Mucama</option>
                </select>
            </div>
            <button type="submit" id="loginButton">Ingresar</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
    <script src="script.js" type="module"></script>
</body>
</html>