<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'usuario') {
    echo json_encode(['success' => false, 'message' => 'No tienes permisos para realizar esta acción']);
    exit();
}
require_once '../../modelos/Habitacion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $habitacion = new Habitacion();
    $usuario_id = $_SESSION['usuario_id'];
    $habitacion_id = $_POST['habitacion_id'];
    $fecha_entrada = $_POST['fecha_entrada'];
    $fecha_salida = $_POST['fecha_salida'];

    // Validaciones adicionales
    if (empty($habitacion_id) || !is_numeric($habitacion_id)) {
        echo json_encode(['success' => false, 'message' => 'ID de habitación inválido']);
        exit();
    }

    if (strtotime($fecha_salida) <= strtotime($fecha_entrada)) {
        echo json_encode(['success' => false, 'message' => 'La fecha de salida debe ser posterior a la de entrada']);
        exit();
    }

    $fecha_actual = date('Y-m-d');
    if (strtotime($fecha_entrada) < strtotime($fecha_actual)) {
        echo json_encode(['success' => false, 'message' => 'La fecha de entrada no puede ser anterior a hoy']);
        exit();
    }

    // Verificar disponibilidad
    $habitaciones_disponibles = $habitacion->getHabitacionesDisponibles();
    $disponible = false;
    foreach ($habitaciones_disponibles as $hab) {
        if ($hab['id'] == $habitacion_id) {
            $disponible = true;
            break;
        }
    }
    if (!$disponible) {
        echo json_encode(['success' => false, 'message' => 'La habitación seleccionada no está disponible']);
        exit();
    }

    $reserva_id = $habitacion->reservar($habitacion_id, $usuario_id, $fecha_entrada, $fecha_salida);
    if ($reserva_id !== false) {
        echo json_encode(['success' => true, 'message' => 'Reserva realizada con éxito', 'reserva_id' => $reserva_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al realizar la reserva. La habitación puede estar reservada por otro usuario.']);
    }
}
?>