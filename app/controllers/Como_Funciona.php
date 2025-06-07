<?php
// Definición de la clase Como_Funciona que extiende de Controlador
class Como_Funciona extends Controlador
{
    // Constructor de la clase
    public function __construct()
    {
        // Inicia la sesión
        session_start();
    }

    // Método principal que carga la vista
    public function index()
    {
        // Carga la vista 'como_funciona/inicio'
        $this->vista('como_funciona/inicio');
    }
}
