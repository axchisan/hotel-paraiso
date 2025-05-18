<?php
class Conexion {
    private $host = '127.0.0.1';
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
        return $this->conexion->query($sql);
    }

    public function consultaTabla($sql) {
        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function consultaFila($sql) {
        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_assoc();
    }

    public function cerrar() {
        $this->conexion->close();
    }
}
?>