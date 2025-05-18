<?php session_start(); ?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Paraíso - Registro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <img src="../../recursos/imagenes/logo.png" alt="Hotel Paraíso Logo" class="logo">
        <h1>Registro en Hotel Paraíso</h1>
        <?php if (isset($_GET['error'])): ?>
            <p class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>
        <form id="registroForm" action="../../controladores/AuthController.php?action=registro" method="POST">
            <div class="input-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required placeholder="ej: huesped123">
            </div>
            <div class="input-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="contrasena" required placeholder="********">
            </div>
            <div class="input-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre">
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="tuemail@ejemplo.com">
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
            <button type="submit" id="registroButton">Registrar</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>