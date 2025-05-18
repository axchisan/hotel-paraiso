<?php
require_once '../../config/database.php';

class Recepcionista {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function getReservas() {
        $sql = "SELECT r.*, u.username, h.numero, h.tipo 
                FROM reservas r 
                JOIN usuarios u ON r.usuario_id = u.id 
                JOIN habitaciones h ON r.habitacion_id = h.id";
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function asignarHabitacion($reserva_id, $checkin = null) {
        $estado = $checkin ? 'checkin' : 'asignada';
        $sql = "UPDATE reservas SET estado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('si', $estado, $reserva_id);
        if ($stmt->execute() && $checkin) {
            $update_sql = "UPDATE habitaciones h 
                           JOIN reservas r ON h.id = r.habitacion_id 
                           SET h.estado = 'ocupada' 
                           WHERE r.id = ?";
            $stmt_update = $this->conexion->prepare($update_sql);
            return $stmt_update->bind_param('i', $reserva_id) && $stmt_update->execute();
        }
        return $stmt->execute();
    }

    public function checkout($reserva_id) {
        $sql = "UPDATE reservas SET estado = 'finalizada' WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $reserva_id);
        if ($stmt->execute()) {
            $update_sql = "UPDATE habitaciones h 
                           JOIN reservas r ON h.id = r.habitacion_id 
                           SET h.estado = 'disponible' 
                           WHERE r.id = ?";
            $stmt_update = $this->conexion->prepare($update_sql);
            return $stmt_update->bind_param('i', $reserva_id) && $stmt_update->execute();
        }
        return false;
    }

    public function cancelarReserva($reserva_id) {
        $sql = "UPDATE reservas SET estado = 'cancelada' WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $reserva_id);
        if ($stmt->execute()) {
            $update_sql = "UPDATE habitaciones h 
                           JOIN reservas r ON h.id = r.habitacion_id 
                           SET h.estado = 'disponible' 
                           WHERE r.id = ?";
            $stmt_update = $this->conexion->prepare($update_sql);
            return $stmt_update->bind_param('i', $reserva_id) && $stmt_update->execute();
        }
        return false;
    }
}
?>