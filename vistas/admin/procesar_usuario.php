<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../modelos/Administrador.php';

$admin = new Administrador();
$action = $_POST['action'];

if ($action === 'eliminar') {
    $id = $_POST['id'];
    if ($admin->eliminarUsuario($id)) {
        header('Location: index.php?success=Usuario eliminado');
    } else {
        header('Location: index.php?error=Error al eliminar el usuario');
    }
}
?>