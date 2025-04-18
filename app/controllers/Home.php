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

        $this->vista('home/inicio');
    }
}
