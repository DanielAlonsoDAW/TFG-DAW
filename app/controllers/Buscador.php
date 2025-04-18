<?php
class Buscador extends Controlador
{
    public function __construct() {
        session_start();
    }
    public function index()
    {

        $this->vista('buscador/inicio');
    }
}
