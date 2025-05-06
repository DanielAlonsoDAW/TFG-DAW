<?php
require RUTA_APP . "/librerias/FuncionesFormulario.php";
class Registro_Duenos extends Controlador
{
    private $registroModelo;
    public function __construct()
    {
        session_start();
        if (isset($_SESSION['usuario'])) {
            redireccionar('/home');
        }
        $this->registroModelo = $this->modelo('Registro_Model');
    }

    public function index()
    {
        $nombre = $email = $contrasena = '';
        $nombreErr = $emailErr = $contraseñaErr = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar nombre
            if (comprobarDatos(trim($_POST['nombre']))) {
                $nombre = test_input($_POST['nombre']);
            } else {
                $nombreErr = "Completa el campo Nombre\n";
            }

            // Validar email
            if (comprobarEmail(trim($_POST['email']))) {
                if (!$this->registroModelo->comprobarEmailBBDD($_POST['email'], 'patitas_duenos')) {
                    $email = test_input($_POST['email']);
                } else {
                    $emailErr = "El correo electrónico indicado ya está registrado\n";
                }
            } else {
                $emailErr = "Indica un correo electrónico válido\n";
            }

            // Validar contrasena
            if (comprobarDatos(trim($_POST['contrasena']))) {
                $contrasena = password_hash(test_input($_POST['contrasena']), PASSWORD_DEFAULT);
            } else {
                $contraseñaErr = "Completa el campo Contrasena\n";
            }

            // Verificar si hay errores en el formulario
            if (formularioErrores($nombreErr, $emailErr, $contraseñaErr)) {
                $datos = [
                    'nombre' => $nombre,
                    'email' => $email,
                    'contrasena' => $contrasena,
                ];

                if ($this->registroModelo->agregarDueno($datos, 'patitas_duenos')) {
                    redireccionar('/home');
                } else {
                    die("No se pudo realizar el alta");
                }
            } else {
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
            $datos = [
                'nombre' => '',
                'email' => '',
                'contrasena' => '',
            ];
            $this->vista('/registro_duenos/inicio', $datos);
        }
    }
}
