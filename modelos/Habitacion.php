<?php
require_once '../../config/database.php';

class Habitacion {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function getHabitacionesDisponibles() {
        $sql = "SELECT * FROM habitaciones WHERE estado = 'disponible'";
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getMisReservas($usuario_id) {
        $sql = "SELECT r.*, h.numero, h.tipo, h.precio 
                FROM reservas r 
                JOIN habitaciones h ON r.habitacion_id = h.id 
                WHERE r.usuario_id = ? AND r.estado = 'pendiente'";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function reservar($habitacion_id, $usuario_id, $fecha_entrada, $fecha_salida) {
        $sql = "INSERT INTO reservas (usuario_id, habitacion_id, fecha_entrada, fecha_salida) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('iiss', $usuario_id, $habitacion_id, $fecha_entrada, $fecha_salida);
        if ($stmt->execute()) {
            $db = new Conexion(); // Reinstanciar para acceder al insert_id
            $reserva_id = $db->getLastInsertId();
            $update_sql = "UPDATE habitaciones SET estado = 'ocupada' WHERE id = ?";
            $stmt_update = $this->conexion->prepare($update_sql);
            $stmt_update->bind_param('i', $habitacion_id);
            if ($stmt_update->execute()) {
                return $reserva_id; // Devolver el ID de la reserva
            }
        }
        return false;
    }
}
?>