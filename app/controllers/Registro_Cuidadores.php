<?php
class Registro_Cuidadores extends Controlador
{
    public function __construct() {}
    public function index()
    {

        $this->vista('registro_cuidadores/inicio');
    }
}
