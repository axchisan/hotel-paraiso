<?php
session_start();
require_once '../modelos/Usuario.php';

class AuthController {
    private $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            $rol_id = $_POST['rol_id'] ?? '';

            if (empty($username) || empty($contrasena) || empty($rol_id)) {
                header('Location: ../vistas/auth/login.php?error=Todos los campos son obligatorios');
                exit();
            }

            $resultado = $this->usuario->validar($username, $contrasena, $rol_id);

            if ($resultado['success']) {
                $_SESSION['usuario_id'] = $resultado['usuario']['id'];
                $_SESSION['rol'] = $this->usuario->getRol($resultado['usuario']['rol_id']);
                switch ($_SESSION['rol']) {
                    case 'administrador':
                        header('Location: ../vistas/admin/index.php');
                        break;
                    case 'recepcionista':
                        header('Location: ../vistas/recepcionista/index.php');
                        break;
                    case 'usuario':
                        header('Location: ../vistas/publico/index.php');
                        break;
                    case 'mucama':
                        header('Location: ../vistas/mucama/index.php');
                        break;
                    default:
                        header('Location: ../vistas/auth/login.php?error=Rol no válido');
                }
            } else {
                header('Location: ../vistas/auth/login.php?error=' . urlencode($resultado['error']));
            }
        }
    }

    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $rol_id = $_POST['rol_id'] ?? '';

            if (empty($username) || empty($contrasena) || empty($nombre) || empty($email) || empty($rol_id)) {
                header('Location: ../vistas/auth/registro.php?error=Todos los campos son obligatorios');
                exit();
            }

            $resultado = $this->usuario->registrar($username, $contrasena, $nombre, $email, $rol_id);

            if ($resultado['success']) {
                header('Location: ../vistas/auth/login.php?success=Registro exitoso');
            } else {
                header('Location: ../vistas/auth/registro.php?error=' . urlencode($resultado['error']));
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ../vistas/auth/login.php');
    }
}

$controller = new AuthController();
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            $controller->login();
            break;
        case 'registro':
            $controller->registro();
            break;
        case 'logout':
            $controller->logout();
            break;
    }
}
?>