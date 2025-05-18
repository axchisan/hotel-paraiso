<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../modelos/Administrador.php';

$admin = new Administrador();
$habitacion = $admin->getHabitaciones();
$habitacion = array_filter($habitacion, fn($h) => $h['id'] == $_GET['id'])[array_key_first(array_filter($habitacion, fn($h) => $h['id'] == $_GET['id']))];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $numero = $_POST['numero'];
    $tipo = $_POST['tipo'];
    $precio = $_POST['precio'];
    $capacidad = $_POST['capacidad'];
    if ($admin->editarHabitacion($id, $numero, $tipo, $precio, $capacidad)) {
        header('Location: index.php?success=Habitación actualizada');
    } else {
        header('Location: index.php?error=Error al actualizar la habitación');
    }
}
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Habitación - Hotel Paraíso</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Editar Habitación</h1>
        <nav>
            <a href="index.php">Volver</a>
        </nav>
    </header>
    <main>
        <h2>Editar Habitación #<?php echo $habitacion['numero']; ?></h2>
        <form action="editar_habitacion.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $habitacion['id']; ?>">
            <div class="form-group">
                <label for="numero">Número:</label>
                <input type="text" name="numero" value="<?php echo $habitacion['numero']; ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <input type="text" name="tipo" value="<?php echo $habitacion['tipo']; ?>" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio ($/noche):</label>
                <input type="number" name="precio" step="0.01" value="<?php echo $habitacion['precio']; ?>" required>
            </div>
            <div class="form-group">
                <label for="capacidad">Capacidad:</label>
                <input type="number" name="capacidad" value="<?php echo $habitacion['capacidad']; ?>" required>
            </div>
            <button type="submit">Actualizar</button>
        </form>
    </main>
</body>
</html>