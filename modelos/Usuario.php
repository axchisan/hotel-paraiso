<?php
require_once '../config/database.php';

class Usuario {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function usuarioExiste($username) {
        $sql = "SELECT COUNT(*) as count FROM usuarios WHERE username = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado['count'] > 0;
    }

    public function emailExiste($email) {
        $sql = "SELECT COUNT(*) as count FROM usuarios WHERE email = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado['count'] > 0;
    }

    public function registrar($username, $contrasena, $nombre, $email, $rol_id) {
        if ($this->usuarioExiste($username)) {
            return ['success' => false, 'error' => 'El nombre de usuario ya está en uso'];
        }
        if ($this->emailExiste($email)) {
            return ['success' => false, 'error' => 'El email ya está registrado'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'El email no es válido'];
        }
        if (strlen($contrasena) < 6) {
            return ['success' => false, 'error' => 'La contraseña debe tener al menos 6 caracteres'];
        }

        $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (username, contrasena, nombre, email, rol_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ssssi', $username, $contrasena_hash, $nombre, $email, $rol_id);
        $success = $stmt->execute();
        if ($success) {
            return ['success' => true];
        } else {
            return ['success' => false, 'error' => 'Error al registrar el usuario'];
        }
    }

    public function validar($username, $contrasena, $rol_id) {
        $sql = "SELECT * FROM usuarios WHERE username = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if (!$resultado) {
            return ['success' => false, 'error' => 'Usuario no encontrado'];
        }
        if (!password_verify($contrasena, $resultado['contrasena'])) {
            return ['success' => false, 'error' => 'Contraseña incorrecta'];
        }
        if ($resultado['rol_id'] != $rol_id) {
            return ['success' => false, 'error' => 'Rol seleccionado no coincide con el usuario'];
        }
        return ['success' => true, 'usuario' => $resultado];
    }

    public function getRol($rol_id) {
        $sql = "SELECT nombre FROM roles WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $rol_id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado ? $resultado['nombre'] : null;
    }
}
?>