<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'recepcionista') {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../modelos/Recepcionista.php';

$recepcionista = new Recepcionista();
$reservas = $recepcionista->getReservas();
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Recepcionista - Hotel Paraíso</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Panel de Recepcionista</h1>
        <nav>
            <a href="../../publico/index.php">Inicio</a>
            <a href="../../auth/logout.php">Cerrar Sesión</a>
        </nav>
    </header>
    <main>
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Recepcionista'); ?></h2>
        <h3>Gestión de Reservas</h3>
        <?php if (isset($_GET['success'])): ?>
            <div class="notification success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="notification error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <?php if (empty($reservas)): ?>
            <p>No hay reservas.</p>
        <?php else: ?>
            <div class="reservations-list">
                <?php foreach ($reservas as $reserva): ?>
                    <div class="reservation-card">
                        <h4>Reserva #<?php echo $reserva['id']; ?></h4>
                        <p>Usuario: <?php echo $reserva['username']; ?></p>
                        <p>Habitación: <?php echo $reserva['numero'] . ' - ' . $reserva['tipo']; ?></p>
                        <p>Entrada: <?php echo date('d/m/Y', strtotime($reserva['fecha_entrada'])); ?></p>
                        <p>Salida: <?php echo date('d/m/Y', strtotime($reserva['fecha_salida'])); ?></p>
                        <p>Estado: <?php echo $reserva['estado']; ?></p>
                        <div class="action-buttons">
                            <?php if ($reserva['estado'] === 'pendiente'): ?>
                                <form action="procesar_asignacion.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                                    <button type="submit" name="action" value="asignar">Asignar</button>
                                    <button type="submit" name="action" value="checkin">Check-in</button>
                                </form>
                            <?php elseif ($reserva['estado'] === 'checkin'): ?>
                                <form action="procesar_asignacion.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                                    <button type="submit" name="action" value="checkout">Check-out</button>
                                </form>
                            <?php endif; ?>
                            <?php if ($reserva['estado'] !== 'finalizada' && $reserva['estado'] !== 'cancelada'): ?>
                                <form action="procesar_asignacion.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                                    <button type="submit" name="action" value="cancelar" class="cancel-btn">Cancelar</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>