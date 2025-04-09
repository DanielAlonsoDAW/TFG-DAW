<?php
require RUTA_APP . "/librerias/FuncionesFormulario.php";
class Login_Privado extends Controlador
{
    private $loginModelo;

    public function __construct()
    {
        session_start();
        if (isset($_SESSION['usuario']) && $_SESSION['grupo'] == 'admin') {
            redireccionar('/menu');
        }
        //1) Acceso al modelo
        $this->loginModelo = $this->modelo('Login_Privado_Model');
    }

    public function index()
    {
        $this->vista('login_privado/inicio');
    }

    public function login()
    {

        $loginErr = $passErr = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $login = comprobarDatos($_POST['username']) ? test_input($_POST['username']) : $loginErr = "Completa el campo Usuario \n";
            $password = comprobarDatos($_POST['password']) ? test_input($_POST['password']) : $passErr = "Completa el campo Contraseña\n";
            if (formularioErrores($loginErr, $passErr)) {
                $bbddPass = $this->loginModelo->obtenerPass($login);
                if ($bbddPass) {
                    if (password_verify($password, "$bbddPass->password")) {
                        $bbddGrupo = $this->loginModelo->obtenerGrupo($login);
                        if ($bbddGrupo->grupo == "admin") {
                            $_SESSION['usuario'] = $login;
                            $_SESSION['grupo'] = 'admin';
                            $this->vista('menu/inicio');
                        } else {
                            $_SESSION['error'] = "Usuario o contraseña incorrectos";
                            $this->vista('login_privado/inicio');
                        }
                    } else {
                        $_SESSION['error'] = "Usuario o contraseña incorrectos";
                        $this->vista('login_privado/inicio');
                    }
                } else {
                    $_SESSION['error'] = "Usuario o contraseña incorrectos";
                    $this->vista('login_privado/inicio');
                }
            } else {
                $_SESSION['error'] = "Usuario o contraseña incorrectos";
                $this->vista('login_privado/inicio');
            }
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        redireccionar('/home');
    }
}
