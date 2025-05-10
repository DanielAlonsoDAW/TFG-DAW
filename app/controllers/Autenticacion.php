<?php
require RUTA_APP . "/librerias/FuncionesFormulario.php";
class Autenticacion extends Controlador
{
    private $autenticacionModelo;

    public function __construct()
    {
        session_start();
        if (isset($_SESSION['usuario']) && $_SESSION['grupo'] == 'cuidador') {
            redireccionar('/home');
        }
        if (isset($_SESSION['usuario']) && $_SESSION['grupo'] == 'dueno') {
            redireccionar('/home');
        }
        //1) Acceso al modelo
        $this->autenticacionModelo = $this->modelo('Autenticacion_Model');
    }

    public function index()
    {
        $emailErr = $contrasenaErr = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = comprobarDatos($_POST['email']) ? test_input($_POST['email']) : $emailErr = "Completa el campo Email \n";
            $contrasena = comprobarDatos($_POST['contrasena']) ? test_input($_POST['contrasena']) : $contrasenaErr = "Completa el campo Contraseña\n";

            if (formularioErrores($emailErr, $contrasenaErr)) {
                // Buscar primero en dueños
                $usuario = $this->autenticacionModelo->obtenerDuenoPorEmail($email);
                $grupo = 'dueno';

                // Si no se encuentra, buscar en cuidadores
                if (!$usuario) {
                    $usuario = $this->autenticacionModelo->obtenerCuidadorPorEmail($email);
                    $grupo = 'cuidador';
                }

                // Si no existe en ninguna tabla
                if (!$usuario) {
                    $_SESSION['error'] = "Usuario o contraseña incorrectos";
                    $this->vista('autenticacion/inicio');
                    return;
                }

                // Verificar contraseña
                if (password_verify($contrasena, $usuario->contrasena)) {
                    $_SESSION['usuario_id'] = $usuario->id;
                    $_SESSION['usuario'] = $usuario->nombre;
                    $_SESSION['grupo'] = $grupo;
                    $_SESSION['imagen_usuario'] = $usuario->imagen;

                    redireccionar('/home');
                } else {
                    $_SESSION['error'] = "Usuario o contraseña incorrectos";
                    $this->vista('autenticacion/inicio');
                }
            } else {
                $_SESSION['error'] = "Usuario o contraseña incorrectos";
                $this->vista('autenticacion/inicio');
            }
        } else {
            $this->vista('autenticacion/inicio');
        }
    }


    public function logout()
    {
        session_unset();
        session_destroy();
        redireccionar('/home');
    }
}
