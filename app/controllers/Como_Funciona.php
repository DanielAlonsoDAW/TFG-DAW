<?php
class Como_Funciona extends Controlador
{
    public function __construct()
    {
    }
    public function index()
    {

        $this->vista('como_funciona/inicio');
    }
}
