<?php
class Mascotas extends Controlador
{
    public function __construct() {
        session_start();
    }
    public function index()
    {

        $this->vista('servicios/inicio');
    }
}
