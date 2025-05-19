<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'mucama') {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../modelos/Mucama.php';

$mucama = new Mucama();

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'asignar') {
        $habitacion_id = $_POST['habitacion_id'] ?? null;
        $descripcion = $_POST['descripcion'] ?? 'Limpieza general';
        $prioridad = $_POST['prioridad'] ?? 1;

        if ($habitacion_id && $mucama->asignarTarea($habitacion_id, $descripcion, $prioridad)) {
            header('Location: index.php?success=Tarea asignada con éxito');
        } else {
            header('Location: index.php?error=Error al asignar la tarea');
        }
    } elseif ($_POST['action'] === 'completar') {
        $tarea_id = $_POST['tarea_id'] ?? null;
        if ($tarea_id && $mucama->marcarCompletada($tarea_id)) {
            header('Location: index.php?success=Tarea marcada como completada');
        } else {
            header('Location: index.php?error=Error al marcar la tarea');
        }
    } else {
        header('Location: index.php?error=Acción no válida');
    }
} else {
    header('Location: index.php?error=Acción no especificada');
}
exit();
?>