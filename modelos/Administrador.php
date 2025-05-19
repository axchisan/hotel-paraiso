<?php
require_once '../../config/database.php';

class Administrador {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function getHabitaciones() {
        $sql = "SELECT * FROM habitaciones";
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarHabitacion($numero, $tipo, $precio, $capacidad) {
        $sql = "INSERT INTO habitaciones (numero, tipo, precio, capacidad, estado) VALUES (?, ?, ?, ?, 'disponible')";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ssdi', $numero, $tipo, $precio, $capacidad);
        return $stmt->execute();
    }

    public function editarHabitacion($id, $numero, $tipo, $precio, $capacidad) {
        $sql = "UPDATE habitaciones SET numero = ?, tipo = ?, precio = ?, capacidad = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ssddi', $numero, $tipo, $precio, $capacidad, $id);
        return $stmt->execute();
    }

    public function eliminarHabitacion($id) {
        $sql = "DELETE FROM habitaciones WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getUsuarios() {
        $sql = "SELECT u.*, r.nombre AS rol FROM usuarios u JOIN roles r ON u.rol_id = r.id";
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function eliminarUsuario($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getIngresosPorReservas() {
        $sql = "SELECT 
                    r.fecha_entrada, 
                    r.fecha_salida, 
                    h.precio, 
                    DATEDIFF(r.fecha_salida, r.fecha_entrada) AS dias,
                    (h.precio * DATEDIFF(r.fecha_salida, r.fecha_entrada)) AS total
                FROM reservas r 
                JOIN habitaciones h ON r.habitacion_id = h.id 
                WHERE r.estado = 'finalizada'";
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
}
?>