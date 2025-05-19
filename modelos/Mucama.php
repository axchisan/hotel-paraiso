<?php
require_once '../../config/database.php';

class Mucama {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function getTareasPendientes() {
        $sql = "SELECT t.*, h.numero, h.tipo 
                FROM tareas_limpieza t 
                JOIN habitaciones h ON t.habitacion_id = h.id 
                WHERE t.estado = 'pendiente'";
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function marcarCompletada($tarea_id) {
        $sql = "UPDATE tareas_limpieza SET estado = 'completada', fecha_completada = NOW() WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $tarea_id);
        return $stmt->execute();
    }

    public function getTareasCompletadas() {
        $sql = "SELECT t.*, h.numero, h.tipo 
                FROM tareas_limpieza t 
                JOIN habitaciones h ON t.habitacion_id = h.id 
                WHERE t.estado = 'completada' 
                ORDER BY t.fecha_completada DESC LIMIT 10";
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function asignarTarea($habitacion_id, $descripcion = 'Limpieza general') {
        $sql = "INSERT INTO tareas_limpieza (habitacion_id, descripcion, estado, fecha_asignada) VALUES (?, ?, 'pendiente', NOW())";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('is', $habitacion_id, $descripcion);
        return $stmt->execute();
    }
}
?>