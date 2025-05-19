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
            <div class="carousel-container">
                <?php if (empty($habitaciones)): ?>
                    <p>No hay habitaciones disponibles en este momento.</p>
                <?php else: ?>
                    <div class="carousel">
                        <?php foreach ($habitaciones as $hab): ?>
                            <div class="carousel-item" data-id="<?php echo $hab['id']; ?>">
                                <img src="../../recursos/imagenes/room<?php echo $hab['id']; ?>.jpg" alt="<?php echo $hab['tipo']; ?>">
                                <div class="room-info">
                                    <h4><?php echo $hab['numero'] . ' - ' . $hab['tipo']; ?></h4>
                                    <p>Precio: $<?php echo $hab['precio']; ?>/noche</p>
                                    <p>Capacidad: <?php echo $hab['capacidad']; ?> personas</p>
                                    <button class="reserve-btn" data-habitacion-id="<?php echo $hab['id']; ?>" 
                                            data-numero="<?php echo $hab['numero']; ?>" 
                                            data-tipo="<?php echo $hab['tipo']; ?>" 
                                            data-precio="<?php echo $hab['precio']; ?>" 
                                            data-capacidad="<?php echo $hab['capacidad']; ?>">Seleccionar</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-btn prev" onclick="moveCarousel(-1)">❮</button>
                    <button class="carousel-btn next" onclick="moveCarousel(1)">❯</button>
                <?php endif; ?>
            </div>
        </section>

        <section class="reservation-section">
            <h3>Hacer una Reserva</h3>
            <div id="selected-room" class="selected-room" style="display: none;">
                <h4>Habitación Seleccionada</h4>
                <p><strong>Número:</strong> <span id="selected-numero"></span></p>
                <p><strong>Tipo:</strong> <span id="selected-tipo"></span></p>
                <p><strong>Precio:</strong> $<span id="selected-precio"></span>/noche</p>
                <p><strong>Capacidad:</strong> <span id="selected-capacidad"></span> personas</p>
                <button id="deselect-room" class="deselect-btn">Deseleccionar</button>
            </div>
            <form id="reservation-form" method="POST">
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
        let currentIndex = 0;
        const items = document.querySelectorAll('.carousel-item');
        const totalItems = items.length;
        let selectedRoom = null;

        function moveCarousel(direction) {
            currentIndex = (currentIndex + direction + totalItems) % totalItems;
            updateCarousel();
        }

        function updateCarousel() {
            const offset = -currentIndex * 100 / 3; // Ajuste para 3 items visibles
            document.querySelector('.carousel').style.transform = `translateX(${offset}%)`;
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 5000);
        }

        document.querySelectorAll('.reserve-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Desmarcar habitación previamente seleccionada
                document.querySelectorAll('.carousel-item').forEach(item => item.classList.remove('selected'));
                document.querySelectorAll('.reserve-btn').forEach(b => b.textContent = 'Seleccionar');

                // Marcar la nueva selección
                const habitacionId = btn.getAttribute('data-habitacion-id');
                const numero = btn.getAttribute('data-numero');
                const tipo = btn.getAttribute('data-tipo');
                const precio = btn.getAttribute('data-precio');
                const capacidad = btn.getAttribute('data-capacidad');

                btn.textContent = 'Seleccionada';
                btn.parentElement.parentElement.classList.add('selected');
                selectedRoom = { id: habitacionId, numero, tipo, precio, capacidad };

                // Mostrar detalles de la habitación seleccionada
                document.getElementById('selected-habitacion').value = habitacionId;
                document.getElementById('selected-numero').textContent = numero;
                document.getElementById('selected-tipo').textContent = tipo;
                document.getElementById('selected-precio').textContent = precio;
                document.getElementById('selected-capacidad').textContent = capacidad;
                document.getElementById('selected-room').style.display = 'block';
            });
        });

        document.getElementById('deselect-room').addEventListener('click', () => {
            document.querySelectorAll('.carousel-item').forEach(item => item.classList.remove('selected'));
            document.querySelectorAll('.reserve-btn').forEach(b => b.textContent = 'Seleccionar');
            document.getElementById('selected-habitacion').value = '';
            document.getElementById('selected-room').style.display = 'none';
            selectedRoom = null;
        });

        document.getElementById('reservation-form').addEventListener('submit', function (e) {
            e.preventDefault();

            if (!selectedRoom) {
                showNotification('Por favor selecciona una habitación antes de confirmar.', 'error');
                return;
            }

            const formData = new FormData(this);
            fetch('procesar_reserva.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Actualizar las reservas dinámicamente
                    const reservationsSection = document.querySelector('.reservations-section .reservations-list');
                    const newReservation = document.createElement('div');
                    newReservation.className = 'reservation-card';
                    const dias = (new Date(formData.get('fecha_salida')) - new Date(formData.get('fecha_entrada'))) / (1000 * 60 * 60 * 24);
                    newReservation.innerHTML = `
                        <h4>Reserva #${data.reserva_id}</h4>
                        <p>Habitación: ${selectedRoom.numero} - ${selectedRoom.tipo}</p>
                        <p>Entrada: ${new Date(formData.get('fecha_entrada')).toLocaleDateString('es-ES')}</p>
                        <p>Salida: ${new Date(formData.get('fecha_salida')).toLocaleDateString('es-ES')}</p>
                        <p>Total Estimado: $${(selectedRoom.precio * dias).toFixed(2)}</p>
                        <p>Estado: pendiente</p>
                    `;
                    if (reservationsSection) {
                        reservationsSection.prepend(newReservation);
                    } else {
                        const newList = document.createElement('div');
                        newList.className = 'reservations-list';
                        newList.appendChild(newReservation);
                        document.querySelector('.reservations-section').appendChild(newList);
                        document.querySelector('.reservations-section p').remove();
                    }

                    // Actualizar carrusel: remover la habitación reservada
                    const selectedItem = document.querySelector(`.carousel-item[data-id="${selectedRoom.id}"]`);
                    if (selectedItem) selectedItem.remove();
                    const remainingItems = document.querySelectorAll('.carousel-item').length;
                    if (remainingItems === 0) {
                        document.querySelector('.carousel-container').innerHTML = '<p>No hay habitaciones disponibles en este momento.</p>';
                    }

                    // Limpiar selección
                    document.getElementById('selected-room').style.display = 'none';
                    document.getElementById('selected-habitacion').value = '';
                    selectedRoom = null;
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error al procesar la solicitud. Inténtalo de nuevo.', 'error');
            });
        });

        // Inicializar carrusel
        updateCarousel();
        setInterval(() => moveCarousel(1), 5000); // Auto-scroll cada 5 segundos
    </script>
</body>
</html>