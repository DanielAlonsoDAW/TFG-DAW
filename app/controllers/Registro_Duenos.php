<?php
require RUTA_APP . "/librerias/FuncionesFormulario.php";
class Registro_Duenos extends Controlador
{
    private $registroDuenosModelo;
    public function __construct()
    {
        $this->registroDuenosModelo = $this->modelo('Registro_Duenos_Model');
    }

    public function index()
    {
        $nombre = $email = $contraseña = '';
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
                if (!$this->registroDuenosModelo->comprobarEmailBBDD($_POST['email'])) {
                    $email = test_input($_POST['email']);
                } else {
                    $emailErr = "El correo electrónico indicado ya está registrado\n";
                }
            } else {
                $emailErr = "Indica un correo electrónico válido\n";
            }

            // Validar contraseña
            if (comprobarDatos(trim($_POST['contraseña']))) {
                $contraseña = password_hash(test_input($_POST['contraseña']), PASSWORD_DEFAULT);
            } else {
                $contraseñaErr = "Completa el campo Contraseña\n";
            }

            // Verificar si hay errores en el formulario
            if (formularioErrores($nombreErr, $emailErr, $contraseñaErr)) {
                $datos = [
                    'nombre' => $nombre,
                    'email' => $email,
                    'contraseña' => $contraseña,
                ];

                if ($this->registroDuenosModelo->agregarDueno($datos)) {
                    redireccionar('/home');
                } else {
                    die("No se pudo realizar el alta");
                }
            } else {
                $datos = [
                    'correct' => [
                        'nombre' => $nombre,
                        'email' => $email,
                        'contraseña' => $contraseña,
                    ],
                    'errors' => [
                        'nombre' => $nombreErr,
                        'email' => $emailErr,
                        'contraseña' => $contraseñaErr,
                    ]
                ];
                $this->vista('/registro_duenos/inicio', $datos);
            }
        } else {
            $datos = [
                'nombre' => '',
                'email' => '',
                'contraseña' => '',
            ];
            $this->vista('/registro_duenos/inicio', $datos);
        }
    }
}
