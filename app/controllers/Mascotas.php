<?php
class Mascotas extends Controlador
{
    public function __construct() {}
    public function index()
    {

        $this->vista('servicios/inicio');
    }
}
