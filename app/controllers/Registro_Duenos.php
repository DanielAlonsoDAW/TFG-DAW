<?php
class Registro_Duenos extends Controlador
{
    public function __construct() {}
    public function index()
    {

        $this->vista('registro_duenos/inicio');
    }
}
