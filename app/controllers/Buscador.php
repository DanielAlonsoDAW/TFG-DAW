<?php
class Buscador extends Controlador
{
    public function __construct() {}
    public function index()
    {

        $this->vista('buscador/inicio');
    }
}
