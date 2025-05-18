<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'mucama') {
    header('Location: ../../auth/login.php');
    exit();
}
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Mucama - Hotel Paraíso</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Panel de Mucama</h1>
        <nav>
            <a href="../auth/logout.php">Cerrar Sesión</a>
        </nav>
    </header>
    <main>
        <h2>Bienvenido, Mucama</h2>
        <p>Desde aquí puedes ver las habitaciones asignadas para limpieza.</p>
    </main>
</body>
</html>