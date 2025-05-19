<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'recepcionista') {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../modelos/Recepcionista.php';

$recepcionista = new Recepcionista();
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : '';
$reservas = $recepcionista->getReservas();
$usuarios = $recepcionista->getUsuarios();
$habitaciones = $recepcionista->getHabitacionesDisponibles();

if ($estado_filtro) {
    $reservas = array_filter($reservas, fn($r) => $r['estado'] === $estado_filtro);
}
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Recepcionista - Hotel Paraíso</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Panel de Recepcionista</h1>
        <nav>
            <a href="../publico/index.php">Inicio</a>
            <a href="../auth/logout.php">Cerrar Sesión</a>
        </nav>
    </header>
    <main>
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Recepcionista'); ?></h2>

        <section class="add-reservation-section">
            <h3>Agregar Nueva Reserva</h3>
            <form id="add-reservation-form" class="add-reservation-form">
                <div class="form-group">
                    <label for="usuario_id">Usuario:</label>
                    <select name="usuario_id" id="usuario_id" required>
                        <option value="">Seleccione un usuario</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo $usuario['id']; ?>"><?php echo htmlspecialchars($usuario['username']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="habitacion_id">Habitación:</label>
                    <select name="habitacion_id" id="habitacion_id" required>
                        <option value="">Seleccione una habitación</option>
                        <?php foreach ($habitaciones as $habitacion): ?>
                            <option value="<?php echo $habitacion['id']; ?>"><?php echo htmlspecialchars($habitacion['numero'] . ' - ' . $habitacion['tipo']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_entrada">Fecha de Entrada:</label>
                    <input type="date" name="fecha_entrada" id="fecha_entrada" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="fecha_salida">Fecha de Salida:</label>
                    <input type="date" name="fecha_salida" id="fecha_salida" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                </div>
                <button type="submit" class="add-btn"><i class="fas fa-plus"></i> Agregar Reserva</button>
            </form>
        </section>

        <section class="reservations-section">
            <h3>Gestión de Reservas</h3>
            <div class="filter-section">
                <label for="estado">Filtrar por Estado:</label>
                <select id="estado-filtro">
                    <option value="">Todos</option>
                    <option value="pendiente" <?php echo $estado_filtro === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="asignada" <?php echo $estado_filtro === 'asignada' ? 'selected' : ''; ?>>Asignada</option>
                    <option value="checkin" <?php echo $estado_filtro === 'checkin' ? 'selected' : ''; ?>>Check-in</option>
                    <option value="finalizada" <?php echo $estado_filtro === 'finalizada' ? 'selected' : ''; ?>>Finalizada</option>
                    <option value="cancelada" <?php echo $estado_filtro === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>
            <div class="reservations-list" id="reservations-list">
                <?php if (empty($reservas)): ?>
                    <p>No hay reservas.</p>
                <?php else: ?>
                    <?php foreach ($reservas as $reserva): ?>
                        <div class="reservation-card" data-reserva-id="<?php echo $reserva['id']; ?>">
                            <h4>Reserva #<?php echo $reserva['id']; ?></h4>
                            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($reserva['username']); ?></p>
                            <p><strong>Habitación:</strong> <?php echo htmlspecialchars($reserva['numero'] . ' - ' . $reserva['tipo']); ?></p>
                            <p><strong>Entrada:</strong> <?php echo date('d/m/Y', strtotime($reserva['fecha_entrada'])); ?></p>
                            <p><strong>Salida:</strong> <?php echo date('d/m/Y', strtotime($reserva['fecha_salida'])); ?></p>
                            <p><strong>Estado:</strong> <span class="estado"><?php echo htmlspecialchars($reserva['estado']); ?></span></p>
                            <div class="action-buttons">
                                <?php if ($reserva['estado'] === 'pendiente'): ?>
                                    <button class="action-btn asignar" data-action="asignar"><i class="fas fa-check"></i> Asignar</button>
                                    <button class="action-btn checkin" data-action="checkin"><i class="fas fa-sign-in-alt"></i> Check-in</button>
                                <?php elseif ($reserva['estado'] === 'checkin'): ?>
                                    <button class="action-btn checkout" data-action="checkout"><i class="fas fa-sign-out-alt"></i> Check-out</button>
                                <?php endif; ?>
                                <?php if ($reserva['estado'] !== 'finalizada' && $reserva['estado'] !== 'cancelada'): ?>
                                    <button class="action-btn cancelar" data-action="cancelar"><i class="fas fa-times"></i> Cancelar</button>
                                <?php endif; ?>
                                <?php if (in_array($reserva['estado'], ['finalizada', 'cancelada']) && strtotime($reserva['fecha_salida']) < time()): ?>
                                    <button class="action-btn eliminar" data-action="eliminar"><i class="fas fa-trash-alt"></i> Eliminar</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        // Mostrar notificaciones
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 5000);
        }

        // Filtrado dinámico
        document.getElementById('estado-filtro').addEventListener('change', function() {
            const estado = this.value;
            const cards = document.querySelectorAll('.reservation-card');
            cards.forEach(card => {
                const cardEstado = card.querySelector('.estado').textContent;
                card.style.display = estado === '' || cardEstado === estado ? 'block' : 'none';
            });
        });

        // Agregar nueva reserva
        document.getElementById('add-reservation-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'crear'); // Añadimos el campo 'action' con valor 'crear'

            fetch('procesar_asignacion.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    const reservationsList = document.getElementById('reservations-list');
                    const newReservation = document.createElement('div');
                    newReservation.className = 'reservation-card';
                    newReservation.dataset.reservaId = data.reserva_id;

                    // Obtener el nombre del usuario desde el formulario
                    const usuarioId = formData.get('usuario_id');
                    const usuarioOption = document.querySelector(`#usuario_id option[value="${usuarioId}"]`);
                    const usuarioNombre = usuarioOption ? usuarioOption.textContent : 'Desconocido';

                    newReservation.innerHTML = `
                        <h4>Reserva #${data.reserva_id}</h4>
                        <p><strong>Usuario:</strong> ${usuarioNombre}</p>
                        <p><strong>Habitación:</strong> ${document.querySelector(`#habitacion_id option[value="${formData.get('habitacion_id')}"]`).textContent}</p>
                        <p><strong>Entrada:</strong> ${new Date(formData.get('fecha_entrada')).toLocaleDateString('es-ES')}</p>
                        <p><strong>Salida:</strong> ${new Date(formData.get('fecha_salida')).toLocaleDateString('es-ES')}</p>
                        <p><strong>Estado:</strong> <span class="estado">pendiente</span></p>
                        <div class="action-buttons">
                            <button class="action-btn asignar" data-action="asignar"><i class="fas fa-check"></i> Asignar</button>
                            <button class="action-btn checkin" data-action="checkin"><i class="fas fa-sign-in-alt"></i> Check-in</button>
                            <button class="action-btn cancelar" data-action="cancelar"><i class="fas fa-times"></i> Cancelar</button>
                        </div>
                    `;
                    if (reservationsList.children[0].tagName === 'P') {
                        reservationsList.innerHTML = '';
                    }
                    reservationsList.prepend(newReservation);
                    this.reset();
                    // Actualizar opciones de habitaciones
                    document.querySelector(`#habitacion_id option[value="${formData.get('habitacion_id')}"]`).remove();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(() => showNotification('Error al procesar la solicitud.', 'error'));
        });

        // Acciones sobre reservas
        document.getElementById('reservations-list').addEventListener('click', function(e) {
            const btn = e.target.closest('.action-btn');
            if (!btn) return;
            
            const reservaId = btn.closest('.reservation-card').dataset.reservaId;
            const action = btn.dataset.action;
            const formData = new FormData();
            formData.append('reserva_id', reservaId);
            formData.append('action', action);

            fetch('procesar_asignacion.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    const card = btn.closest('.reservation-card');
                    if (action === 'eliminar') {
                        card.remove();
                        if (!document.querySelector('.reservation-card')) {
                            document.getElementById('reservations-list').innerHTML = '<p>No hay reservas.</p>';
                        }
                    } else {
                        const estadoSpan = card.querySelector('.estado');
                        estadoSpan.textContent = data.estado;
                        const buttonsDiv = card.querySelector('.action-buttons');
                        buttonsDiv.innerHTML = '';
                        if (data.estado === 'pendiente') {
                            buttonsDiv.innerHTML = `
                                <button class="action-btn asignar" data-action="asignar"><i class="fas fa-check"></i> Asignar</button>
                                <button class="action-btn checkin" data-action="checkin"><i class="fas fa-sign-in-alt"></i> Check-in</button>
                                <button class="action-btn cancelar" data-action="cancelar"><i class="fas fa-times"></i> Cancelar</button>
                            `;
                        } else if (data.estado === 'checkin') {
                            buttonsDiv.innerHTML = `
                                <button class="action-btn checkout" data-action="checkout"><i class="fas fa-sign-out-alt"></i> Check-out</button>
                                <button class="action-btn cancelar" data-action="cancelar"><i class="fas fa-times"></i> Cancelar</button>
                            `;
                        } else if (data.estado === 'finalizada' || data.estado === 'cancelada') {
                            const fechaSalida = new Date(card.querySelector('p:nth-child(5)').textContent.split(': ')[1].split('/').reverse().join('-'));
                            if (fechaSalida < new Date()) {
                                buttonsDiv.innerHTML = `
                                    <button class="action-btn eliminar" data-action="eliminar"><i class="fas fa-trash-alt"></i> Eliminar</button>
                                `;
                            }
                        }
                    }
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(() => showNotification('Error al procesar la solicitud.', 'error'));
        });
    </script>
</body>
</html>