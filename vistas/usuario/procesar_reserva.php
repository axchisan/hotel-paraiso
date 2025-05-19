<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'usuario') {
    header('Location: ../../auth/login.php');
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
        header('Location: index.php?error=ID de habitación inválido');
        exit();
    }

    if (strtotime($fecha_salida) <= strtotime($fecha_entrada)) {
        header('Location: index.php?error=La fecha de salida debe ser posterior a la de entrada');
        exit();
    }

    $fecha_actual = date('Y-m-d');
    if (strtotime($fecha_entrada) < strtotime($fecha_actual)) {
        header('Location: index.php?error=La fecha de entrada no puede ser anterior a hoy');
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
        header('Location: index.php?error=La habitación seleccionada no está disponible');
        exit();
    }

    if ($habitacion->reservar($habitacion_id, $usuario_id, $fecha_entrada, $fecha_salida)) {
        header('Location: index.php?success=Reserva realizada con éxito');
    } else {
        header('Location: index.php?error=Error al realizar la reserva. La habitación puede estar reservada por otro usuario.');
    }
}
?>