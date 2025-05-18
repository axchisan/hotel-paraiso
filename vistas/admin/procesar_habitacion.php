<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../modelos/Administrador.php';

$admin = new Administrador();
$action = $_POST['action'];

if ($action === 'agregar') {
    $numero = $_POST['numero'];
    $tipo = $_POST['tipo'];
    $precio = $_POST['precio'];
    $capacidad = $_POST['capacidad'];
    if ($admin->agregarHabitacion($numero, $tipo, $precio, $capacidad)) {
        header('Location: index.php?success=Habitación agregada');
    } else {
        header('Location: index.php?error=Error al agregar la habitación');
    }
} elseif ($action === 'eliminar') {
    $id = $_POST['id'];
    if ($admin->eliminarHabitacion($id)) {
        header('Location: index.php?success=Habitación eliminada');
    } else {
        header('Location: index.php?error=Error al eliminar la habitación');
    }
}
?>