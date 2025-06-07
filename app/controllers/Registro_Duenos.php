<?php
require RUTA_APP . "/librerias/Funciones.php";

// Controlador para el registro de dueños
class Registro_Duenos extends Controlador
{
    private $registroModelo;

    // Constructor: inicia sesión y redirige si ya hay usuario logueado
    public function __construct()
    {
        session_start();
        if (isset($_SESSION['usuario'])) {
            redireccionar('/home');
        }
        $this->registroModelo = $this->modelo('Registro_Model');
    }

    // Método principal para mostrar y procesar el formulario de registro
    public function index()
    {
        // Inicialización de variables
        $nombre = $email = $contrasena = '';
        $nombreErr = $emailErr = $contraseñaErr = '';

        // Si el formulario fue enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar nombre
            if (comprobarDatos(trim($_POST['nombre']))) {
                $nombre = test_input($_POST['nombre']);
            } else {
                $nombreErr = "Completa el campo Nombre\n";
            }

            // Validar email
            if (comprobarEmail(trim($_POST['email']))) {
                // Verificar si el email ya existe en la base de datos
                if (!$this->registroModelo->comprobarEmailBBDD($_POST['email'], 'patitas_duenos')) {
                    $email = test_input($_POST['email']);
                } else {
                    $emailErr = "El correo electrónico indicado ya está registrado\n";
                }
            } else {
                $emailErr = "Indica un correo electrónico válido\n";
            }

            // Validar contraseña
            if (comprobarContrasena(trim($_POST['contrasena']))) {
                $contrasena = password_hash(test_input($_POST['contrasena']), PASSWORD_DEFAULT);
            } else {
                $contraseñaErr = "Completa el campo Contrasena\n";
            }

            // Si no hay errores en el formulario
            if (formularioErrores($nombreErr, $emailErr, $contraseñaErr)) {
                $datos = [
                    'nombre' => $nombre,
                    'email' => $email,
                    'contrasena' => $contrasena,
                ];

                // Intentar agregar el usuario a la base de datos
                if ($this->registroModelo->agregarUsuario($datos, 'patitas_duenos')) {
                    redireccionar('/home');
                } else {
                    die("No se pudo realizar el alta");
                }
            } else {
                // Si hay errores, mostrar los datos y errores en la vista
                $datos = [
                    'correct' => [
                        'nombre' => $nombre,
                        'email' => $email,
                        'contrasena' => $contrasena,
                    ],
                    'errors' => [
                        'nombre' => $nombreErr,
                        'email' => $emailErr,
                        'contrasena' => $contraseñaErr,
                    ]
                ];
                $this->vista('/registro_duenos/inicio', $datos);
            }
        } else {
            // Si no se envió el formulario, mostrar la vista vacía
            $datos = [
                'nombre' => '',
                'email' => '',
                'contrasena' => '',
            ];
            $this->vista('/registro_duenos/inicio', $datos);
        }
    }
}
