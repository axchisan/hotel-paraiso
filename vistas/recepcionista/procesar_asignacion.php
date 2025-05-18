<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'recepcionista') {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../modelos/Recepcionista.php';

$recepcionista = new Recepcionista();
$action = $_POST['action'];
$reserva_id = $_POST['reserva_id'];

if ($action === 'asignar' || $action === 'checkin') {
    $checkin = ($action === 'checkin');
    if ($recepcionista->asignarHabitacion($reserva_id, $checkin)) {
        header('Location: index.php?success=' . ($checkin ? 'Check-in realizado' : 'Habitación asignada'));
    } else {
        header('Location: index.php?error=Error al procesar la acción');
    }
} elseif ($action === 'checkout') {
    if ($recepcionista->checkout($reserva_id)) {
        header('Location: index.php?success=Check-out realizado');
    } else {
        header('Location: index.php?error=Error al realizar el check-out');
    }
} elseif ($action === 'cancelar') {
    if ($recepcionista->cancelarReserva($reserva_id)) {
        header('Location: index.php?success=Reserva cancelada');
    } else {
        header('Location: index.php?error=Error al cancelar la reserva');
    }
}
?>