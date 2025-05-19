<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'recepcionista') {
    echo json_encode(['success' => false, 'message' => 'No tienes permisos para realizar esta acción']);
    exit();
}
require_once '../../modelos/Recepcionista.php';

$recepcionista = new Recepcionista();
$action = $_POST['action'] ?? '';

if ($action === 'crear') {
    $usuario_id = $_POST['usuario_id'];
    $habitacion_id = $_POST['habitacion_id'];
    $fecha_entrada = $_POST['fecha_entrada'];
    $fecha_salida = $_POST['fecha_salida'];

    if (strtotime($fecha_salida) <= strtotime($fecha_entrada)) {
        echo json_encode(['success' => false, 'message' => 'La fecha de salida debe ser posterior a la de entrada']);
        exit();
    }

    $fecha_actual = date('Y-m-d');
    if (strtotime($fecha_entrada) < strtotime($fecha_actual)) {
        echo json_encode(['success' => false, 'message' => 'La fecha de entrada no puede ser anterior a hoy']);
        exit();
    }

    $reserva_id = $recepcionista->crearReserva($usuario_id, $habitacion_id, $fecha_entrada, $fecha_salida);
    if ($reserva_id !== false) {
        echo json_encode(['success' => true, 'message' => 'Reserva creada con éxito', 'reserva_id' => $reserva_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear la reserva']);
    }
} elseif ($action === 'asignar' || $action === 'checkin') {
    $reserva_id = $_POST['reserva_id'];
    $checkin = ($action === 'checkin');
    if ($recepcionista->asignarHabitacion($reserva_id, $checkin)) {
        echo json_encode(['success' => true, 'message' => $checkin ? 'Check-in realizado' : 'Habitación asignada', 'estado' => $checkin ? 'checkin' : 'asignada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al procesar la acción']);
    }
} elseif ($action === 'checkout') {
    $reserva_id = $_POST['reserva_id'];
    if ($recepcionista->checkout($reserva_id)) {
        echo json_encode(['success' => true, 'message' => 'Check-out realizado', 'estado' => 'finalizada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al realizar el check-out']);
    }
} elseif ($action === 'cancelar') {
    $reserva_id = $_POST['reserva_id'];
    if ($recepcionista->cancelarReserva($reserva_id)) {
        echo json_encode(['success' => true, 'message' => 'Reserva cancelada', 'estado' => 'cancelada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al cancelar la reserva']);
    }
} elseif ($action === 'eliminar') {
    $reserva_id = $_POST['reserva_id'];
    if ($recepcionista->eliminarReserva($reserva_id)) {
        echo json_encode(['success' => true, 'message' => 'Reserva eliminada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la reserva. Asegúrese de que esté finalizada o cancelada y su fecha de salida haya pasado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
?>