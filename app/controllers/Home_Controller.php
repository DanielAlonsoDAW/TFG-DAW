<?php
require RUTA_APP . "/librerias/FuncionesFormulario.php";
class Home extends Controlador
{
    private $homeModelo;

    public function __construct()
    {
        session_start();
        //1) Acceso al modelo
        $this->homeModelo = $this->modelo('Home_Model');
    }
    public function index()
    {
        if (isset($_SESSION['idioma'])) {
            switch ($_SESSION['idioma']) {
                case 'espanol':
                    $datos = [$this->homeModelo->idioma('espanol'), 'idioma' => 'espanol'];
                    break;
                case 'ingles':
                    $datos = [$this->homeModelo->idioma('ingles'), 'idioma' => 'ingles'];
                    break;
                case 'catalan':
                    $datos = [$this->homeModelo->idioma('catalan'), 'idioma' => 'catalan'];
                    break;
                case 'euskera':
                    $datos = [$this->homeModelo->idioma('euskera'), 'idioma' => 'euskera'];
                    break;
            }
            $this->vista('home/inicio', $datos);
        } else {
            $datos = [$this->homeModelo->idioma('espanol'), 'idioma' => 'espanol'];
            $this->vista('home/inicio', $datos);
        }
    }

    public function language()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (comprobarDatos($_POST['language']) && comprobarIdioma($_POST['language'])) {
                $idioma = test_input($_POST['language']);
                $_SESSION['idioma'] = $idioma;
            }
        }

        redireccionar('/home');
    }
}
