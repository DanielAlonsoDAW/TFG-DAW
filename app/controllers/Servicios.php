<?php
// Definición de la clase Servicios que extiende de Controlador
class Servicios extends Controlador
{
    // Constructor de la clase
    public function __construct() {
        // Inicia la sesión
        session_start();
    }

    // Método principal que carga la vista de inicio de servicios
    public function index()
    {
        // Carga la vista 'servicios/inicio'
        $this->vista('servicios/inicio');
    }
}
