<?php
class Home extends Controlador
{
    // Propiedad privada para el modelo de cuidadores
    private $cuidadorModelo;

    // Constructor de la clase
    public function __construct()
    {
        // Inicia la sesión
        session_start();

        // Acceso al modelo 'Cuidadores_Model'
        $this->cuidadorModelo = $this->modelo('Cuidadores_Model');
    }

    // Método principal que se ejecuta por defecto
    public function index()
    {
        // Obtiene los cuidadores TOP desde el modelo
        $cuidadores = $this->cuidadorModelo->obtenerCuidadoresTOP();

        // Carga la vista 'home/inicio' y le pasa los cuidadores
        $this->vista('home/inicio', ['cuidadores' => $cuidadores]);
    }
}
