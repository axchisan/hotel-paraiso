<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'mucama') {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../modelos/Mucama.php';

$mucama = new Mucama();
$tarea_id = $_POST['tarea_id'];

if ($mucama->marcarCompletada($tarea_id)) {
    header('Location: index.php?success=Tarea marcada como completada');
} else {
    header('Location: index.php?error=Error al marcar la tarea');
}
?>