<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'usuario') {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../modelos/Habitacion.php';

$habitacion = new Habitacion();
$habitaciones = $habitacion->getHabitacionesDisponibles();
$mis_reservas = $habitacion->getMisReservas($_SESSION['usuario_id']);
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario - Hotel Paraíso</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Panel de Usuario</h1>
        <nav>
            <a href="../publico/index.php">Inicio</a>
            <a href="../auth/logout.php">Cerrar Sesión</a>
        </nav>
    </header>
    <main>
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Usuario'); ?></h2>

        <section class="rooms-section">
            <h3>Habitaciones Disponibles</h3>
            <div class="rooms-grid">
                <?php if (empty($habitaciones)): ?>
                    <p>No hay habitaciones disponibles en este momento.</p>
                <?php else: ?>
                    <?php foreach ($habitaciones as $hab): ?>
                        <div class="room-card" data-id="<?php echo $hab['id']; ?>">
                            <img src="../../recursos/imagenes/room<?php echo $hab['id']; ?>.jpg" alt="<?php echo $hab['tipo']; ?>">
                            <h4><?php echo $hab['numero'] . ' - ' . $hab['tipo']; ?></h4>
                            <p>Precio: $<?php echo $hab['precio']; ?>/noche</p>
                            <p>Capacidad: <?php echo $hab['capacidad']; ?> personas</p>
                            <button class="reserve-btn" data-habitacion-id="<?php echo $hab['id']; ?>">Reservar</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="reservation-section">
            <h3>Hacer una Reserva</h3>
            <form id="reservation-form" action="procesar_reserva.php" method="POST">
                <input type="hidden" id="selected-habitacion" name="habitacion_id">
                <div class="form-group">
                    <label for="fecha_entrada">Fecha de Entrada:</label>
                    <input type="date" name="fecha_entrada" id="fecha_entrada" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="fecha_salida">Fecha de Salida:</label>
                    <input type="date" name="fecha_salida" id="fecha_salida" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                </div>
                <button type="submit">Confirmar Reserva</button>
            </form>
        </section>

        <section class="reservations-section">
            <h3>Mis Reservas</h3>
            <?php if (empty($mis_reservas)): ?>
                <p>No tienes reservas pendientes.</p>
            <?php else: ?>
                <div class="reservations-list">
                    <?php foreach ($mis_reservas as $reserva): ?>
                        <div class="reservation-card">
                            <h4>Reserva #<?php echo $reserva['id']; ?></h4>
                            <p>Habitación: <?php echo $reserva['numero'] . ' - ' . $reserva['tipo']; ?></p>
                            <p>Entrada: <?php echo date('d/m/Y', strtotime($reserva['fecha_entrada'])); ?></p>
                            <p>Salida: <?php echo date('d/m/Y', strtotime($reserva['fecha_salida'])); ?></p>
                            <p>Total Estimado: $<?php echo $reserva['precio'] * (strtotime($reserva['fecha_salida']) - strtotime($reserva['fecha_entrada'])) / (60 * 60 * 24); ?></p>
                            <p>Estado: <?php echo $reserva['estado']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <script>
        document.querySelectorAll('.reserve-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const habitacionId = btn.getAttribute('data-habitacion-id');
                document.getElementById('selected-habitacion').value = habitacionId;
                alert('Habitación seleccionada: ' + habitacionId);
            });
        });
    </script>
</body>
</html>