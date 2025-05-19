<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'mucama') {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../modelos/Mucama.php';

$mucama = new Mucama();
$tareas_pendientes = $mucama->getTareasPendientes();
$tareas_completadas = $mucama->getTareasCompletadas();
$habitaciones = $mucama->getHabitaciones();
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
            <a href="../publico/index.php">Inicio</a>
            <a href="../auth/logout.php">Cerrar Sesión</a>
        </nav>
    </header>
    <main>
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Mucama'); ?></h2>

        <section>
            <h3>Asignar Nueva Tarea</h3>
            <form action="procesar_tarea.php" method="POST">
                <div>
                    <label for="habitacion_id">Habitación:</label>
                    <select name="habitacion_id" id="habitacion_id" required>
                        <option value="">Seleccione una habitación</option>
                        <?php foreach ($habitaciones as $habitacion): ?>
                            <option value="<?php echo $habitacion['id']; ?>">
                                <?php echo htmlspecialchars($habitacion['numero'] . ' - ' . $habitacion['tipo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" placeholder="Detalles de la tarea" required></textarea>
                </div>
                <div>
                    <label for="prioridad">Prioridad:</label>
                    <select name="prioridad" id="prioridad" required>
                        <option value="1">Alta (1)</option>
                        <option value="2">Media (2)</option>
                        <option value="3">Baja (3)</option>
                    </select>
                </div>
                <input type="hidden" name="action" value="asignar">
                <button type="submit">Asignar Tarea</button>
            </form>
        </section>

        <h3>Tareas Pendientes</h3>
        <?php if (isset($_GET['success'])): ?>
            <div class="notification success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="notification error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <?php if (empty($tareas_pendientes)): ?>
            <p>No hay tareas pendientes.</p>
        <?php else: ?>
            <div class="tasks-list">
                <?php foreach ($tareas_pendientes as $tarea): ?>
                    <div class="task-card">
                        <h4>Habitación #<?php echo htmlspecialchars($tarea['numero'] . ' - ' . $tarea['tipo']); ?></h4>
                        <p>Descripción: <?php echo htmlspecialchars($tarea['descripcion'] ?? 'Limpieza general'); ?></p>
                        <form action="procesar_tarea.php" method="POST" style="display:inline;">
                            <input type="hidden" name="tarea_id" value="<?php echo $tarea['id']; ?>">
                            <input type="hidden" name="action" value="completar">
                            <button type="submit">Marcar como Completada</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h3>Últimas Tareas Completadas</h3>
        <?php if (empty($tareas_completadas)): ?>
            <p>No hay tareas completadas recientes.</p>
        <?php else: ?>
            <div class="tasks-list">
                <?php foreach ($tareas_completadas as $tarea): ?>
                    <div class="task-card">
                        <h4>Habitación #<?php echo htmlspecialchars($tarea['numero'] . ' - ' . $tarea['tipo']); ?></h4>
                        <p>Fecha: <?php echo date('d/m/Y H:i', strtotime($tarea['fecha_completada'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>