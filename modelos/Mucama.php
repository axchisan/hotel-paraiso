<?php
require_once '../../config/database.php';

class Mucama {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function getTareasPendientes() {
        $sql = "SELECT t.*, h.numero, h.tipo 
                FROM tareas_limpieza t 
                JOIN habitaciones h ON t.habitacion_id = h.id 
                WHERE t.estado = 'pendiente'";
        $result = $this->conexion->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function marcarCompletada($tarea_id) {
        $sql = "UPDATE tareas_limpieza SET estado = 'completada', fecha_completada = NOW() WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar marcarCompletada: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param('i', $tarea_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getTareasCompletadas() {
        $sql = "SELECT t.*, h.numero, h.tipo 
                FROM tareas_limpieza t 
                JOIN habitaciones h ON t.habitacion_id = h.id 
                WHERE t.estado = 'completada' 
                ORDER BY t.fecha_completada DESC LIMIT 10";
        $result = $this->conexion->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function asignarTarea($habitacion_id, $descripcion = 'Limpieza general', $prioridad = 1) {
        $sql = "INSERT INTO tareas_limpieza (habitacion_id, tipo_tarea, descripcion, estado, fecha_asignada, prioridad) 
                VALUES (?, 'limpieza_general', ?, 'pendiente', NOW(), ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar asignarTarea: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param('isi', $habitacion_id, $descripcion, $prioridad);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getHabitaciones() {
        $sql = "SELECT id, numero, tipo FROM habitaciones";
        $result = $this->conexion->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}