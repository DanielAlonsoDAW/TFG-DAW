<?php
class Home extends Controlador
{
    private $cuidadorModelo;

    public function __construct()
    {
        session_start();

        //1) Acceso a los modelos
        $this->cuidadorModelo = $this->modelo('Cuidadores_Model');
    }
    public function index()
    {
        $cuidadores = $this->cuidadorModelo->obtenerCuidadoresTOP();

        $this->vista('home/inicio', ['cuidadores' => $cuidadores]);
    }
}
