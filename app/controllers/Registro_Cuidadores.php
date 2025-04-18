<?php
require RUTA_APP . "/librerias/FuncionesFormulario.php";
class Registro_Cuidadores extends Controlador
{
    private $registroCuidadoresModelo;
    public function __construct()
    {
        session_start();
        if (isset($_SESSION['usuario']) && $_SESSION['grupo'] == 'cuidador') {
            redireccionar('/home');
        }
        $this->registroCuidadoresModelo = $this->modelo('Registro_Cuidadores_Model');
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
                if (!$this->registroCuidadoresModelo->comprobarEmailBBDD($_POST['email'])) {
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

                if ($this->registroCuidadoresModelo->agregarDueno($datos)) {
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
                $this->vista('/registro_cuidadores/inicio', $datos);
            }
        } else {
            $datos = [
                'nombre' => '',
                'email' => '',
                'contrasena' => '',
            ];
            $this->vista('/registro_cuidadores/inicio', $datos);
        }
    }
}
