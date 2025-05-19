<?php
class Conexion {
    private $host = 'localhost';
    private $usuario = 'root';
    private $contrasena = '';
    private $base_datos = 'hotel_paraiso';
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->base_datos);
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
        $this->conexion->set_charset("utf8");
    }

    public function getConexion() {
        return $this->conexion;
    }

    public function consultaActualizar($sql) {
        $result = $this->conexion->query($sql);
        if (!$result) {
            error_log("Error en consultaActualizar: " . $this->conexion->error);
        }
        return $result;
    }

    public function consultaTabla($sql) {
        $result = $this->conexion->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function consultaFila($sql) {
        $result = $this->conexion->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    public function getLastInsertId() {
        return $this->conexion->insert_id;
    }

    public function cerrar() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}