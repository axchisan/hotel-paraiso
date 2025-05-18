<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../modelos/Administrador.php';

$admin = new Administrador();
$habitaciones = $admin->getHabitaciones();
$usuarios = $admin->getUsuarios();
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - Hotel Paraíso</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Panel de Administrador</h1>
        <nav>
            <a href="../publico/index.php">Inicio</a>
            <a href="../auth/logout.php">Cerrar Sesión</a>
        </nav>
    </header>
    <main>
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Administrador'); ?></h2>

        <section class="admin-section">
            <h3>Gestionar Habitaciones</h3>
            <div class="form-section">
                <h4>Agregar Habitación</h4>
                <form action="procesar_habitacion.php" method="POST">
                    <input type="hidden" name="action" value="agregar">
                    <div class="form-group">
                        <label for="numero">Número:</label>
                        <input type="text" name="numero" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <input type="text" name="tipo" required>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio ($/noche):</label>
                        <input type="number" name="precio" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="capacidad">Capacidad:</label>
                        <input type="number" name="capacidad" required>
                    </div>
                    <button type="submit">Agregar</button>
                </form>
            </div>

            <div class="list-section">
                <h4>Lista de Habitaciones</h4>
                <div class="rooms-list">
                    <?php foreach ($habitaciones as $hab): ?>
                        <div class="room-card">
                            <h4><?php echo $hab['numero'] . ' - ' . $hab['tipo']; ?></h4>
                            <p>Precio: $<?php echo $hab['precio']; ?>/noche</p>
                            <p>Capacidad: <?php echo $hab['capacidad']; ?> personas</p>
                            <p>Estado: <?php echo $hab['estado']; ?></p>
                            <div class="action-buttons">
                                <form action="editar_habitacion.php" method="GET" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $hab['id']; ?>">
                                    <button type="submit" class="edit-btn">Editar</button>
                                </form>
                                <form action="procesar_habitacion.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="eliminar">
                                    <input type="hidden" name="id" value="<?php echo $hab['id']; ?>">
                                    <button type="submit" class="delete-btn">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="admin-section">
            <h3>Gestionar Usuarios</h3>
            <div class="list-section">
                <h4>Lista de Usuarios</h4>
                <div class="users-list">
                    <?php foreach ($usuarios as $usuario): ?>
                        <div class="user-card">
                            <h4><?php echo $usuario['username']; ?></h4>
                            <p>Nombre: <?php echo $usuario['nombre']; ?></p>
                            <p>Email: <?php echo $usuario['email']; ?></p>
                            <p>Rol: <?php echo $usuario['rol']; ?></p>
                            <form action="procesar_usuario.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="eliminar">
                                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                <button type="submit" class="delete-btn">Eliminar</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
</body>
</html> 