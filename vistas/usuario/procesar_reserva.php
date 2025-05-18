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

    if (strtotime($fecha_salida) <= strtotime($fecha_entrada)) {
        header('Location: index.php?error=La fecha de salida debe ser posterior a la de entrada');
        exit();
    }

    if ($habitacion->reservar($habitacion_id, $usuario_id, $fecha_entrada, $fecha_salida)) {
        header('Location: index.php?success=Reserva realizada con éxito');
    } else {
        header('Location: index.php?error=Error al realizar la reserva');
    }
}
?>