<?php
require RUTA_APP . "/librerias/Funciones.php";

// Controlador para la autenticación de usuarios
class Autenticacion extends Controlador
{
    private $autenticacionModelo;

    // Constructor: inicia sesión y redirige si ya hay usuario autenticado
    public function __construct()
    {
        session_start();
        // Si el usuario es cuidador, redirige a /home
        if (isset($_SESSION['usuario']) && $_SESSION['grupo'] == 'cuidador') {
            redireccionar('/home');
        }
        // Si el usuario es dueño, redirige a /home
        if (isset($_SESSION['usuario']) && $_SESSION['grupo'] == 'dueno') {
            redireccionar('/home');
        }
        // Acceso al modelo de autenticación
        $this->autenticacionModelo = $this->modelo('Autenticacion_Model');
    }

    // Método principal para mostrar el formulario e iniciar sesión
    public function index()
    {
        $emailErr = $contrasenaErr = '';

        // Si se envía el formulario por POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validar email
            $email = comprobarDatos($_POST['email']) ? test_input($_POST['email']) : $emailErr = "Completa el campo Email \n";
            // Validar contraseña
            $contrasena = comprobarContrasena($_POST['contrasena']) ? test_input($_POST['contrasena']) : $contrasenaErr = "Completa el campo Contraseña\n";

            // Si no hay errores de validación
            if (formularioErrores($emailErr, $contrasenaErr)) {
                // Buscar usuario en la tabla de dueños
                $usuario = $this->autenticacionModelo->obtenerDuenoPorEmail($email);
                $grupo = 'dueno';

                // Si no se encuentra, buscar en la tabla de cuidadores
                if (!$usuario) {
                    $usuario = $this->autenticacionModelo->obtenerCuidadorPorEmail($email);
                    $grupo = 'cuidador';
                }

                // Si no existe el usuario en ninguna tabla
                if (!$usuario) {
                    $_SESSION['error'] = "Usuario o contraseña incorrectos";
                    $this->vista('autenticacion/inicio');
                    return;
                }

                // Verificar la contraseña
                if (password_verify($contrasena, $usuario->contrasena)) {
                    // Guardar datos en la sesión
                    $_SESSION['usuario_id'] = $usuario->id;
                    $_SESSION['usuario'] = $usuario->nombre;
                    $_SESSION['grupo'] = $grupo;
                    $_SESSION['imagen_usuario'] = $usuario->imagen;

                    // Redirigir al home
                    redireccionar('/home');
                } else {
                    // Contraseña incorrecta
                    $_SESSION['error'] = "Usuario o contraseña incorrectos";
                    $this->vista('autenticacion/inicio');
                }
            } else {
                // Errores de validación
                $_SESSION['error'] = "Usuario o contraseña incorrectos";
                $this->vista('autenticacion/inicio');
            }
        } else {
            // Si no es POST, mostrar la vista de inicio de sesión
            $this->vista('autenticacion/inicio');
        }
    }

    // Método para cerrar sesión
    public function logout()
    {
        session_unset();
        session_destroy();
        redireccionar('/home');
    }
}
